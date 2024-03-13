<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
include("../src/Twilio/autoload.php");
$time_start 	= microtime(true);          
$InsererDansBD  = true;//Si on rencontre des erreurs, on assigne false a cette variable
/*
1-- Valider qu'on a un numéro de commande Optipro --> Order_num_optipro
2.0-- Vérifier si la clé  qui a été soumise existe bien dans la BD -->$ProductCode  et collecte des info pour validations produit 
2.1--Valider Sphere Versus   --> Min/Max  de la BD
2.2--Valider Cylindres       --> Min/Max  de la BD
2.3--Valider Fitting Height  --> Min/Max  de la BD
2.4--Valider Additions       --> Min/Max  de la BD
2.5--Valider si Additions differentes
3-- Valider le Lens_Category de la clé soumise
4-- Validation Produit SV
5-- Validation Produit Progressif
6-- Vérifier si  un model de frame à été soumis
7-- Insertion de la commande dans Orders
8-- Extra Produit:  Edging
9-- Extra Produit:  Frame
10- Extra Produit:  Tint
11- Extra Produit:  Prism
*/

$ftp_server = constant("FTP_WINDOWS_VM");
$ftp_user   = constant("FTP_USER_OPTIPRO_HBC");

$ftp_pass   = constant("FTP_PASSWORD_OPTIPRO_HBC");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
$message .= '<br><b>Connexion FTP </b>';
// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
	echo 'Connexion reussie';
}else {
	echo 'Probleme de connexion';
}
ftp_pasv($conn_id,true);
ftp_chdir($conn_id,"Optipro");
echo '<br>Dossier: Optipro';
$directory=ftp_pwd($conn_id);
$contents=ftp_nlist($conn_id, ".");
$max=0;
$newest_file="";
$ImporterCetteCommande =  'oui';

$Compteur = 0;

foreach ($contents as $value) {//FIND NEWEST FILE
	$Compteur += 1;
	$time=ftp_mdtm($conn_id,$value);
	if (strpos($value,".csv")!==false){
		//Insérer dans l'Array:
		$ArrayCSV[] = $value;
		//if ($time>$max){
		//	$max=$time;
			//$newest_file=$value;
		//}
	}
	
}

if ($ArrayCSV[0] <> ''){
//On trie l'array directement après l'avoir remplie
	sort($ArrayCSV);
	echo '<br><br>Contenu array apres sort:';
	var_dump($ArrayCSV);
	$newest_file = $ArrayCSV[0];//Le premier élément de l'array sera le csv traité
}



if ($newest_file==''){
	echo '<br>Aucun fichier a traiter';
	exit();
}
		
	echo "<br>TRAITEMENT DU FICHIER : ".$newest_file . '<br>';
	

$FichierCsvSansSlash = str_replace('./','',$newest_file);
//Insertion dans la table importation_optipro pour nous assurer d'identifier les fichiers qui pourraient bloquer l'importation afin de m'aviser
$QueryImportationOptipro  = "SELECT * FROM importation_optipro WHERE fichier_csv='$FichierCsvSansSlash'";
echo '<br>$QueryImportationOptipro: '.$QueryImportationOptipro;
$resultImportationOptipro =  mysqli_query($con,$QueryImportationOptipro)		or die  ('I cannot select items because  1: ' .$QueryImportationOptipro  . mysqli_error($con));

/*
if (mysqli_num_rows($resultImportationOptipro)>0){
	$DataImportationOptipro   =  mysqli_fetch_array($resultImportationOptipro,MYSQLI_ASSOC);
	$ID = $DataImportationOptipro[id];
	$sms_sent= $DataImportationOptipro[sms_sent];
	$ActuelleValeurCompteur = $DataImportationOptipro[compteur]; 
	$NouvelleValeurCompteur = $ActuelleValeurCompteur+1;
	
	//Avant de mettre à jour le compteur, on vérifie s'il est rendu a 4. 
	//Si c'est le cas,  on envoie un SMS pour aviser du problème. 
	if ($NouvelleValeurCompteur==4){
		//Envoie du SMS pour m'aviser du problème avec ce fichier CSV
		//LIVE CREDENTIALS TWILIO
		$sid    = "ACf4ad6dfeeda0ed7460f46a9453ae9d5c"; // Your Account SID from www.twilio.com/console
		$token  = "5f21fc960d2784177555bd05a183316c"; // Your Auth Token from www.twilio.com/console
		$client = new Twilio\Rest\Client($sid, $token);
		$message_HBC = $client->messages->create(
		  '18193831723', // Text this number
		  array(
			'from' => '18198055552', // From a valid Twilio number
			'body' => 'HBC: Probleme avec le fichier '. $FichierCsvSansSlash. ' On a tenté de l\'importer 4 fois, sans succès.' 
			)
		  );
		print $message_HBC->sid;
		$sms_sent='yes';
		echo '<br><br>SMS-->HBC:envoyé!';	
	}//End IF
	
	if ($NouvelleValeurCompteur==30){
		//Envoie du SMS pour m'aviser du problème avec ce fichier CSV
		//LIVE CREDENTIALS TWILIO
		$sid    = "ACf4ad6dfeeda0ed7460f46a9453ae9d5c"; // Your Account SID from www.twilio.com/console
		$token  = "5f21fc960d2784177555bd05a183316c"; // Your Auth Token from www.twilio.com/console
		$client = new Twilio\Rest\Client($sid, $token);
		$message_HBC = $client->messages->create(
		  '18193831723', // Text this number
		  array(
			'from' => '18198055552', // From a valid Twilio number
			'body' => 'HBC: Probleme avec le fichier '. $FichierCsvSansSlash. ' On a tenté de l\'importer 30 fois.. sans succès.' 
			)
		  );
		print $message_HBC->sid;
		$sms_sent='yes';
		echo '<br><br>SMS-->HBC:envoyé!';	
	}//End IF
	
	$queryUpdateImportationOptipro = "UPDATE importation_optipro 
	SET compteur = $NouvelleValeurCompteur,
	sms_sent = '$sms_sent' WHERE id =$ID "; 
	echo '<br>'.$queryUpdateImportationOptipro;
	$resultupdateImportationOptipro =  mysqli_query($con,$queryUpdateImportationOptipro)		or die  ('I cannot select items because  1: ' .$queryUpdateImportationOptipro  . mysqli_error($con));	
}else{
	//Ce fichier csv n'a jamais été importé jusqu'a maintenant donc  on l'enregistre dans importation_optipro
	$queryAjoutImportationOptipro  = "INSERT INTO importation_optipro (fichier_csv, compteur, sms_sent) VALUES ('$FichierCsvSansSlash',1,'')";
	echo '<br>$queryAjoutImportationOptipro :'. $queryAjoutImportationOptipro ;
	$resultAjoutImportationOptipro =  mysqli_query($con,$queryAjoutImportationOptipro)		or die  ('I cannot select items because  1: ' .$queryAjoutImportationOptipro  . mysqli_error($con));
}//End IF*/


$local_file  = 'Temporary_Fichier_temporaire_importation_optipro12345789.csv';
$server_file = $newest_file;

// try to download $server_file and save to $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
	echo 'Copie reussi<br>';
}else{
	echo 'Copie non reussi, echec.<br>';
}

// close the connection
$orderArray=array();
ftp_close($conn_id);  
$row = 1;
$handle = fopen("Temporary_Fichier_temporaire_importation_optipro12345789.csv", "r");
$count=0;
	
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {//COLLECT DATA INTO ARRAY FIRST

		$count++;	
		$OrderNumberOptipro     = $orderArray[$count][2]=$data[1];
		$USER_ID	  			= $orderArray[$count][3]=$data[2];
		
		if ($USER_ID=='griffe'){
			$USER_ID='88666';//Cacher le user id de griffé lunettier pour Swiss
		}
		
		//Valeur par défaut
		$AccountIsSubLicensee 		= "non";
		$FiltreAccountIsSubLicensee = " AND 81=81 ";  
		
		/*if ($USER_ID=="88433"){
			$AccountIsSubLicensee ="oui";
			$FiltreAccountIsSubLicensee =" AND price_sublicensee <> 0";
		}*/
		
		/*if ($USER_ID=="88438"){
			$AccountIsSubLicensee ="oui";
			$FiltreAccountIsSubLicensee =" AND price_sublicensee <> 0";
		}*/
		
		/*if ($USER_ID=="88439"){
			$AccountIsSubLicensee ="oui";
			$FiltreAccountIsSubLicensee =" AND price_sublicensee <> 0";
		}*/
		
		/*if ($USER_ID=="88430"){//TEST
			$AccountIsSubLicensee ="oui";
			$FiltreAccountIsSubLicensee =" AND price_sublicensee <> 0";
		}*/
		
		
		
		$TRAY_NUM	  			= $orderArray[$count][5]=$data[4];
		$MAIN_LAB	 			= $orderArray[$count][6]=$data[5];	 //HBC = 1
		$EYE 		  			= $orderArray[$count][8]=$data[7];
		//echo '<br> 	$EYE:'. $EYE;
		if ($EYE == '')
		$EYE = 'Both';

		$ORDER_ITEM_DATE	    = $orderArray[$count][10]=$data[9];
		echo '<br> 	$ORDER_ITEM_DATE:'. $ORDER_ITEM_DATE;
		
		$ORDER_PATIENT_FIRST 	= mysqli_real_escape_string($con,$orderArray[$count][14]=$data[13]);		
		$ORDER_PATIENT_LAST 	= mysqli_real_escape_string($con,$orderArray[$count][15]=$data[14]);				
		$SALESPERSON_ID 	    = str_replace(',','',$orderArray[$count][16]=$data[15]);	
			
		$ORDER_STATUS           = 'basket';//Basket pour ajout au basket le temps des tests, ensuite a processing
		$RE_SPHERE				= str_replace(',','.',$orderArray[$count][34]=$data[33]);	
		$LE_SPHERE 		        = str_replace(',','.',$orderArray[$count][35]=$data[34]);	
		
		//Initialisation par défaut pour empecher  que les commandes sans spheres  bloquent le traitement des csv
		if ($EYE=='Both'){
			if ($RE_SPHERE=='')	
				$RE_SPHERE=14;
			
			if ($LE_SPHERE=='')	
				$LE_SPHERE=14;
		}
		
		if ($EYE=='R.E.'){
			if ($RE_SPHERE=='')	
				$RE_SPHERE=14;
		}
		
		
		if ($EYE=='L.E.'){
			if ($LE_SPHERE=='')	
				$LE_SPHERE=14;
		}
		
			
		$RE_CYL		    	    = $orderArray[$count][36]=$data[35];	
		if ($RE_CYL ==' ')
		$RE_CYL='';
		
		$LE_CYL		     	    = $orderArray[$count][37]=$data[36];
		if ($LE_CYL ==' ')
		$LE_CYL='';
		
		$RE_ADD		     	 	= $orderArray[$count][38]=$data[37];
		$LE_ADD		     	    = $orderArray[$count][39]=$data[38];
		$RE_AXIS		 		= $orderArray[$count][40]=$data[39];
		$LE_AXIS		        = $orderArray[$count][41]=$data[40];
		
		
		//Prismes
		$RE_AX		        = $orderArray[$count][44]=$data[43];	
		$LE_AX		        = $orderArray[$count][43]=$data[42];	
		$RE_AX2		        = $orderArray[$count][42]=$data[41];	
		$LE_AX2		        = $orderArray[$count][45]=$data[44];	
		$RE_champ1		    = trim($orderArray[$count][46]=$data[45],' ');
		$RE_champ2		    = trim($orderArray[$count][47]=$data[46],' ');	
		$LE_champ1		    = trim($orderArray[$count][48]=$data[47],' ');	
		$LE_champ2	        = trim($orderArray[$count][49]=$data[48],' ');	
		

		$RE_PR_AX		        = trim($orderArray[$count][44]=$data[43],' ');	
		$LE_PR_AX2		        = trim($orderArray[$count][43]=$data[42],' ');	
		$RE_PR_AX2		        = trim($orderArray[$count][42]=$data[41],' ');	
		$LE_PR_AX		        = trim($orderArray[$count][45]=$data[44],' ');	
			
	
		
		/*
		  Anglais            		 Français	
		A)BO/Base Out    		A)BE/Base Externe
		B)BI/Base In    		B)BI/Base Interne  **SIMILAIRE DANS LES DEUX LANGUES**
		C)BU/Top BASE        	D)BH/Base Haut 
		D)BD/Base Down       	C)BB/Base Bas
		*/

		//Left Eye
		if ($LE_champ1=='BO')
			$LE_champ1='BE';
		if ($LE_champ1=='BI')
			$LE_champ1='BI';
		if ($LE_champ1=='BU')
			$LE_champ1='BH';
		if ($LE_champ1=='BD')
			$LE_champ1='BB';
		
		if ($LE_champ2=='BO')
			$LE_champ2='BE';
		if ($LE_champ2=='BI')
			$LE_champ2='BI';
		if ($LE_champ2=='BU')
			$LE_champ2='BH';
		if ($LE_champ2=='BD')
			$LE_champ2='BB';
		
		//Right Eye
		if ($RE_champ1=='BO')
			$RE_champ1='BE';
		if ($RE_champ1=='BI')
			$RE_champ1='BI';
		if ($RE_champ1=='BU')
			$RE_champ1='BH';
		if ($RE_champ1=='BD')
			$RE_champ1='BB';
		
		if ($RE_champ2=='BO')
			$RE_champ2='BE';
		if ($RE_champ2=='BI')
			$RE_champ2='BI';
		if ($RE_champ2=='BU')
			$RE_champ2='BH';
		if ($RE_champ2=='BD')
			$RE_champ2='BB';
	
	
		//Oeil gauche
		if (($LE_AX<>'')||($LE_AX2<>'')){
			if ((strtoupper($LE_champ1) == 'BH') || (strtoupper($LE_champ2) == 'BH')){// Haut =  Up		
				$LE_PR_UD = 'Up';
			}
			if ((strtoupper($LE_champ1) == 'BB') || (strtoupper($LE_champ2) == 'BB')){// Bas =  Down		
				$LE_PR_UD = 'Down';
			}
			if ((strtoupper($LE_champ1) == 'BI') || (strtoupper($LE_champ2) == 'BI')){// Interne =  In	
				$LE_PR_IO = 'In';
			}
			if ((strtoupper($LE_champ1) == 'BE') || (strtoupper($LE_champ2) == 'BE')){// Externe = Out	
				$LE_PR_IO = 'Out';
			}
		}//End IF
		

		//Oeil Droit
		if (($RE_AX<>'')||($RE_AX2<>'')){
			if ((strtoupper($RE_champ1) == 'BH') || (strtoupper($RE_champ2) == 'BH')){// Haut =  Up	
				$RE_PR_UD = 'Up';
			}
			if ((strtoupper($RE_champ1) == 'BB') || (strtoupper($RE_champ2) == 'BB')){// Bas =  Down	
				$RE_PR_UD = 'Down';
			}
			if ((strtoupper($RE_champ1) == 'BI') || (strtoupper($RE_champ2) == 'BI')){// Interne =  In	
				$RE_PR_IO = 'In';
			}
			if ((strtoupper($RE_champ1) == 'BE') || (strtoupper($RE_champ2) == 'BE')){// Externe = Out
				$RE_PR_IO = 'Out';
			}
		}//End IF
		
		
		if (($LE_PR_AX == '') && ($LE_PR_AX2 <> '')){//Axe2 = Up/Down
			if (($LE_PR_UD == '') && ($LE_PR_IO <> '')){
				$LE_PR_AX  = $LE_PR_AX2;
				$LE_PR_AX2 = '';
			}
		}	
		
		
		if (($RE_PR_AX == '') && ($RE_PR_AX2 <> '')){//Axe2 = Up/Down
			if (($RE_PR_UD == '') && ($RE_PR_IO <> '')){
				$RE_PR_AX  = $RE_PR_AX2;
				$RE_PR_AX2 = '';
			}
		}
		
		


		$RE_PD	        		= $orderArray[$count][50]=$data[49];	
		$RE_PD_NEAR	        	= $orderArray[$count][51]=$data[50];	
		$RE_HEIGHT	        	= str_replace(',','.',$orderArray[$count][52]=$data[51]);	
		if ($RE_HEIGHT=='')   
			$RE_HEIGHT=0;
		$LE_PD	       	 		= $orderArray[$count][53]=$data[52];
		$LE_PD_NEAR	        	= $orderArray[$count][54]=$data[53];	
		$LE_HEIGHT	       		= str_replace(',','.',$orderArray[$count][55]=$data[54]);	
		if ($LE_HEIGHT=='')   
			$LE_HEIGHT=0;
		$PT	        			= $orderArray[$count][56]=$data[55];	
		$PA	        			= $orderArray[$count][57]=$data[56];	
		$VERTEX	        		= $orderArray[$count][58]=$data[57];	
		$FRAME_A	        	= $orderArray[$count][59]=$data[58];	
		$FRAME_B         		= $orderArray[$count][60]=$data[59];	
		
		$FRAME_ED	        	= $orderArray[$count][61]=$data[60];
		if ($FRAME_ED =='')//Si vide, on met 0
		$FRAME_ED = 0;
		$FRAME_DBL	        	= $orderArray[$count][62]=$data[61];	
		
			
		$FRAME_TYPE	        	= $orderArray[$count][63]=$data[62];
		//Mettre en anglais le frame type selon ce qu'on a recu d'Optipro
		switch(strtolower($FRAME_TYPE)){
			case 'métal': 	    $FRAME_TYPE = 'Metal'; 			 break;	
			case 'metal': 	    $FRAME_TYPE = 'Metal'; 			 break;	
			case 'plastique':   $FRAME_TYPE = 'Plastic'; 		 break;
			case 'percage':     $FRAME_TYPE = 'Drill and Notch'; break;	
			case 'fil de nylon':$FRAME_TYPE = 'Nylon Groove';    break;	
			case 'fil de métal':$FRAME_TYPE = 'Metal Groove';    break;
			case 'fil de metal':$FRAME_TYPE = 'Metal Groove';    break;
			case 'griffe':      $FRAME_TYPE = 'GRIFFE';    		 break;//??	
			case 'avec clip':   $FRAME_TYPE = 'AVEC CLIP'; 		 break;//??	
			case 'autre':       $FRAME_TYPE = 'AUTRE';     		 break;//??	  
			//Pour Optipro HBC
			case 'plastic':   	$FRAME_TYPE = 'Plastic'; 		break;
			case 'plastic ':   	$FRAME_TYPE = 'Plastic'; 		break;
			case 'plastic ': 	$FRAME_TYPE = 'Plastic'; 		break;
			case 'metal':   	$FRAME_TYPE = 'Metal'; 		 	break;
			case 'drill and notch':     $FRAME_TYPE = 'Drill and Notch'; break;	
			case 'drill and notch ':     $FRAME_TYPE = 'Drill and Notch'; break;	
			case 'nylon groove':$FRAME_TYPE = 'Nylon Groove';    break;	
			case 'nylon groove ':$FRAME_TYPE = 'Nylon Groove';   break; 
			case 'metal groove':$FRAME_TYPE = 'Metal Groove';    break;
			case 'metal groove ':$FRAME_TYPE = 'Metal Groove';    break;
			default: $FRAME_TYPE = 'Plastic';     
		}
		

			
		$PRODUCT_NAME_OPTIPRO   = $orderArray[$count][19]=$data[18];  //Nom du produit dans optipro Ex: PROGRESSIF IFREE
		$PRODUCT_NAME_OPTIPRO   = str_replace('É','E',$PRODUCT_NAME_OPTIPRO);
		$PRODUCT_NAME_OPTIPRO   = str_replace('é','e',$PRODUCT_NAME_OPTIPRO);
		
		if ($PRODUCT_NAME_OPTIPRO == 'LENTILLE DU CLIENT'){//Meme nom utilisé pour Halifax, donc rien a changer
			$ImporterCetteCommande = 'non';
		}
		
		
		if ($PRODUCT_NAME_OPTIPRO=='IOFFICE'){
			$PRODUCT_NAME_OPTIPRO='IOFFICE HD';
		}
			
		
		$ORDER_PRODUCT_ID		= $orderArray[$count][20]=$data[19];  //TRES IMPORTANT
	    $ORDER_PRODUCT_INDEX	= $orderArray[$count][21]=$data[20];	  
		$ORDER_PRODUCT_COATING	= $orderArray[$count][29]=$data[28];  
		
		if ($ORDER_PRODUCT_COATING=='Xlr2'){
			$ORDER_PRODUCT_COATING='Xlr';//Transformer Maxiivue 2 en Maxiivue
		}
		if ($ORDER_PRODUCT_COATING=='HD AR 2'){
			$ORDER_PRODUCT_COATING='HD AR';//Transformer HD AR 2 en HD AR
		}
		
		//LES AR+ETC
		if ($ORDER_PRODUCT_COATING=='AR+ETC SWISS'){
			$ORDER_PRODUCT_COATING='AR+ETC';
		}
		
		if ($ORDER_PRODUCT_COATING=='AR+ETC HKO'){
			$ORDER_PRODUCT_COATING='AR+ETC';
		}
		
		if ($ORDER_PRODUCT_COATING=='AR+ETC GKB'){
			$ORDER_PRODUCT_COATING='AR+ETC';
		}
		
		//LES AR BACKSIDES
		if ($ORDER_PRODUCT_COATING=='AR BACKSIDE HKO'){
			$ORDER_PRODUCT_COATING='AR Backside';
		}
		
		if ($ORDER_PRODUCT_COATING=='AR BACKSIDE GKB'){
			$ORDER_PRODUCT_COATING='AR Backside';
		}
		
		if ($ORDER_PRODUCT_COATING=='AR BACKSIDE SWISS'){
			$ORDER_PRODUCT_COATING='AR Backside';
		}
		
		if ($ORDER_PRODUCT_COATING=='AR+ETC KNR'){
			$ORDER_PRODUCT_COATING='AR+ETC';
		}
		if ($ORDER_PRODUCT_COATING=='AR Backside KNR'){
			$ORDER_PRODUCT_COATING='AR Backside';
		}
		
	
		
		$ORDER_PRODUCT_PHOTO    = $orderArray[$count][30]=$data[29];
		if ($ORDER_PRODUCT_PHOTO == '')
		$ORDER_PRODUCT_PHOTO    = 'None';
		
		$ORDER_PRODUCT_POLAR    = $orderArray[$count][31]=$data[30];
		if ($ORDER_PRODUCT_POLAR =='')
		$ORDER_PRODUCT_POLAR    = 'None';
	
		if ($ORDER_PRODUCT_POLAR =='G15')
		$ORDER_PRODUCT_POLAR    = 'Green';
		
		
		$PRODUCT_CODE 		    = $orderArray[$count][17]=$data[16];  //EST VIDE A LA BASE
		$INTERNAL_NOTE          =  str_replace("'", ' ',$orderArray[$count][65]=$data[64]);
		$JOB_TYPE	            = $orderArray[$count][83]=$data[82];	// Uncut, Remote edging ou Edge and Mount	
		
		//STRTOUPPER AVANT d ANALYSER LE CONTENU DE LA NOTE
			
		$PositionUncut = strpos(strtoupper($INTERNAL_NOTE),'UNCUT');
		if ($PositionUncut !== false) {
			$JOB_TYPE   = 'Uncut';
		}
		
		
		$PositionNo2 = strpos($INTERNAL_NOTE,'#1');
		if ($PositionNo2 !== false) {
			$ORDER_PATIENT_LAST   = $ORDER_PATIENT_LAST . ' 1';
		}
		
		$PositionNo2 = strpos($INTERNAL_NOTE,'#2');
		if ($PositionNo2 !== false) {
			$ORDER_PATIENT_LAST   = $ORDER_PATIENT_LAST . ' 2';
		}
		
		$PositionNo3 = strpos($INTERNAL_NOTE,'#3');
		if ($PositionNo3 !== false) {
			$ORDER_PATIENT_LAST   = $ORDER_PATIENT_LAST . ' 3';
		}
		
		$PositionNo4 = strpos($INTERNAL_NOTE,'#4');
		if ($PositionNo4 !== false) {
			$ORDER_PATIENT_LAST   = $ORDER_PATIENT_LAST . ' 4';
		}
		
		$PositionNo5 = strpos($INTERNAL_NOTE,'#5');
		if ($PositionNo5 !== false) {
			$ORDER_PATIENT_LAST   = $ORDER_PATIENT_LAST . ' 5';
		}
		
		$PositionODBalance = strpos($INTERNAL_NOTE,'OD is a balance');
		if ($PositionODBalance !== false) {
			$SPECIAL_INSTRUCTIONS   =  'OD is a balance. ' .  $SPECIAL_INSTRUCTIONS ;
		}
		
		$PositionOSBalance = strpos($INTERNAL_NOTE,'OS is a balance');
		if ($PositionOSBalance !== false) {
			$SPECIAL_INSTRUCTIONS   =  'OS is a balance. ' .  $SPECIAL_INSTRUCTIONS ;
		}

		
		//Si la note contient 'reprise', on ne doit pas importer la commande
		$PositionReprise        = strpos(strtolower($INTERNAL_NOTE),'reprise');
		$PositionRedo        	= strpos(strtolower($INTERNAL_NOTE),'redo');
		if ($PositionReprise !== false) {
			$ESTUNEREPRISE   = 'oui';
		}elseif ($PositionRedo !== false) {
			$ESTUNEREPRISE   = 'oui';
		}else{
			$ESTUNEREPRISE   = '';	
		}
		echo '<br><br>Valeur champ estunereprise (oui ou vide):'.  $ESTUNEREPRISE;
		
		//Si la note contient 'park' ou PARK, on ne doit pas importer la commande puisquelle est en attente (bien que le client aie fait un dépot)
		$PositionPark        	= strpos(strtolower($INTERNAL_NOTE),'park');
		if ($PositionPark !== false){
			$ESTENATTENTE   = 'oui';
		}else{
			$ESTENATTENTE   = '';	
		}
		
		echo '<br><br>Valeur champ ESTENATTENTE (oui ou vide):'.  $ESTENATTENTE;
		
		$PositionRush = strpos(strtolower($INTERNAL_NOTE),'rush');
		if ($PositionRush !== false) {
			//echo "La chaine 'rush' a ete trouvee dans la chaîne '$INTERNAL_NOTE'";
			//echo " et débute à la position $pos";
			$SPECIAL_INSTRUCTIONS   = " RUSH ";
		}
		

		
		if ($JOB_TYPE =='Remoted Edging'){
			$JOB_TYPE = 'remote edging';
		}
		
		if ($JOB_TYPE =='Remote Edging'){
			$JOB_TYPE = 'remote edging';
		}
		
		if(($JOB_TYPE<>'Uncut') &&($JOB_TYPE<>'remote edging')){
			$JOB_TYPE ="Edge and Mount";	
		}
		
		
		//TEMPORAIREMENT HARD CODÉ POUR PRÉVENIR BUG OPTIPRO
		//$JOB_TYPE ="Edge and Mount";	

		
		$Position1erAcommercial = strpos(strtolower($INTERNAL_NOTE),'@');
		if($Position1erAcommercial !== false)
		$Position2emeAcommercial = strpos(strtolower($INTERNAL_NOTE),'@',$Position1erAcommercial +1);
		if (($Position1erAcommercial !== false) && ($Position2emeAcommercial !== false)){
			echo '<br>Position1erACommercial:'. $Position1erAcommercial;
			echo '<br>Position2emeACommercial:'. $Position2emeAcommercial;
			$LongeurTexteaInserer = $Position2emeAcommercial - $Position1erAcommercial;
			$InsererDansInstStecial = 	substr($INTERNAL_NOTE,$Position1erAcommercial+1,$LongeurTexteaInserer-1);
			$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' ' . $InsererDansInstStecial;
		}
		
		
		//GESTION DU CAS : UN OEIL SV ET L'AUTRE PROGRESSIF
		$UnSvUnProg = false;
		$PositionODSVHD = strpos(strtoupper($INTERNAL_NOTE),'OD SV HD');
		$PositionOSSVHD = strpos(strtoupper($INTERNAL_NOTE),'OG SV HD');
		$PositionODSV   = strpos(strtoupper($INTERNAL_NOTE),'OD SV');
		$PositionOSSV   = strpos(strtoupper($INTERNAL_NOTE),'OG SV');
		if ($PositionODSVHD !== false){
			$SPECIAL_INSTRUCTIONS   = $SPECIAL_INSTRUCTIONS . ' OD SV HD.';
			//Cette ligne écrase ce que contenais la variable $INTERNAL_NOTE
			$INTERNAL_NOTE =  ' Please adjust the price since one eye is a SV HD.';
			$UnSvUnProg = true;
		}elseif ($PositionOSSVHD !== false){
			$SPECIAL_INSTRUCTIONS   = $SPECIAL_INSTRUCTIONS . ' OG SV HD.';
			//Cette ligne écrase ce que contenais la variable $INTERNAL_NOTE
			$INTERNAL_NOTE =  ' Please adjust the price since one eye is a SV HD.';
			$UnSvUnProg = true;
		}elseif ($PositionODSV !== false){
			$SPECIAL_INSTRUCTIONS   = $SPECIAL_INSTRUCTIONS . ' OD SV.';
			//Cette ligne écrase ce que contenais la variable $INTERNAL_NOTE
			$INTERNAL_NOTE =  ' Please adjust the price since one eye is a SV.';
			$UnSvUnProg = true;
		}elseif($PositionOSSV !== false){
			$SPECIAL_INSTRUCTIONS   = $SPECIAL_INSTRUCTIONS . ' OG SV.';
			//Cette ligne écrase ce que contenais la variable $INTERNAL_NOTE
			$INTERNAL_NOTE =  ' Please adjust the price since one eye is a SV.';
			$UnSvUnProg = true;	
		}else{
			$INTERNAL_NOTE =  '';	
		}
		
		//Code pour le Code_Remise_Optipro
		if ($orderArray[$count][76]=$data[75]<>'xxx'){
			$CODE_REMISE_OPTIPRO =  mysqli_real_escape_string($con,$orderArray[$count][76]=$data[75]);// Exemple: 'GET2'
		}else{	
			$CODE_REMISE_OPTIPRO ='';// Exemple: 'GET2'
		}//END IF
		
		$SHIPPING_CODE	        = $orderArray[$count][77]=$data[76];// TOUJOURS 'OR005DLN'	
		$ENGRAVING	        	= $orderArray[$count][78]=$data[77];	
		
		//TINT
		$TINT	       			= $orderArray[$count][79]=$data[78];	
		$TINT_COLOR	            = $orderArray[$count][80]=$data[79];	
		if ($TINT_COLOR=='Orange')
		$TINT_COLOR='Shooter Orange';
		if ($TINT_COLOR=='Yellow')
		$TINT_COLOR='Shooter Yellow';
		if ($TINT_COLOR=='Rose')
		$TINT_COLOR='Sky Rose';
		
		$FROM_PERC	            = $orderArray[$count][81]=$data[80];	
		$TO_PERC	            = $orderArray[$count][82]=$data[81];
		//Details Monture
		$SUPPLIER	            = $orderArray[$count][84]=$data[83];	
		$FRAME_MODEL	        = $orderArray[$count][86]=$data[85];	
		$COLOR	            	= $orderArray[$count][87]=$data[86];	
		//Épaisseurs Spéciales
		
		$RE_CT	            	= $orderArray[$count][94]=$data[93];	
		$LE_CT	            	= $orderArray[$count][95]=$data[94];	
		$RE_ET	           		= $orderArray[$count][96]=$data[95];	
		$LE_ET	           	    = $orderArray[$count][97]=$data[96];	
		
		$PATIENT_REF_NUM	    = $orderArray[$count][92]=$data[91];	
		
		$BASE_CURVE	        	= $orderArray[$count][93]=$data[92];
		if ($BASE_CURVE <> ''){
			$BASE_CURVE             = substr($BASE_CURVE,1,4);
		}
		$SAFETY	            	= $orderArray[$count][98]=$data[97];	
		$FOLLOW_LENS_SHAPE	    = $orderArray[$count][100]=$data[99];	
		$TRACE	   				= $orderArray[$count][101]=$data[100];
	
if (($TRACE<>'') && ($TRACE<>'TRACE')){
			//LA TRACE N'EST PAS VIDE, JE TENTE D'ALLER CHERCHER LA VALEUR DBL DANS LE FICHIER DE TRACE, SI ÉCHEC, je dois utiliser la valeur du champ DBL reçu du csv d'Optipro
				
			echo '<br>Passer les lignes du fichier de trace (OMA)<br>';

			//$file = 'KUBIK KK3061 55.OMA';

			//$file=$TRACE;
			$file = "../../../../../ftp_root/Banque de traces/". $TRACE;
			echo 'Trace que je cherche: '. $file.'<br><br>';
			$searchfor = 'DBL=';
			
			// get the file contents, assuming the file to be readable (and exist)
			$contents = file_get_contents($file);

			// escape special characters in the query
			$pattern = preg_quote($searchfor, '/');

			// finalise the regular expression, matching the whole line
			$pattern = "/^.*$pattern.*\$/m";

			// search, and store all matching occurences in $matches
			if (preg_match_all($pattern, $contents, $matches))
			{
			  //echo "Found matches:\n";
			   //echo implode("\n", $matches[0]);
			   $DBL_Fichier_Trace = implode("\n", $matches[0]);
			}else
			{
			   echo "Impossible de trouver la valeur du champ DBL, je prendrai donc le DBL du csv recu d'Optipro";
			   $DBL_Fichier_Trace = $orderArray[$count][62]=$data[61];//Valeur dans le csv exporté d'Optipro	;s
			   //exit();
			}
			

			echo '<br>'. $DBL_Fichier_Trace;
			$DBL_Fichier_Trace=str_replace(' ','',$DBL_Fichier_Trace);
			$Longeur = strlen($DBL_Fichier_Trace);
			echo 'Longeur du fichier: '. $Longeur.'<br>';
			
			
			if ($Longeur==10){ //Ex: DLB=15.13
					$VraiDBL = substr($DBL_Fichier_Trace,4,5);
			}elseif($Longeur==9){//Ex: DBL=15.1
					$VraiDBL = substr($DBL_Fichier_Trace,4,4).'0';
			}elseif($Longeur==8){//Ex: DBL=15.1
					$VraiDBL = substr($DBL_Fichier_Trace,4,3) . '00';
			}//END IF
				
				echo '<br><br>Valeur du champ DBL: '. $VraiDBL;
		if ($VraiDBL>0){
			//Utiliser ce DBL dans la commande que je vais insérer dans la DB au lieu de celui dans le fichier csv exporté.	
			$FRAME_DBL = $VraiDBL;
		}	

			
}//END IF la trace n'est PAS vide




		$CORRIDOR				= $orderArray[$count][102]=$data[101];	
		$DESIGN	    			= $orderArray[$count][103]=$data[102];	
		$KNIFE_EDGE				= $orderArray[$count][104]=$data[103];	
		$INTERMEDIATE_DISTANCE 	= $orderArray[$count][108]=$data[107];
		$READING_DISTANCE		= $orderArray[$count][109]=$data[108];	
		$BISEAUX_POLIS 		    = $orderArray[$count][110]=$data[109];
		$LENTICULAIRE 		    = $orderArray[$count][111]=$data[110];
		$ARMOUR420   		    = $orderArray[$count][111]=$data[111];

		$IIMPACT   		        = $orderArray[$count][112]=$data[112];	
		$OPTICIEN   		    = $orderArray[$count][113]=$data[113];	
		$WARRANTY 				= $orderArray[$count][114]=$data[114];	//Accidental Breakage Coverage
		
		$UV400					= $orderArray[$count][115]=$data[115];	//UV400
		
		$CODE_SOURCE_MONTURE	= $orderArray[$count][117]=$data[117];	//Code sourde de la monture que je dois sauvegarder
		if ($CODE_SOURCE_MONTURE=='')
			$CODE_SOURCE_MONTURE=='TEST';
		
		$MFH			= $orderArray[$count][118]=$data[118];	//Minimum Fitting Height MFH
		$TOTAL_OPTIPRO	= $orderArray[$count][119]=$data[119];	//TOTAL_OPTIPRO
		$TOTAL_MONTURE_OPTIPRO	= $orderArray[$count][120]=$data[120];	//TOTAL_MONTURE_OPTIPRO


		
		$SIDE_SHIELD		 	= $orderArray[$count][107]=$data[106];	
		$AS_THIN_AS_POSSIBLE	= $orderArray[$count][106]=$data[105];	

		$MIRROR				    = $orderArray[$count][105]=$data[104];			
		
		//Aucun design fournis, si parmis les produits qui ont besoin d'un design, on met quotidien par défaut (Kassandra 10 mars 2016)
		if ($DESIGN == ''){
			
			//Si le produit demandé exige un design, on en force un par défaut
			switch($PRODUCT_NAME_OPTIPRO){
				case 'PRECISION+ TRANS BROWN LOW REFLEXION':	$DESIGN = "tout usage";	break;
				case 'PROGRESSIF DUO HD': 					$DESIGN = "";		  	break;
				case 'PROGRESSIF DUO INDIVIDUALISE':		$DESIGN = "quotidien";	break;
				case 'PROGRESSIF DUO INDIVIDUALISE 4D':		$DESIGN = "quotidien";	break;
				case 'PROGRESSIF INDUVIDUALISE 4D':			$DESIGN = "quotidien";	break;
				case 'PROGRESSIF INDIVIDUALISE 4D':			$DESIGN = "quotidien";	break;
				case 'PROGRESSIF HD ALPHA':					$DESIGN = "quotidien";	break;
				case 'PROGRESSIF CAMBER ':				    $DESIGN = "quotidien";	break;
				case 'PRECISION+ SUPER AR':   				$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ SUPER AR':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ LOW REFLEXION':			$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ HC': 				$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ POLAR BROWN HC': 	$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ POLAR BROWN SUPER AR':$DESIGN = "tout usage";break;
				case 'PROMO PRECISION+ POLAR GREY HC': 		$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ POLAR GREY SUPER AR':$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ SUPER AR': 			$DESIGN = "tout usage";	break;
				case 'PRECISION+ DRIVEWEAR HC':				$DESIGN = "tout usage";	break;
				case 'PRECISION+ DRIVEWEAR SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ HC':						$DESIGN = "tout usage";	break;
				case 'PRECISION+ PHOTO BROWN HC':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ PHOTO BROWN SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ PHOTO GREY HC':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ PHOTO GREY SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ POLAR BROWN HC':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ POLAR BROWN SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ POLAR GREY HC':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ POLAR GREY SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ SUPER AR':					$DESIGN = "tout usage";	break;
				case 'PRECISION+ TRANS BROWN HC':			$DESIGN = "tout usage";	break;	
				case 'PRECISION+ TRANS BROWN SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ TRANS GREY HC':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ TRANS GREY SUPER AR':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ UV420 HC':					$DESIGN = "tout usage";	break;
				case 'PRECISION+ UV420 SUPER AR':			$DESIGN = "tout usage";	break;
				case 'PRECISION+ XTRACTIVE BROWN HC':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ XTRACTIVE BROWN SUPER AR':	$DESIGN = "tout usage";	break;
				case 'PRECISION+ XTRACTIVE GREY HC':		$DESIGN = "tout usage";	break;
				case 'PRECISION+ XTRACTIVE GREY SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ DRIVEWEAR HC':			$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ DRIVEWEAR SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ HC':					$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ PHOTO BROWN HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ PHOTO BROWN SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ PHOTO GREY HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ PHOTO GREY SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ POLAR BROWN HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ POLAR BROWN SUPER AR': $DESIGN = "tout usage";	break;
				case '2ND PRECISION+ POLAR GREY HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ POLAR GREY SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ SUPER AR':				$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ TRANS BROWN HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ TRANS BROWN SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ TRANS GREY HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ TRANS GREY SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ UV420 HC':				$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ UV420 SUPER AR':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ XTRACTIVE BROWN HC':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ XTRACTIVE BROWN SUPER AR':	$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ XTRACTIVE GREY HC':		$DESIGN = "tout usage";	break;
				case '2ND PRECISION+ XTRACTIVE GREY SUPER AR':	$DESIGN = "tout usage";	break;
				case 'PROMO PRECISION+ POL GRIS HC':			$DESIGN = "exterieur";   break;
				case 'PROMO PRECISION+ POL GRIS AR BACK':		$DESIGN = "outdoor";   break;
				case 'PROMO PRECISION+ POL BRUN AR BACK':		$DESIGN = "exterieur";	break;
				case 'PROMO PRECISION+ POL BRUN HC':			$DESIGN = "exterieur";	break;
				default: echo '<br>P Name:'. $PRODUCT_NAME_OPTIPRO;
			}//End Switch
			
		}
		
		switch($PRODUCT_NAME_OPTIPRO){//Ces produits n'ont pas besoin de design, on le supprime donc si la succursale en a demandé un en particulier.
			case 'PROGRESSIF DUO HD':  						case 'DUO HD':        							$DESIGN = ""; break;  
		    case 'PROGRESSIF NUM IOT': 						case 'NUM IOT':        							$DESIGN = ""; break;       
			case 'PROGRESSIF HD IOT':  						case 'HD IOT':         							$DESIGN = ""; break;
			case 'PROGRESSIF INDIVIDUALISE IMPRESSION': 	case 'INDIVIDUALIZED IMPRESSION':     			$DESIGN = ""; break;
			case 'PROGRESSIF INDIVIDUALISE  IMPRESSION': 	case 'INDIVIDUALIZED IMPRESSION':     			$DESIGN = ""; break;
			case 'FT28':   			   						case 'FT28':           							$DESIGN = ""; break;
			case 'ST35':   			   						case 'ST35':           							$DESIGN = ""; break;
			case 'FORFAIT PLANO AR+ETC':					case 'PLANO CLEAR ETC SV PACKAGE': 				$DESIGN = ""; break;	
			case 'FORFAIT PLANO HC':  		 				case 'PLANO CLEAR HC SV PACKAGE ': 				$DESIGN = ""; break;	
			case 'FORFAIT PLANO TEINTE HC':   				case 'PLANO TINT HC SV PACKAGE':	 			$DESIGN = ""; break;	
			case 'FORFAIT SIMPLE VISION AR+ETC': 			case 'ETC SV PACKAGE':  						$DESIGN = ""; break;	
			case 'FORFAIT SIMPLE VISION HC':  	 			case 'HC SV PACKAGE':   						$DESIGN = ""; break;
			case 'FORFAIT SIMPLE VISION MAXIIVUE':  		case 'MAXIIVUE II SV PACKAGE': 					$DESIGN = ""; break;
			case 'FORFAIT SIMPLE VISION STRESSFREE':		case 'STRESSFREE SV PACKAGE': 					$DESIGN = ""; break;
			case 'SIMPLE VISION SURFACE':					case 'ASPHERIC SINGLE VISION':   		 		$DESIGN = ""; break;		
			case 'ROND 22':    								case 'ROUND 22':                     		  	$DESIGN = ""; break;  	
			case 'FORFAIT SV AR+ETC TRANSITIONS GRIS':  	case 'ETC GREY TRANS SV PACKAGE':	  	  		$DESIGN = ""; break; 	
			case 'FORFAIT SV AR+ETC TRANSITIONS BRUN': 		case 'ETC BROWN TRANS SV PACKAGE':  	  		$DESIGN = ""; break; 	
			case 'IOFFICE HD': 								case 'IOFFICE HD':     	  						$DESIGN = ""; break;	
			case 'DUO PREMIUM OFFICE': 						case 'DUO PREMIUM OFFICE': 		  				$DESIGN = ""; break;
			case 'IREADER': 								case 'IREADER':	 								$DESIGN = ""; break;	
			case 'IRELAX':  								case 'IRELAX': 	  								$DESIGN = ""; break;
			case 'IROOM':  									case 'IROOM':  	  								$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC':  			case 'INTERNET PROGRESSIVE ETC':  			  	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC POL. GRIS':	case 'INTERNET PROGRESSIVE ETC GREY POLAR':	  	$DESIGN = ""; break;
			case 'PROGRESSIF IFREE':  		   		    	case 'IFREE':	  								$DESIGN = ""; break;
			case 'iFREE 3':  		   		    			case 'IFREE 3':	  								$DESIGN = ""; break;
			case 'PROGRESSIF IACTION':  			    	case 'IACTION' :	  							$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC TRANS. GRIS':	case 'INTERNET PROGRESSIVE ETC GREY TRANS': 	$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET AR+ETC TRANS. BRUN':  case 'INTERNET PROGRESSIVE ETC BROWN TRANS ':  	$DESIGN = ""; break;	
			case 'SIMPLE VISION HD':  						case 'HD SINGLE VISION':      					$DESIGN = ""; break;
			case 'SIMPLE VISION IACTION': 					case 'IACTION SINGLE VISION ': 					$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET HC':  				case 'INTERNET PROGRESSIVE HC' :		  		$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET HC TRANS. GRIS':  	case 'INTERNET PROGRESSIVE HC GREY TRANS' : 	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET HC TRANS. BRUN': 	  	case 'INTERNET PROGRESSIVE HC BROWN TRANS': 	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET HC TRANSITION GRIS':  case 'INTERNET PROGRESSIVE HC GREY TRANS': 		$DESIGN = ""; break;
			case 'PROGRESSIF OPTOTECH': 		            case 'OPTOTECH':  								$DESIGN = ""; break; 	
			case 'PROGRESSIF DUO HD':  						case 'DUO HD':        							$DESIGN = ""; break;   
		    case 'PROGRESSIF NUM IOT': 						case 'NUM IOT':        							$DESIGN = ""; break;       
			case 'PROGRESSIF HD IOT':  						case 'HD IOT':         							$DESIGN = ""; break;
			case 'PROGRESSIF INDIVIDUALISE IMPRESSION': 	case 'INDIVIDUALIZED IMPRESSION':     			$DESIGN = ""; break;
			case 'PROGRESSIF INDIVIDUALISE  IMPRESSION': 	case 'INDIVIDUALIZED IMPRESSION':     			$DESIGN = ""; break;
			case 'FT28':   			   						case 'FT28':           							$DESIGN = ""; break;
			case 'ST35':   			   						case 'ST35':           							$DESIGN = ""; break;
			case 'FORFAIT PLANO AR+ETC':					case 'PLANO CLEAR ETC SV PACKAGE': 				$DESIGN = ""; break;	
			case 'FORFAIT PLANO HC':  		 				case 'PLANO CLEAR HC SV PACKAGE ': 				$DESIGN = ""; break;	
			case 'FORFAIT PLANO TEINTE HC':   				case 'PLANO TINT HC SV PACKAGE':	 			$DESIGN = ""; break;	
			case 'FORFAIT SIMPLE VISION AR+ETC': 			case 'ETC SV PACKAGE':  						$DESIGN = ""; break;	
			case 'FORFAIT SIMPLE VISION HC':  	 			case 'HC SV PACKAGE':   						$DESIGN = ""; break;
			case 'FORFAIT SIMPLE VISION MAXIIVUE':  		case 'MAXIIVUE II SV PACKAGE': 					$DESIGN = ""; break;
			case 'FORFAIT SIMPLE VISION STRESSFREE':		case 'STRESSFREE SV PACKAGE': 					$DESIGN = ""; break;
			case 'SIMPLE VISION SURFACE':					case 'ASPHERIC SINGLE VISION':   		 		$DESIGN = ""; break;		
			case 'ROND 22':    								case 'ROUND 22':                     		  	$DESIGN = ""; break;  	
			case 'FORFAIT SV AR+ETC TRANSITIONS GRIS':  	case 'ETC GREY TRANS SV PACKAGE':	  	  		$DESIGN = ""; break; 	
			case 'FORFAIT SV AR+ETC TRANSITIONS BRUN': 		case 'ETC BROWN TRANS SV PACKAGE':  	  		$DESIGN = ""; break; 	
			case 'IOFFICE': 								case 'IOFFICE':     	  						$DESIGN = ""; break;	
			case 'DUO PREMIUM OFFICE': 						case 'DUO PREMIUM OFFICE': 		  				$DESIGN = ""; break;
			case 'IREADER': 								case 'IREADER':	 								$DESIGN = ""; break;	
			case 'IRELAX':  								case 'IRELAX': 	  								$DESIGN = ""; break;
			case 'IROOM':  									case 'IROOM':  	  								$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC':  			case 'INTERNET PROGRESSIVE ETC':  			  	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC POL. GRIS':	case 'INTERNET PROGRESSIVE ETC GREY POLAR':	  	$DESIGN = ""; break;
			case 'PROGRESSIF IFREE':  		   		    	case 'IFREE':	  								$DESIGN = ""; break;
			case 'PROGRESSIF IACTION':  			    	case 'IACTION' :	  							$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET AR+ETC TRANS. GRIS':	case 'INTERNET PROGRESSIVE ETC GREY TRANS': 	$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET AR+ETC TRANS. BRUN':  case 'INTERNET PROGRESSIVE ETC BROWN TRANS ':  	$DESIGN = ""; break;	
			case 'SIMPLE VISION HD':  						case 'HD SINGLE VISION':      					$DESIGN = ""; break;
			case 'SIMPLE VISION IACTION': 					case 'IACTION SINGLE VISION ': 					$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET HC':  				case 'INTERNET PROGRESSIVE HC' :		  		$DESIGN = ""; break;	
			case 'PROGRESSIF INTERNET HC TRANS. GRIS':  	case 'INTERNET PROGRESSIVE HC GREY TRANS' : 	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET HC TRANS. BRUN': 	  	case 'INTERNET PROGRESSIVE HC BROWN TRANS': 	$DESIGN = ""; break;
			case 'PROGRESSIF INTERNET HC TRANSITION GRIS':  case 'INTERNET PROGRESSIVE HC GREY TRANS': 		$DESIGN = ""; break;
			case 'PROGRESSIF OPTOTECH': 		            case 'OPTOTECH':  								$DESIGN = ""; break; 
			//SAFETY	
			case 'SECURITY BIFOCAL':																		$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITE SIMPLE VISION': 				  	case  'SECURITE SV':							$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITY SIMPLE VISION': 				  	case  'SECURITY SV':							$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITE PROGRESSIF HD': 					case  'SECURITE HD PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITE PROGRESSIF HD ':																	$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITY PROGRESSIF HD ': 				case  'SECURITY HD PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITE PROGRESSIF CLASSIQUE':  		    case  'SECURITE CLASSIC PROGRESSIVE' : 			$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITY PROGRESSIF CLASSIQUE':  		    case  'SECURITY CLASSIC PROGRESSIVE' : 			$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITE PROGRESSIF INDIVIDUALISE':       case  'SECURITE INDIVIDUALIZED PROGRESSIVE':	$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITY PROGRESSIF INDIVIDUALISE':       case  'SECURITY INDIVIDUALIZED PROGRESSIVE':	$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITE PROGRESSIF NUMERIQUE HD':  		case  'SECURITE NUM. PROGRESSIVE':  			$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITY PROGRESSIF NUMERIQUE HD':  		case  'SECURITY NUM. PROGRESSIVE':  			$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITE PROGRESSIF NUMERIQUE HD ':  		case  'SECURITE NUM. PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITY PROGRESSIF NUMERIQUE HD ':  		case  'SECURITY NUM. PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITE PROGRESSIF NUMERIQUE ':  		case  'SECURITE NUM. PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITY PROGRESSIF NUMERIQUE ':  		case  'SECURITY NUM. PROGRESSIVE': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. SV AR':  		  					case  'SECUR. SV AR':  							$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. SV REVOLUTION AR':  		  		case  'SECUR. SV REVOLUTION AR': 				$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. SV HC':  		  					case  'SECUR. SV HC': 							$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. ST28 AR':                       	case  'SECUR. ST28 AR': 						$DESIGN = ""; $SAFETY='safety'; break;  
			case 'SECURITE ST28':							case  'SECURITE ST28': 							$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. PROG. NUM. AR':					case  'SECUR. NUM. PROGRESSIVE AR': 			$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. PROG. NUM. HC':					case  'SECUR. NUM. PROGRESSIVE HC':				$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECURITE IRELAX':                         case  'SECURITE IRELAX':  						$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECURITY IRELAX':                         case  'SECURITY IRELAX':  						$DESIGN = ""; $SAFETY='safety'; break;
			case 'SECUR. PROG. DE BASE HC':					case  'SECUR. CLASSIC PROGRESSIVE HC': 			$DESIGN = ""; $SAFETY='safety'; break;	
			case 'SECUR. ST28 HC':																			$DESIGN = ""; $SAFETY='safety'; break;	
		}//End Switch
		
		if ($ORDER_PRODUCT_PHOTO=='Drivewear')
		$ORDER_PRODUCT_POLAR ='Drivewear';
		
		if ($ORDER_PRODUCT_POLAR=='Drivewear')
		$ORDER_PRODUCT_PHOTO ='Drivewear';
		

		$ORDER_FROM 		   = 'hbc';
		
}//End while


$LAB = 1;

	
	
$RxData = '';
$RxData .='<br><table width="950" cellpadding="2"  cellspacing="0" border="1" class="TextSize">
<tr><td align="center" bgcolor="#E4E4E4" colspan="21"><h2>RX</h2></td></tr>
<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">&nbsp;</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>SPHERES</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>CYLINDERS	</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>AXIS</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>HEIGHTS</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>ADD</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>Prism</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PD</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>NEAR PD</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PT</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PA</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>VERTEX</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>EDGE THICKNESS</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>CENTER THICKNESS</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>BASE CURVE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>SAFETY</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>READING DISTANCE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>INTERMEDIATE_DISTANCE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>INTERNAL NOTE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>UV400</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>Code Source Monture</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TOTAL OPTIPRO</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TOTAL MONTURE OPTIPRO</strong></td>
</tr>
<tr>
<td class="formCellNosides" align="right">R.E.</td>
<td class="formCellNosides" align="center">'.$RE_SPHERE.'</td>
<td class="formCellNosides" align="center">'.$RE_CYL.'</td>
<td class="formCellNosides" align="center">'.$RE_AXIS.'</td>
<td class="formCellNosides" align="center">'.$RE_HEIGHT.'</td>
<td class="formCellNosides" align="center">'.$RE_ADD.'</td>
<td class="formCellNosides" align="center">&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="formCellNosides" align="center">'.$RE_PD.'</td>
<td class="formCellNosides" align="center">'.$RE_PD_NEAR.'</td>
<td class="formCellNosides" align="center">'.$PT.'</td>
<td class="formCellNosides" align="center">'.$PA.'</td>
<td class="formCellNosides" align="center">'.$VERTEX.'</td>
<td class="formCellNosides" align="center">'.$RE_ET.'</td>
<td class="formCellNosides" align="center">'.$RE_CT.'</td>
<td class="formCellNosides" align="center">'.$BASE_CURVE.'</td>
<td class="formCellNosides" align="center">'.$SAFETY.'</td>
<td class="formCellNosides" align="center">'.$READING_DISTANCE.'</td>
<td class="formCellNosides" align="center">'.$INTERMEDIATE_DISTANCE.'</td>
<td class="formCellNosides" align="center">'.$INTERNAL_NOTE.'</td>
<td class="formCellNosides" align="center">'.$UV400.'</td>
<td class="formCellNosides" align="center">'.$CODE_SOURCE_MONTURE.'</td>
<td class="formCellNosides" align="center">'.$TOTAL_OPTIPRO.'</td>
<td class="formCellNosides" align="center">'.$TOTAL_MONTURE_OPTIPRO.'</td>
</tr>
<tr>
<td class="formCellNosides" align="right">L.E.</td>
<td class="formCellNosides" align="center">'.$LE_SPHERE.'</td>
<td class="formCellNosides" align="center">'.$LE_CYL.'</td>
<td class="formCellNosides" align="center">'.$LE_AXIS.'</td>
<td class="formCellNosides" align="center">'.$LE_HEIGHT.'</td>
<td class="formCellNosides" align="center">'.$LE_ADD.'</td>
<td class="formCellNosides" align="center">&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="formCellNosides" align="center">'.$LE_PD.'</td>
<td class="formCellNosides" align="center">'.$LE_PD_NEAR.'</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">'.$LE_ET.'</td>
<td class="formCellNosides" align="center">'.$LE_CT.'</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">&nbsp;</td>
<td class="formCellNosides" align="center">&nbsp;</td>
</tr></table><br>';

if (strtoupper($BISEAUX_POLIS) == 'BISEAUX POLIS'){//On ajoute dans l'instruction spéciale si pas vide
	$SPECIAL_INSTRUCTIONS   = $SPECIAL_INSTRUCTIONS . '  *EDGE POLISH*';
	//echo '<br>BISEAUX POLIS !';
}else{
	//echo '<br>Contenu du champ:'. $BISEAUX_POLIS;	
}

if ($TINT <> '')
{
	$RxData .='<table width="325" cellpadding="2"  cellspacing="0" border="1" class="TextSize">
	<tr><td align="center" bgcolor="#E4E4E4" colspan="16"><h2>TINT</h2></td></tr>
	<tr>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TYPE</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>COLOR</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>FROM</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TO</strong></th>
	</tr>';
	$RxData .= '<tr>
	<td class="formCellNosides" align="center">'.$TINT.'</td>
	<td class="formCellNosides" align="center">'.$TINT_COLOR.'</td>
	<td class="formCellNosides" align="center">'.$FROM_PERC.'</td>
	<td class="formCellNosides" align="center">'.$TO_PERC.'</td>
	</tr>';
	$RxData .= '</table><br>';
}//End if Tint n'est pas vide

$ProdHeight = " ";//Initialiser cette variable
$ProduitIdentifier  = true;
$AjoutTeintePromo   = 'non';



//Vérification  si Armour 420 + photo ou polar  demandé = Impossible, uniquement offert en clair.
$PhotoMajuscule = strtoupper($ORDER_PRODUCT_PHOTO);
$PolarMajuscule = strtoupper($ORDER_PRODUCT_POLAR);
echo '<br><br>Armour 420:'. $ARMOUR420;
echo '<br>PHOTO:'. $PhotoMajuscule;
echo '<br>POLAR:'. $PolarMajuscule;

if (($ARMOUR420=='armour 420') && ($PhotoMajuscule<>'NONE' || $PolarMajuscule<>'NONE')){
	$ErrorDetail   .=" Armour 420 Option is not available with  transitions or polarized lenses.";
	$InsererDansBD  = false;
}




if (($ORDER_PRODUCT_INDEX=='1.74') && ($ORDER_PRODUCT_POLAR=='Grey' || $ORDER_PRODUCT_POLAR=='Brown')){
	//Polarisé Jamais  offert  en 1.74
	$ErrorDetail.=" Polarized are never available with index 1.74";
	$InsererDansBD  = false;	
	}

/*
if ($ORDER_PRODUCT_PHOTO== 'Green'){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Transitions Green is not available.<br>';
}//Fin si aucun corridor n'a été fournis
*/






//Vérification Si Stock avec cylindre plus bas que -3
switch($PRODUCT_NAME_OPTIPRO){
		
case 'ENFANT STOCK AR+ETC':		
case 'ETC GREY TRANS SV PACKAGE':
case 'ETC KIDS STOCK ':
case 'ETC SV PACKAGE':
case 'ETC SV STOCK':
case 'ETC SV STOCK ':
case 'FORFAIT PLANO AR+ETC':
case 'FORFAIT SIMPLE VISION AR+ETC':
case 'FORFAIT SIMPLE VISION HC':
case 'FORFAIT SIMPLE VISION MAXIIVUE':
case 'FORFAIT SIMPLE VISION STRESSFREE':
case 'FORFAIT SV AR+ETC TRANSITIONS GRIS':
case 'HC SV PACKAGE':
case 'HC SV STOCK':
case 'HC SV STOCK ':
case 'MAXIIVUE II SV PACKAGE':
case 'MAXIIVUE II SV STOCK':
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN':
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.5':
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.6':
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.67':
case 'PACKAGE SV AR+ETC TRANSITIONS GREY':
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.5':
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.6':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROW':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROW 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.6':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.67':
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY':
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.6':
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.67':
case 'PACKAGE SV RX MAXIIVUE TRANSITION BROWN':
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN':
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.5':
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.6':
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.67':
case 'PLANO CLEAR ETC SV PACKAGE':
case 'STRESSFREE SV PACKAGE':
case 'STRESSFREE SV STOCK':
case 'STRESSFREE SV STOCK':
case 'STRESSFREE SV STOCK':
case 'SV STOCK':
case 'SV STOCK ETC PACKAGE ':

case 'OFFICE (2M)': //HKO

	if ($ORDER_PRODUCT_COATING=='Low Reflexion'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Office (2M) (Central Lab) avec Low Reflexion(Essilor Lab) = Combinaison impossible. Office (2M)(Central Lab) with Low Reflexion (Essilor Lab) = Impossible combination.";
		$InsererDansBD  = false;
	}
	
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name not like '%420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'OFFICE UV420 (2M)': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name like '%420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

		
//Le produit demandé est du Stock, on doit évaluer le/les cylindre(s) 		
	
if ($EYE == 'Both'){
	/*if ($RE_CYL<-2){
		$ErrorDetail   .="All stock lenses max Cylinder is -2.00. This order needs to be done in surface.";
		$InsererDansBD  = false;	
	}
	if ($LE_CYL<-2){
		$ErrorDetail   .="All stock lenses max Cylinder is -2.00. This order needs to be done in surface.";
		$InsererDansBD  = false;	
	}*/
	
	
}elseif($EYE == 'R.E.'){
	/*if ($RE_CYL<-2){
		$ErrorDetail   .="All stock lenses max Cylinder is -2.00. This order needs to be done in surface.";
		$InsererDansBD  = false;	
	}*/			
	
	
}elseif($EYE == 'L.E.'){
	/*if ($LE_CYL<-2){
		$ErrorDetail   .="All stock lenses max Cylinder is -2.00. This order needs to be done in surface.";
		$InsererDansBD  = false;	
	}			*/
	
	
}
		
}//End Case Validation Cyl  si Stock












//Identifier  le produit que l'entrepot veut commander
switch($PRODUCT_NAME_OPTIPRO){
	
	
case 'NEW SINGLE VISION'://Représentent les SV, [autant Stock que Surface]
	
	//First, we presume that we can use Stock. 
	$STOCK_OU_RX="STOCK";

	//1:Analyse all the details that might exclude Stock from our choices
	//A)Prism(s)
	if (($RE_PR_AX<>'')|| ($RE_PR_AX2<>'')  || ($LE_PR_AX<>'')    || ($LE_PR_AX2<>'')){
		//Signifie qu'au moins un prisme est demand. Donc, on oublie le stock.
		$STOCK_OU_RX="RX";
	}//END IF (A)
	echo '<br><br>Resultat apres evaluation A) Prisme:<b>'. $STOCK_OU_RX. '</b>';
	
	
	//B)Polarized
	if ($ORDER_PRODUCT_POLAR<>'None'){
		//Signifie que des verres polarisés sont demandés. Donc, on oublie le stock.
		$STOCK_OU_RX="RX";
	}//END IF (B)
	echo '<br><br>Resultat apres evaluation B)Polarisé:<b>'. $STOCK_OU_RX. '</b>';
	
	
	/*//C)Optical Center-->Within the fields  'Fitting Heights'
	if (($LE_HEIGHT<>'')|| ($RE_HEIGHT<>'')){
		//Means an optical center has been requested. Stock won't be an option.	
		$STOCK_OU_RX="RX";	
	}//END IF (C)
	echo '<br><br>Resultat apres evaluation C)Centre Optique:<b>'. $STOCK_OU_RX. '</b>';*/


	//D)Cylinders
	$ValeurLimiteCylindreNegatif = -4;
	if(($RE_CYL < $ValeurLimiteCylindreNegatif) || ($LE_CYL < $ValeurLimiteCylindreNegatif)){
		//Means that at least an eye contains a cyl lower than -4. Stock won't be an option.	
		$STOCK_OU_RX="RX";	
	}//END IF (D)
	echo '<br><br>Resultat apres evaluation D)Cylindres:<b>'. $STOCK_OU_RX. '</b>';


	//E)Index: If 1.74 = Rx 
	if ($ORDER_PRODUCT_INDEX==1.74){
		//Means that the requested index is 1.74. Stock won't be an option.
		$STOCK_OU_RX="RX";		
	}//END IF (E)
	echo '<br><br>Resultat apres evaluation E)Indice:<b>'. $STOCK_OU_RX. '</b>';


	//F)Armour 420
	if ($ARMOUR420<>''){
		//Means that an armour 420 has been requested. Stock won't be an option.	
		$STOCK_OU_RX="RX";		
	}//END IF
	echo '<br><br>Resultat apres evaluation F)Armour420:<b>'. $STOCK_OU_RX . '</b>';
	
	
	//G)Drivewear
	if ($ORDER_PRODUCT_PHOTO=='Drivewear'){
		//Means that a drivewear has been requested. Stock won't be an option.	
		$STOCK_OU_RX="RX";		
	}//END IF
	echo '<br><br>Resultat apres evaluation G)Drivewear:<b>'. $STOCK_OU_RX. '</b>';
	
	
	//H)Transitions Extra Active Grey
	if ($ORDER_PRODUCT_PHOTO=='Extra Active Grey'){
		//Means that a Transitions Extra Active Grey has been requested. Therefore, stock won't be an option.	
		$STOCK_OU_RX="RX";		
	}
	echo '<br><br>Resultat apres evaluation H)Transitions Extra Active Grey:<b>'. $STOCK_OU_RX. '</b>';
	
	
	//I)TINT
	if ($TINT<>''){
		//Means that a tint has been requested. Therefore, Stock won't be an option.	
		$STOCK_OU_RX="RX";		
	}
	echo '<br><br>Resultat apres evaluation I)Tint:<b>'. $STOCK_OU_RX. '</b>';
	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	
	//Évaluation si l'indice est 1.53,si c'est le cas, la commande ne se ferait pas en stock automatiquement
	if ($ORDER_PRODUCT_INDEX=='1.53'){	
		$STOCK_OU_RX="RX";	
	}
	echo '<br><br>Resultat apres evaluation Index 1.53):<b>'. $STOCK_OU_RX. '</b>';
	
	
	//Évaluation si l'indice est 1.59 et si le traitement demandé est Maxivue2,si c'est le cas, la commande ne se ferait pas en stock: automatique
	if ($ORDER_PRODUCT_INDEX=='1.59' && $ORDER_PRODUCT_COATING=='Xlr'){	
		$STOCK_OU_RX="RX";	
	}
	echo '<br><br>Resultat apres evaluation Index 1.59 + Xlr):<b>'. $STOCK_OU_RX. '</b>';
	
	//J)Calcul du diamètre nécessaire ? IMPORTANT: Si on ajoute ceci, on doit s'assurer que les diamètres des produits sont EXACTS dans la BD d'HBC..
	//RÈGLE: Minimum blank Size (MBS) =  Frame_ED + (2X la décentration) +2
	//ATTENTE DE VALIDER LA REGLE A UTILISER AVEC ROBERTO CAR CELLE QUE J'AI TROUVÉ DIFFÈRE DE CELLE DE DANIELLE

	//Define Min/Max Sphere allowed for Stock products and Min Cyl
	//1.50
	$CylMinStock150_Clear 	  = -2.00;          $CylMinStock150_Transitions 	= -2.00;
	$SphereMaxStock150_Clear  =  3.25;		   	$SphereMaxStock150_Transitions  =  4.00;
	$SphereMinStock150_Clear  = -3.00;			$SphereMinStock150_Transitions  = -6.00;
	
	//1.59
	$CylMinStock159_Clear     = -3.00; 			$CylMinStock159_Transitions     = -2.00;
	$SphereMaxStock159_Clear  =  3.75; 			$SphereMaxStock159_Transitions 	=  4.00;
	$SphereMinStock159_Clear  = -4.00; 			$SphereMinStock159_Transitions 	= -8;
	
	//1.60
	$CylMinStock160_Clear     = -2.00;			$CylMinStock160_Transitions    	= -2.00;
	$SphereMaxStock160_Clear  =  2.75;			$SphereMaxStock160_Transitions 	=  4.00;
	$SphereMinStock160_Clear  = -6.00;			$SphereMinStock160_Transitions 	= -6.00;
	
	//1.67
	$CylMinStock167_Clear     = -2.00;			$CylMinStock167_Transitions     = -2.00;
	$SphereMaxStock167_Clear  =  4.00;			$SphereMaxStock167_Transitions  =  4.00;
	$SphereMinStock167_Clear  = -6.00;			$SphereMinStock167_Transitions  = -8.00;
	
	//1.74
	$CylMinStock174_Clear     =  -2.00;			$CylMinStock174_Transitions    	=  0;
	$SphereMaxStock174_Clear  =  -3.00;			$SphereMaxStock174_Transitions 	=  0;
	$SphereMinStock174_Clear  = -10.00;			$SphereMinStock174_Transitions 	=  0;
	
	
	
	
	//IF STOCK IS STILL  AN OPTION HERE, LET'S DEFINE THE MINIMUM CYL, MIN AND MAX SPHERE AND VALIDATE IF THE REQUESTED INDEX MATCHES WITH THE ACCEPTED RANGE
	//VALIDATION FOR STOCK CLEAR LENSES
	if (($STOCK_OU_RX=='STOCK') && ($ORDER_PRODUCT_PHOTO=='None')){
		//Check if cylinders matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_CYL < $CylMinStock150_Clear) || ($LE_CYL < $CylMinStock150_Clear)) {$STOCK_OU_RX="RX";} 	break;	
			case 1.59:	if (($RE_CYL < $CylMinStock159_Clear) || ($LE_CYL < $CylMinStock159_Clear)) {$STOCK_OU_RX="RX";} 	break;	
			case 1.6:	if (($RE_CYL < $CylMinStock160_Clear) || ($LE_CYL < $CylMinStock160_Clear)) {$STOCK_OU_RX="RX";} 	break;	
			case 1.67:	if (($RE_CYL < $CylMinStock167_Clear) || ($LE_CYL < $CylMinStock167_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.74:	if (($RE_CYL < $CylMinStock174_Clear) || ($LE_CYL < $CylMinStock174_Clear)) {$STOCK_OU_RX="RX";}	break;	
		}//End Switch
		
		echo '<br><br>Resultat apres evaluation Cylindre (Non-Transition Product)<b>'. $STOCK_OU_RX. '</b>';
		
		//Check if Minimum Spheres matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_SPHERE < $SphereMinStock150_Clear) || ($LE_SPHERE < $SphereMinStock150_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.59:	if (($RE_SPHERE < $SphereMinStock159_Clear) || ($LE_SPHERE < $SphereMinStock159_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.6:	if (($RE_SPHERE < $SphereMinStock160_Clear) || ($LE_SPHERE < $SphereMinStock160_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.67:	if (($RE_SPHERE < $SphereMinStock167_Clear) || ($LE_SPHERE < $SphereMinStock167_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.74:	if (($RE_SPHERE < $SphereMinStock174_Clear) || ($LE_SPHERE < $SphereMinStock174_Clear)) {$STOCK_OU_RX="RX";}	break;	
		}//End Switch
		
		echo '<br><br>Resultat apres evaluation Sphere Minimum (Non-Transition Product)<b>'. $STOCK_OU_RX. '</b>';
		
		//Check if Maximum Spheres matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_SPHERE > $SphereMaxStock150_Clear) || ($LE_SPHERE > $SphereMaxStock150_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.59:	if (($RE_SPHERE > $SphereMaxStock159_Clear) || ($LE_SPHERE > $SphereMaxStock159_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.6:	if (($RE_SPHERE > $SphereMaxStock160_Clear) || ($LE_SPHERE > $SphereMaxStock160_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.67:	if (($RE_SPHERE > $SphereMaxStock167_Clear) || ($LE_SPHERE > $SphereMaxStock167_Clear)) {$STOCK_OU_RX="RX";}	break;	
			case 1.74:	if (($RE_SPHERE > $SphereMaxStock174_Clear) || ($LE_SPHERE > $SphereMaxStock174_Clear)) {$STOCK_OU_RX="RX";}	break;	
		}//End Switch
		
			echo '<br><br>Resultat apres evaluation Sphere Maximum (Non-Transition Product)<b>'. $STOCK_OU_RX. '</b>';

	}//End IF 'STOCK IS STILL possible' AND LENSES ARE CLEAR
	
	

	//VALIDATION FOR STOCK LENSES + TRANSITIONS 
	if (($STOCK_OU_RX=='STOCK') && ($ORDER_PRODUCT_PHOTO<>'None')){
		//Check if cylinders matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_CYL < $CylMinStock150_Transitions) || ($LE_CYL < $CylMinStock150_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.59:	if (($RE_CYL < $CylMinStock159_Transitions) || ($LE_CYL < $CylMinStock159_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.6:	if (($RE_CYL < $CylMinStock160_Transitions) || ($LE_CYL < $CylMinStock160_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.67:	if (($RE_CYL < $CylMinStock167_Transitions) || ($LE_CYL < $CylMinStock167_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.74:	if (($RE_CYL < $CylMinStock174_Transitions) || ($LE_CYL < $CylMinStock174_Transitions)) {$STOCK_OU_RX="RX";} break;	
		}//End Switch
		
		echo '<br><br>Resultat apres evaluation Cylindre (Transition Product)<b>'. $STOCK_OU_RX. '</b>';
		
		
		//Check if Minimum Spheres matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_SPHERE < $SphereMinStock150_Transitions) || ($LE_SPHERE < $SphereMinStock150_Transitions)) {$STOCK_OU_RX="RX";} 	break;	
			case 1.59:	if (($RE_SPHERE < $SphereMinStock159_Transitions) || ($LE_SPHERE < $SphereMinStock159_Transitions)) {$STOCK_OU_RX="RX";}	break;	
			case 1.6:	if (($RE_SPHERE < $SphereMinStock160_Transitions) || ($LE_SPHERE < $SphereMinStock160_Transitions)) {$STOCK_OU_RX="RX";}	break;	
			case 1.67:	if (($RE_SPHERE < $SphereMinStock167_Transitions) || ($LE_SPHERE < $SphereMinStock167_Transitions)) {$STOCK_OU_RX="RX";}	break;	
			case 1.74:	if (($RE_SPHERE < $SphereMinStock174_Transitions) || ($LE_SPHERE < $SphereMinStock174_Transitions)) {$STOCK_OU_RX="RX";}	break;	
		}//End Switch
		echo '<br><br>Resultat apres evaluation Sphere Minimum (Transition Product)<b>'. $STOCK_OU_RX. '</b>';
		
		//Check if Maximum Spheres matches with Stock or not
		switch($ORDER_PRODUCT_INDEX){
			case 1.5:	if (($RE_SPHERE > $SphereMaxStock150_Transitions) || ($LE_SPHERE > $SphereMaxStock150_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.59:	if (($RE_SPHERE > $SphereMaxStock159_Transitions) || ($LE_SPHERE > $SphereMaxStock159_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.6:	if (($RE_SPHERE > $SphereMaxStock160_Transitions) || ($LE_SPHERE > $SphereMaxStock160_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.67:	if (($RE_SPHERE > $SphereMaxStock167_Transitions) || ($LE_SPHERE > $SphereMaxStock167_Transitions)) {$STOCK_OU_RX="RX";} break;	
			case 1.74:	if (($RE_SPHERE > $SphereMaxStock174_Transitions) || ($LE_SPHERE > $SphereMaxStock174_Transitions)) {$STOCK_OU_RX="RX";} break;	
		}//End Switch
		echo '<br><br>Resultat apres evaluation Sphere Maximum (Transition Product)<b>'. $STOCK_OU_RX. '</b>';
	}//End IF 'STOCK IS STILL possible' AND LENSES ARE NOT CLEAR
	
	
	
	//Cas particuliers
	
	//Cas #1: Stock + Indice 1.59 + Transitions + Maxivue2: Doit être du RX..
	if (($STOCK_OU_RX==='STOCK')&& ($ORDER_PRODUCT_INDEX=='1.59')&&($ORDER_PRODUCT_PHOTO<>'None') && ($ORDER_PRODUCT_COATING='Xlr')){
		$STOCK_OU_RX="RX";
		echo '<br><br>Resultat apres evaluation Cas particuliers #1 (Stock + 1.59 + Transitions + Maxivue)<b>'. $STOCK_OU_RX. '</b>';
	}


	//Cas #1: Stock + Indice 1.5 + Transitions + HARD COAT : Doit être du RX..
	if (($STOCK_OU_RX==='STOCK')&& ($ORDER_PRODUCT_INDEX=='1.5')&&($ORDER_PRODUCT_PHOTO<>'None') && ($ORDER_PRODUCT_COATING='Hard Coat')){
		$STOCK_OU_RX="RX";
		echo '<br><br>Resultat apres evaluation Cas particuliers #1 (Stock + 1.5 + Transitions + Hard Coat)<b>'. $STOCK_OU_RX. '</b>';
	}

	/*//F1)Calculer le MBS pour le verre Droit
	$FRAMEA_PLUS_FRAMEDBL_DIVISER_PAR_DEUX = $FRAME_A + $FRAME_DBL;
	$Decentration_DROIT  = $FRAMEA_PLUS_FRAMEDBL_DIVISER_PAR_DEUX - $RE_PD;
	$DeuxFoisDecentration_DROIT = $Decentration_DROIT  * 2;
	
	$Decentration_GAUCHE = $FRAMEA_PLUS_FRAMEDBL_DIVISER_PAR_DEUX - $LE_PD;
	$DeuxFoisDecentration_GAUCHE = $Decentration_GAUCHE  * 2;
	
	$MBS_DROIT = $FRAME_ED ;
	
	//F2)Calculer le MBS pour le verre Gauche
	*/
	
	switch($STOCK_OU_RX){
		case 'RX':  	$ProdName  = "  product_name NOT LIKE '%Stock%' AND product_name like '%single%' AND product_name NOT LIKE '%action%' 
		AND product_name not like '%HD Single%' AND product_name not like '%420%'";  	break;	
		case 'STOCK':  	$ProdName  = "  product_name like '%Stock%' AND product_name not like '%impact%' AND product_name not like '%420%'";  		break;		
	}//END SWITCH
	
	
	$ProdTable = "ifc_ca_exclusive";
	
break;
	
	
	
	
		
case 'FORFAIT SIMPLE VISION AR+ETC': //STC / HKO / GKB
case 'ETC SV PACKAGE': //Halifax
case 'STOCK SIMPLE VISION AR+ETC': //STC / HKO / GKB
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock. It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}
	
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND product_name not like '%420%' AND product_name not like '%-6.00 to 2.75%' AND coating IN ('AR Backside','AR+ETC','Dream AR','ITO AR')"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Dream AR';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	




case 'FT28 CA': //Canlab
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	if ($UV420<>''){
		$ProdName  = "  product_name like '%FT28%' and collection like '%KNR%' AND product_name like '%420%'";  
	}else{
		$ProdName  = "  product_name like '%FT28%' and collection like '%KNR%' AND product_name not like '%420%'"; 
	}

	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


	

case 'SIMPLE VISION SURFACE CA': 
case 'ASPHERIC SINGLE VISION CA': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	if ($ARMOUR420=='armour 420'){
		$ProdName  = "   product_name like '%Single Vision%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND collection like '%KNR%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name like '%420%'"; 	
	}else{
		$ProdName  = " product_name like '%Single Vision%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND collection like '%KNR%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name not like '%420%'"; 	
	}
	
	//UV420
	if ($UV420<>''){
		$ProdName  = " product_name like '%Single Vision%' AND collection like '%Entrepot Sky%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name like '%420%'";  
	}//END IF
		
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;
	
	
	
case 'PROMO PRECISION+ POL BROWN AR BACK': 
case 'PROMO PRECISION+ POL BRUN AR BACK':

$Design="Exterieur";

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;
	
	
//Ifc.ca
case 'INTERNET OFFICE AR+ETC '://GKB
case 'INTERNET OFFICE AR+ETC'://GKB
case 'OFFICE INTERNET ETC'://Halifax

	$ProdName  = "  product_name like '%Internet Office%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	//$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' Regression: 0.75 ';
	if (($EYE == 'Both')&& ($RE_HEIGHT <> '') && ($LE_HEIGHT <> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'INTERNET OFFICE IBLU'://GKB
case 'INTERNET OFFICE IBLU '://GKB
case 'OFFICE INTERNET ETC'://Halifax
	$ProdName  = "  product_name like '%Internet Office%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "iBlu"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	//$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' Regression: 0.75 ';
	if (($EYE == 'Both') && ($RE_HEIGHT <> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'INTERNET ANTI-FATIGUE AR+ETC 0.40'://GKB
case 'ANTI-FATIGUE INTERNET ETC 0.40'://GKB HALIFAX

	$RE_ADD = '0.50';
	$LE_ADD = '0.50';
	$ProdName  = "  product_name like '%Internet Anti-Fatigue%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' Regression: 0.40 ';
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'FORFAIT PLANO AR+ETC'://GKB
case 'ANTI-FATIGUE INTERNET ETC 0.60'://GKB HALIFAX
case 'INTERNET ANTI-FATIGUE AR+ETC 0.60':
    $RE_ADD = '0.75';
	$LE_ADD = '0.75';
	$ProdName  = "  product_name like '%Internet Anti-Fatigue%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' Regression: 0.60 ';
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'FORFAIT SV AR+ETC TRANSITIONS GRIS': // GKB / HKO
case 'ETC GREY TRANS SV PACKAGE': //Halifax
case 'STOCK SV AR+ETC TRANSITIONS GRIS': // GKB / HKO
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock. It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Grey'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'DREAM AR';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;




//READER DE HKO, SANS ARMOUR420
case 'READER (1.2M) HC': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'READER (1.2M) AR+ETC': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'READER (1.2M) HD AR': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;

//READER DE HKO, AVEC ARMOUR420
case 'READER (1.2M) UV420 HC': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'READER (1.2M) UV420 AR+ETC': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'READER (1.2M) UV420 HD AR': //HKO
	$ProdName  				= "  product_name like '%Reader (1.2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;






case 'FORFAIT PLANO AR+ETC': //STC
case 'PLANO CLEAR ETC SV PACKAGE': //Halifax
case 'SV STOCK ETC PACKAGE ': //Halifax
case 'SV STOCK ETC PACKAGE': //Halifax
	$ProdName  = "    product_name  like '%stock%' and product_name not like '%-6.00 to 2.75%' AND coating IN ('SPC') AND product_name not like '%420%'"; 
	
	if ($IIMPACT=='iimpact'){
		$ProdName  .= "  AND product_name LIKE '%Impactfree%'"; 
	}else{
		$ProdName  .= "  AND product_name NOT LIKE  '%Impactfree%'"; 
	}

	
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	if ($TINT_COLOR <> ''){
		$ProdName  .= "  AND product_name like '%$TINT_COLOR%'"; 
	}else{
		$ProdName  .= "  AND product_name NOT like '%tinted%'"; 	
	}
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'SPC';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




	
//DÉBUT PROMOTION K-ONE (Kubik one) Activé le 20 août 2019
		
//VERRES SV	
//STOCK		
case 'KONE SV STOCK AR  1.5':
case 'KONE SV STOCK AR  1.6':
case 'KONE SV STOCK AR':
case 'KONE STOCK SV AR':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	
		

case 'KONE SV STOCK AR PHOTOSUN GREY 1.5':
case 'KONE SV STOCK AR PHOTOSUN GREY  1.6':
case 'KONE SV STOCK AR PHOTOSUN GREY':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'KONE SV STOCK AR TINT GREY 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%K-ONE%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;

case 'KONE SV STOCK AR TINT BROWN 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%K-ONE%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;
			
		
//RX		
case 'KONE SV RX AR  1.5':
case 'KONE SV RX AR  1.6':
case 'KONE SV RX AR':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;			


//NEW
case 'KONE SV RX AR TINT 85% GREY':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;	



case 'KONE SV RX AR TINT 85% BROWN':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;	



case 'ORBIT SV RX AR TINT 85% BROWN':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;	



case 'ORBIT SV RX AR TINT 85% GREY':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;	
		
	
case 'KONE SV RX AR PHOTOSUN GREY  1.5':  
case 'KONE SV RX AR PHOTOSUN GREY  1.6':	
case 'KONE SV RX AR PHOTOSUN GREY':	
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
		
		
case 'KONE SV RX AR PHOTOSUN BROWN  1.5':  
case 'KONE SV RX AR PHOTOSUN BROWN  1.6':
case 'KONE SV RX AR PHOTOSUN BROWN':	
case 'KONE SV RX AR  PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
	
case 'KONE SV RX AR  PHOTOSUN GREY':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;		

//PROGRESSIFS
case 'KONE PROG AR CORR 11  1.5':
case 'KONE PROG AR CORR 11  1.6':
case 'KONE PROG AR CORR 11':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;





case 'KONE PROG AR CORR 11 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'KONE PROG AR CORR 13 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;



case 'FORFAIT SV AR+ETC TRANSITIONS BRUN': // GKB / HKO
case 'ETC BROWN TRANS SV PACKAGE': // Halifax
case 'STOCK SV AR+ETC TRANSITIONS BRUN': // GKB / HKO
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock. It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'DREAM AR';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'KONE PROG AR CORR 15 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;
	

case 'FORFAIT PLANO AR+ETC': //STC
case 'PLANO CLEAR ETC SV PACKAGE': //Halifax
case 'STOCK SIMPLE VISION CLEAR ETC SV PACKAGE': //Halifax
case 'STOCK SV PLANO AR+ETC': //STC
case 'STOCK SIMPLE VISION PLANO AR+ETC':
$ProdName  = "  product_name like '%plano%' AND product_name not like '%stock%' AND coating IN ('AR Backside','AR+ETC','Dream AR','ITO AR')"; 

	
	//Évaluation si la commande contient vraiment un plano
	if ($EYE == 'Both'){
		if (($RE_SPHERE>0) || ($RE_SPHERE<0)){
					$ErrorDetail   .=" Vous demandez un forfait plano mais votre prescription n\'est pas plano ?You are requesting a plano but the Rx is not plano ?";
					$InsererDansBD  = false;
		}
		if (($LE_SPHERE>0) || ($LE_SPHERE<0)){
			$ErrorDetail   .=" Vous demandez un forfait plano mais votre prescription n\'est pas plano ?You are requesting a plano but the Rx is not plano ?";
			$InsererDansBD  = false;
		}
	}//End IF
		
	if($EYE == 'R.E.'){
				if (($RE_SPHERE>0) || ($RE_SPHERE<0)){
					$ErrorDetail   .=" Vous demandez un forfait plano mais votre prescription n\'est pas plano ?You are requesting a plano but the Rx is not plano ?";
					$InsererDansBD  = false;
				}
	}//End IF
	
	if($EYE == 'L.E.'){
					
		if (($LE_SPHERE>0) || ($LE_SPHERE<0)){
			$ErrorDetail   .=" Vous demandez un forfait plano mais votre prescription n\'est pas plano ?You are requesting a plano but the Rx is not plano ?";
			$InsererDansBD  = false;
		}
	}//End IF
	//Fin de l'évaluation: Si la commande contient vraiment un plano.
	
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock. It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}
	
	if ($TINT_COLOR <> ''){
		$ProdName  .= "  AND product_name like '%$TINT_COLOR%'"; 
	}else{
		$ProdName  .= "  AND product_name NOT like '%tinted%'"; 	
	}
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



	
		
case 'KONE PROG AR CORR 11 TINT 85% GREY':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;


case 'KONE PROG AR CORR 13 TINT 85% GREY':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;

case 'KONE PROG AR CORR 15 TINT 85% GREY':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;	
		
		
case 'KONE PROG AR CORR 13  1.5':
case 'KONE PROG AR CORR 13  1.6':	
case 'KONE PROG AR CORR 13':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%tint%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
break;
		
case 'KONE PROG AR CORR 15  1.5':
case 'KONE PROG AR CORR 15  1.6':
case 'KONE PROG AR CORR 15':	
$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%tint%' AND product_name not like '%rx%' AND product_name like  '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
break;
	
		
		
		
		
		
	
case 'KONE PROG AR CORR 11 PHOTOSUN GREY   1.5':	
case 'KONE PROG AR CORR 11 PHOTOSUN GREY   1.6':
case 'KONE PROG AR CORR 11 PHOTOSUN GREY': 
case 'KONE PROG AR CORR 11 PHOTOSUN GREY ': 
	$ProdName  = "  product_name like '%K-ONE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
	
break;
		
		
		

case 'KONE PROG AR CORR 13 PHOTOSUN GREY   1.5':
case 'KONE PROG AR CORR 13 PHOTOSUN GREY   1.6':
case 'KONE PROG AR CORR 13 PHOTOSUN GREY': 
case 'KONE PROG AR CORR 13 PHOTOSUN GREY ': 
	$ProdName  = "  product_name like '%K-ONE%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
			
break;	
		
		
		

case 'KONE PROG AR CORR 15 PHOTOSUN GREY   1.5':		
case 'KONE PROG AR CORR 15 PHOTOSUN GREY   1.6	':	
case 'KONE PROG AR CORR 15 PHOTOSUN GREY':
case 'KONE PROG AR CORR 15 PHOTOSUN GREY ':		
	$ProdName  = "  product_name like '%K-ONE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%15mm%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	
				
break;	
		
		
		
		
		
		
case 'KONE PROG AR CORR 11 PHOTOSUN BROWN   1.5':
case 'KONE PROG AR CORR 11 PHOTOSUN BROWN   1.6':
case 'KONE PROG AR CORR 11 PHOTOSUN BROWN':
case 'KONE PROG AR CORR 11 PHOTOSUN BROWN ':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
		
break;



case 'ORBIT PROG AR CORR 11 PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;


case 'ORBIT PROG AR CORR 13 PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%'  "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;


case 'ORBIT PROG AR CORR 15 PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%'  "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;




case 'ORBIT PROG AR CORR 11 PHOTOSUN GREY':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;


case 'ORBIT PROG AR CORR 13 PHOTOSUN GREY':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%'  "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;


case 'ORBIT PROG AR CORR 15 PHOTOSUN GREY':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%'  "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;


	
case 'KONE PROG AR CORR 13 PHOTOSUN BROWN   1.5':
case 'KONE PROG AR CORR 13 PHOTOSUN BROWN   1.6':	
case 'KONE PROG AR CORR 13 PHOTOSUN BROWN':	
case 'KONE PROG AR CORR 13 PHOTOSUN BROWN ':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name like '%13mm%'  AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';

break;
		
		
		
case 'KONE PROG AR CORR 15 PHOTOSUN BROWN   1.5':
case 'KONE PROG AR CORR 15 PHOTOSUN BROWN   1.6':
case 'KONE PROG AR CORR 15 PHOTOSUN BROWN':
case 'KONE PROG AR CORR 15 PHOTOSUN BROWN ':
	$ProdName  = "  product_name like '%K-ONE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';

break;		
//FIN PROGRESSIFS	

//FIN PROMO K-ONE	


case 'PROMO PRECISION+ POL GREY HC': 
case 'PROMO PRECISION+ POL GRIS HC':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


case 'SV SURFACE TEINTE BRUN 85% HC':
case 'SV SURFACE TINTED BROWN 85% HC':
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%tinted brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING ="Hard Coat";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';		
break;




//Precision+ dans la promo SOLAIRE

case 'PROMO PRECISION+ 360 TEINTE GRIS AR BACK': 
case 'PROMO PRECISION+ 360 TINTED GREY AR BACK':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

	$ProdName  = "  product_name like '%promo%' AND product_name  like '%360%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING="Super AR Backside";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '7':  $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;  
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  	 		
		}
}
	if ($EYE == 'Both'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
		}elseif($EYE == 'R.E.'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
		}elseif($EYE == 'L.E.'){
			$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
		}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';		
break;





case 'PROMO PRECISION+ HC': 
	if ($TINT_COLOR==''){
		//La teinte est obligatoire, on doit stopper la commande. 
		$InsererDansBD  = false;
		$ErrorDetail.= "<br>Il n est pas permis  de commander ce produit PROMO sans teinte.. Svp ajouter la teinte et re-exporter la commande.<br> 
		It is not allowed to order this PROMO product without a tint. Please add the tint and re-export the order.";
	}

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

case 'PROMO PRECISION+ POLAR BROWN HC': 
//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

case 'PROMO PRECISION+ POLAR BROWN SUPER AR': 
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

case 'PROMO PRECISION+ POLAR GREY HC': 
//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

case 'PROMO PRECISION+ POLAR GREY SUPER AR': 
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;



case 'PRECISION+ ANTI FATIGUE':
case 'PRECISION+ ANTI FATIGUE (0.50/0.75)':

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	
	
/*if ($ORDER_PRODUCT_COATING=='Super AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" PRECISION+ ANTI FATIGUE (0.50/0.75) est uniquement disponibles avec les traitements suivants:Bluecut, AR-ES. <br> The product PRECISION+ ANTI FATIGUE (0.50/0.75) is only available with the following coating: BluCut, AR-ES.";
		$InsererDansBD  = false;
	}	*/
	
	
	if ($UV420<>''){
	$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%420%' "; 
	}else{
	$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%420%' "; 
	}
	
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;




case 'PROMO PRECISION+ 360 POL BRUN AR BACK':
case 'PROMO PRECISION+ 360 POL BROWN AR BACK':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name  like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;









case 'PROMO PRECISION+ SUPER AR': 
	if ($TINT_COLOR==''){
		//La teinte est obligatoire, on doit stopper la commande. 
		$InsererDansBD  = false;
		$ErrorDetail.= "<br>Il n est pas permis  de commander ce produit PROMO sans teinte.. Svp ajouter la teinte et re-exporter la commande.<br> 
		It is not allowed to order this PROMO product without a tint. Please add the tint and re-export the order.";
	}

switch($DESIGN){

		case 'lecture': case 'premier porteur': case 'quotidien':case 'ultra court':      
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Il y a uniquement  4 designs disponibles pour ce produit: (Tout Usage, Intérieur, Extérieur ou Conduite).<br>
		There are only 4 designs available with this product:All Purpose, Indoor, Outdoor, Drive).';
		break;
		case 'tout usage':	break;	
		case 'interieur':   break;		
		case 'exterieur':   break;
		case 'extérieur':   break;
		case 'conduite':  echo 'Passe';  break;
		
	}//End Switch
	
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%'  AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;





//DÉBUT  FORFAIT DE MARQUE Créé le 7 Mai 2020
		
//VERRES SV	
//STOCK		
case 'FORFAIT SV STOCK AR':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'AFFO PROG AR CORR 11':	
if ($ORDER_PRODUCT_POLAR=='None'){
	$ORDER_PRODUCT_COATING = 'ITO AR';	
}else{
	$ORDER_PRODUCT_COATING = 'AR Backside';		
}//END IF
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	$ProdName  = "  product_name like '%AFFORDABLE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;
	
		


case 'ORBIT PROG AR CORR 11':	
if ($ORDER_PRODUCT_POLAR=='None'){
	$ORDER_PRODUCT_COATING = 'ITO AR';	
}else{
	$ORDER_PRODUCT_COATING = 'AR Backside';		
}//END IF
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;



case 'ORBIT PROG AR CORR 13':	
if ($ORDER_PRODUCT_POLAR=='None'){
	$ORDER_PRODUCT_COATING = 'ITO AR';	
}else{
	$ORDER_PRODUCT_COATING = 'AR Backside';		
}//END IF
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;



case 'ORBIT PROG AR CORR 15':	
if ($ORDER_PRODUCT_POLAR=='None'){
	$ORDER_PRODUCT_COATING = 'ITO AR';	
}else{
	$ORDER_PRODUCT_COATING = 'AR Backside';		
}//END IF
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;




case 'PRECISION+ 360 E':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision+ 360%' AND collection IN ('HBC STOCK') AND product_name  like '%420%'  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	}else{
		$ProdName  = "  product_name like '%Precision+ 360%' AND collection IN ('HBC STOCK') AND product_name not like '%420%'  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	}
	

	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'SIMPLE VISION SURFACE UV420':
case 'ASPHERIC SINGLE VISION UV420'://Halifax

	if ($ORDER_PRODUCT_COATING=='Hard Coat'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" SIMPLE VISION SURFACE UV420  n\'est pas offert avec le traitement Hard Coat.<br> ASPHERIC SINGLE VISION UV420 is not available with Hard Coat. ";
		$InsererDansBD  = false;
	}

	$ProdName  				= " product_name like '%Single Vision RX%' and product_name like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case '2ND PRECISION+ 360 E':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch


	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision+ 360%' AND product_name like '%420%' AND collection IN ('HBC STOCK')  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	}else{
		$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%420%' AND collection IN ('HBC STOCK')  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	}

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	





case '2ND PRECISION+ 360':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	
	//Paramètres propre à ce produit seulement
	if ($UV400<>''){
		$ProdName  = "  product_name like '%Precision+ 360%' AND product_name like '%420%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	}else{
		$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%420%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	}
	
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//3
case 'PRECISION+':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

if ($ORDER_PRODUCT_COATING=='AR+ETC'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Precision+ est uniquement disponibles avec les traitements suivants:Hard Coat, Super AR, Low Reflexion, BluCut, AR-ES. <br> The product Precision+ is only available with the following coating: Super AR, Low Reflexion, BluCut, AR-ES.";
	$InsererDansBD  = false;
}	

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%'  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' AND product_name not like '%420%' "; 
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;





case 'PROMO PRECISION+ TEINTE BRUN AR BACK': 
case 'PROMO PRECISION+ TINTED BROWN AR BACK':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

	$ProdName  = "  product_name like '%promo%' AND product_name like '%precision%' AND product_name not like '%360%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING="Super AR Backside";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '7': $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;  
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  	 		
		}
}
	if ($EYE == 'Both'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
		}elseif($EYE == 'R.E.'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
		}elseif($EYE == 'L.E.'){
			$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
		}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';		
break;




case 'PRECISION+ E':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement

	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision+%' AND collection IN ('HBC STOCK')  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' AND product_name  like '%420%' "; 
	}else{
		$ProdName  = "  product_name like '%Precision+%' AND collection IN ('HBC STOCK')  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' AND product_name not like '%420%' "; 
	}
	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ 360':


switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%420%'  AND product_name not like '%promo%'  AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'IRELAX': //Swiss //HALIFAX
case 'IRELAX (0.50/0.75)':
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch



$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


	if ($ORDER_PRODUCT_COATING=='HD AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" iRelax (Swiss) avec HD AR(Central Lab) = Combinaison impossible. iRelax(Swiss) with HD AR(Central Lab) = Impossible combination.";
		$InsererDansBD  = false;
	}
	
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%irelax%' AND product_name like '%armour 420%' $ConditionHighImpact"; 	
}else{
	$ProdName  = "  product_name like '%irelax%' AND product_name not like '%armour 420%' $ConditionHighImpact"; 
}
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'FORFAIT SV STOCK AR PHOTOSUN GREY':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'FORFAIT SV STOCK AR TINT GREY 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;

case 'FORFAIT SV STOCK AR TINT BROWN 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = " product_name like '%forfait de marque%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' <br>
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;
			
		
//RX		
case 'FORFAIT SV RX AR':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;			


//NEW
case 'FORFAIT SV RX AR TINT 85% GREY':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;	

case 'FORFAIT SV RX AR TINT 85% BROWN':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;	
		
	
	
case 'FORFAIT SV RX AR PHOTOSUN GREY':	
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
		
		
case 'FORFAIT SV RX AR PHOTOSUN BROWN':	
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
	
		

//PROGRESSIFS
case 'FORFAIT PROG AR CORR 11':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;



case 'FORFAIT PROG AR CORR 11 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'FORFAIT PROG AR CORR 13 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'FORFAIT PROG AR CORR 15 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;
		
		
case 'FORFAIT PROG AR CORR 11 TINT 85% GREY':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;


case 'FORFAIT PROG AR CORR 13 TINT 85% GREY':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;

case 'FORFAIT PROG AR CORR 15 TINT 85% GREY':		
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;	
		
		
	
case 'FORFAIT PROG AR CORR 13':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%tint%' AND product_name not like '%rx%'  <br>
	AND product_name like  '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
break;
		

case 'FORFAIT PROG AR CORR 15':	
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%tint%' AND product_name not like '%rx%' <br>
	AND product_name like  '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
break;
		
		
		
case 'FORFAIT PROG AR CORR 11 PHOTOSUN GREY': 
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
	
break;
				
		
case 'FORFAIT PROG AR CORR 13 PHOTOSUN GREY': 
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
			
break;	
		
				

case 'FORFAIT PROG AR CORR 15 PHOTOSUN GREY':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
				
break;	
		
		
		
	
case 'FORFAIT PROG AR CORR 11 PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name like '%11mm%' <br>
	AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
		
break;
	

case 'FORFAIT PROG AR CORR 13 PHOTOSUN BROWN':	
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name like '%13mm%'  AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';

break;
		
		
		

case 'FORFAIT PROG AR CORR 15 PHOTOSUN BROWN':
	$ProdName  = "  product_name like '%forfait de marque%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name like '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';

break;		
//FIN PROGRESSIFS	

//FIN Forfait de Marque

case 'FORFAIT SV AR+ETC TRANSITIONS BRUN': // GKB / HKO
case 'ETC BROWN TRANS SV PACKAGE': // Halifax
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock. It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'ORBIT SV STOCK AR':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'ORBIT SV STOCK AR PHOTOSUN GREY':
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 
	$ORDER_PRODUCT_PHOTO = 'Grey';	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	





		
//DÉBUT PROMO ENHANCE, CRÉÉ EN FÉVRIER 2020
		
//VERRES SV	
//STOCK		
case 'ENHANCE SV STOCK AR':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	
		

case 'ENHANCE SV STOCK AR ACCLI GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%acclimate%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'ENHANCE SV STOCK AR TRANSITIONS GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%transitions%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'ENHANCE SV STOCK AR TINT GREY 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;

case 'ENHANCE SV STOCK AR TINT BROWN 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;
			
		
//RX		
case 'ENHANCE SV RX AR':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;			


case 'ENHANCE SV RX AR TINT 85% GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;	

case 'ENHANCE SV RX AR TINT 85% BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;	
		
	
case 'ENHANCE SV RX AR ACCLI GREY':	
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%acclimate%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
		
		

case 'ENHANCE SV RX AR ACCLI BROWN':	
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%acclimate%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	




case 'ENHANCE SV RX AR TRANSITIONS BROWN':	
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name  like '%transitions%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	

case 'ENHANCE SV RX AR TRANSITIONS GREY':	
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name  like '%transitions%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
	
		

//PROGRESSIFS
case 'ENHANCE PROG AR CORR 11':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;

case 'ENHANCE PROG AR CORR 13':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;

case 'ENHANCE PROG AR CORR 15':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;



case 'ENHANCE PROG AR CORR 11 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' 
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;




case 'ORBIT PROG AR CORR 11 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' 
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;



case 'ORBIT PROG AR CORR 13 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' 
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;



case 'ORBIT PROG AR CORR 15 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' 
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;





case 'ORBIT PROG AR CORR 11 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' 
	AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;



case 'ORBIT PROG AR CORR 13 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' 
	AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;



case 'ORBIT PROG AR CORR 15 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' 
	AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;





case 'PRECISION AI':   //GKB
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if ($UV420==''){
		$ProdName  = "  product_name like '%Precision AI%' and product_name not like '%420%' AND product_name not like '%2ieme%'"; 
	}else{
		$ProdName  = "  product_name like '%Precision AI%' AND product_name like '%420%' AND product_name not like '%2ieme%'";
	}
	

if (($ORDER_PRODUCT_INDEX=='1.59') || ($ORDER_PRODUCT_INDEX=='1.74')){
	$PolarLowercase = strtolower($ORDER_PRODUCT_POLAR);
		if ($PolarLowercase<>'none'){
		//Un polarisé a été demandé,  afficher le message d'erreur qui correspond car non disponible	
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le  produit <b>Precision AI</b> <u> est indisponible</u> en polarisé dans les indices 1.59 et 1.74.<br>The product <b>Precision AI</b> <u> is not available</u> 
		in Polarized in the following index:1.59 and 1.74.';
		}
}//End IF



if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7-9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br><br> The corridor  is mandatory for this product. 
	Please add the corridor (7-9 or 11mm) and re-export the order.';
}//Fin si aucun corridor n'a été fournis	

	

if ($CORRIDOR <> ''){
	switch($CORRIDOR){
		case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
		case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
		case '11': $ProdName  .= " AND corridor = 11";  $SauterValidationFH = "yes"; break; 
	}
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

break;



case 'POLA SV RX AR':		
	$ProdName  = "  product_name like '%POLAROID%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	





case '2ND PRECISION AI':   //GKB
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch


if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision AI%' AND product_name not like '%420%' AND product_name  like '%2ieme%'"; 
	}else{
		$ProdName  = "  product_name like '%Precision AI%' AND product_name   like '%420%'   AND product_name like '%2ieme%'"; 
	}	

if (($ORDER_PRODUCT_INDEX=='1.59') || ($ORDER_PRODUCT_INDEX=='1.74')){
	$PolarLowercase = strtolower($ORDER_PRODUCT_POLAR);
		if ($PolarLowercase<>'none'){
		//Un polarisé a été demandé,  afficher le message d'erreur qui correspond car non disponible	
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le  produit <b>Precision AI</b> <u> est indisponible</u> en polarisé dans les indices 1.59 et 1.74.<br>The product <b>Precision AI</b> <u> is not available</u> 
		in Polarized in the following index:1.59 and 1.74.';
		}
}//End IF



if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7-9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br><br> The corridor  is mandatory for this product. 
	Please add the corridor (7-9 or 11mm) and re-export the order.';
}//Fin si aucun corridor n'a été fournis	

	

if ($CORRIDOR <> ''){
	switch($CORRIDOR){
		case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
		case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
		case '11': $ProdName  .= " AND corridor = 11";  $SauterValidationFH = "yes"; break; 
	}
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

break;









case 'ENHANCE PROG AR CORR 13 TRANSITION BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name NOT like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'ENHANCE PROG AR CORR 13 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'ENHANCE PROG AR CORR 15 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;
		
		
case 'ENHANCE PROG AR CORR 11 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;


case 'ENHANCE PROG AR CORR 13 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;

case 'ENHANCE PROG AR CORR 15 TINT 85% GREY':		
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;	
		
		


case 'ENHANCE PROG AR CORR 11 ACCLI GREY': 
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'ENHANCE PROG AR CORR 13 ACCLI GREY': 
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;
		
	
case 'ENHANCE PROG AR CORR 15 TRANSITION BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' and product_name like '%transition%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;

	
case 'ENHANCE PROG AR CORR 15 ACCLI GREY': 
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;		
		


case 'ENHANCE PROG AR CORR 11 ACCLI BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'ENHANCE PROG AR CORR 13 ACCLI BROWN':
case 'ENHANCE PROG AR CORR 13 ACCLI BROWN  ':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;
		
		
case 'ENHANCE PROG AR CORR 15 ACCLI BROWN':
case 'ENHANCE PROG AR CORR 13 ACCLI BROWN ':
case 'ENHANCE PROG AR CORR 15 ACCLI BROWN  ':

	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;		
		
		
case 'ENHANCE PROG AR CORR 11 TRANSITIONS BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'ENHANCE PROG AR CORR 13 TRANSITIONS BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'ENHANCE PROG AR CORR 15 TRANSITIONS BROWN':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'ENHANCE PROG AR CORR 11 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'ENHANCE PROG AR CORR 13 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'ENHANCE PROG AR CORR 15 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%ENHANCE%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;
//FIN PROGRESSIFS	


//FIN PROMO ENHANCE

























































//DÉBUT PROMO GEN-Y, CRÉÉ EN FÉVRIER 2020
		
//VERRES SV	
//STOCK		
case 'GEN-Y SV STOCK AR':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%RX%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	
		

case 'GEN-Y SV STOCK AR ACCLI GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%acclimate%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'GEN-Y SV STOCK AR TRANSITIONS GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%transitions%' AND product_name not like '%RX%' AND product_name not like '%PROG%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'GEN-Y SV STOCK AR TINT GREY 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;

case 'GEN-Y SV STOCK AR TINT BROWN 85%':
//AJOUTER LA TEINTE AUSSI + TESTER
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name  like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%'  AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;
			
		
//RX		
case 'GEN-Y SV RX AR':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;			


case 'GEN-Y SV RX AR TINT 85% GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;	

case 'GEN-Y SV RX AR TINT 85% BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;	
		
	
case 'GEN-Y SV RX AR ACCLI GREY':	
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%acclimate%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
		
		

case 'GEN-Y SV RX AR ACCLI BROWN':	
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name like '%acclimate%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	




case 'GEN-Y SV RX AR TRANSITIONS BROWN':	
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name  like '%transitions%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	

case 'GEN-Y SV RX AR TRANSITIONS GREY':	
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name  like '%transitions%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO='Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}	
break;	
	
		

//PROGRESSIFS
case 'GEN-Y PROG AR CORR 11':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%11mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;

case 'GEN-Y PROG AR CORR 13':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%13mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;

case 'GEN-Y PROG AR CORR 15':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%' AND product_name not like '%tint%'  AND product_name like  '%15mm%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';	
break;

case 'GEN-Y PROG AR CORR 11 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' 
	AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
		
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'GEN-Y PROG AR CORR 13 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;

case 'GEN-Y PROG AR CORR 15 TINT 85% BROWN':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Brown%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';	
break;
		
		
case 'GEN-Y PROG AR CORR 11 TINT 85% GREY':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%11mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;


case 'GEN-Y PROG AR CORR 13 TINT 85% GREY':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%13mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';

//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;

case 'GEN-Y PROG AR CORR 15 TINT 85% GREY':		
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name not like '%stock%' AND product_name not like '%rx%'  AND product_name like  '%15mm%' AND product_name like '%tint%' AND product_name like '%Grey%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';	
break;	
		
		


case 'GEN-Y PROG AR CORR 11 ACCLI GREY': 
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'GEN-Y PROG AR CORR 13 ACCLI GREY': 
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;
		
		
case 'GEN-Y PROG AR CORR 15 ACCLI GREY': 
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';	
break;		
		


case 'GEN-Y PROG AR CORR 11 ACCLI BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'GEN-Y PROG AR CORR 13 ACCLI BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;
		
		
case 'GEN-Y PROG AR CORR 15 ACCLI BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';	
break;		
		
		
case 'GEN-Y PROG AR CORR 11 TRANSITION BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'GEN-Y PROG AR CORR 13 TRANSITION BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'GEN-Y PROG AR CORR 15 TRANSITION BROWN':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';		
break;

case 'GEN-Y PROG AR CORR 11 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%11mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'GEN-Y PROG AR CORR 13 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%13mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;

case 'GEN-Y PROG AR CORR 15 TRANSITIONS GREY':
	$ProdName  = "  product_name like '%GEN-Y%' AND product_name like '%15mm%' AND product_name not like '%stock%' AND product_name not like '%rx%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';		
break;
//FIN PROGRESSIFS	


//FIN PROMO GEN-Y



//PROMO RENTRÉE
case 'ENFANT ANTI FATIGUE 0.40 AR+ETC'://GKB
case 'ENFANT ANTI FATIGUE 0.40  AR+ETC':
case 'ETC KIDS ANTI-FATIGUE 0.40'://Halifax

if ($RE_HEIGHT==''){
	$ErrorDetail   .=" Your heights are missing. Vos hauteurs sont manquantes.";
	$InsererDansBD  = false;
	
}

if ($LE_HEIGHT==''){
	$ErrorDetail   .=" Your heights are missing. Vos hauteurs sont manquantes.";
	$InsererDansBD  = false;
	
}

	$ProdName  = "  product_name like '%Promo Etudiant%' and product_name like '%(0.40)%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	//$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS)  . ' Regression: 0.40 ';
	$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS);
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'OFFICE UV420 (2M)': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name like '%420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;



//PRECISION 1ERE PAIRE
case '[GOOD] PRECISION E'://DOIT être dans une collection redirigé vers Essilor

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	if ($Design<>''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Aucun design n\'est offert pour ce produit. Svp retirer le design et ré-exporter la commande.<br> 
		No design are available for this product. Please remove the design and re-export the order.';
	}

	$ProdName  = "  product_name like '%Precision%' and collection like '%stock%' AND product_name not like '%precision AI%' AND collection IN ('Entrepot Sky','Entrepot Promo')  AND product_name not like '%promo%'  and product_name not like  '%Precision+%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){ 
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
	}//END SWITCH	

break;




case '[GOOD] 2ND PRECISION E':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

 	$ProdName  = "  product_name like '%Precision%' AND collection IN ('HBC STOCK')  AND product_name not like '%promo%'  AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;





case 'PRECISION+ ANTI FATIGUE':
case 'PRECISION+ ANTI FATIGUE (0.50/0.75)':

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	
	
	
	if ($UV420<>''){
	$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%420%' "; 
	}else{
	$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%420%' "; 
	}
	
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;


//PROMO RENTRÉE
case 'ENFANT ANTI FATIGUE 0.60  AR+ETC'://GKB
case 'ENFANT ANTI FATIGUE 0.60 AR+ETC'://GKB
case 'ETC KIDS ANTI-FATIGUE 0.60'://Halifax
    $RE_ADD = '0.75';
	$LE_ADD = '0.75';
	$ProdName  = "  product_name like '%Promo Etudiant%' and product_name like '%(0.60)%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS);
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'INTERNET ANTI-FATIGUE 0.60'://GKB
case 'ANTI-FATIGUE INTERNET 0.60'://GKB HALIFAX

	$ProdName  = "  product_name like '%Internet Anti-Fatigue%'"; 
	$ProdTable = "ifc_ca_exclusive";  
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS)  . ' Regression: 0.60 ';
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;





//PRECISION 1ERE PAIRE
case '[GOOD] PRECISION':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if ($ORDER_PRODUCT_COATING=='Hard Coat'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" [Good] Precision  n\'est pas offert avec Hard Coat.<br> [Good] Precision is not available with Hard Coat.";
	$InsererDansBD  = false;
}	

if ($ORDER_PRODUCT_COATING=='Super AR'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" [Good] Precision  n\'est pas offert avec Super AR.<br> [Good] Precision is not available with SUPER AR.";
	$InsererDansBD  = false;
}	


	if ($Design<>''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Aucun design n\'est offert pour ce produit. Svp retirer le design et ré-exporter la commande.<br> 
		No design are available for this product. Please remove the design and re-export the order.';
	}

	
	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision%' and product_name not like '%precision AI%' AND product_name like '%420%'  AND product_name not like '%promo%'  and product_name not like  '%Precision+%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'"; 
	}else{
		$ProdName  = "  product_name like '%Precision%' and product_name not like '%precision AI%' AND product_name not like '%420%'  AND product_name not like '%promo%'  and product_name not like  '%Precision+%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'";  
	}
	
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){ 
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
	}//END SWITCH	

break;




case '2ND PRECISION+ E':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	
	
	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision+%' AND collection IN ('HBC STOCK') AND product_name like '%griff%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name  like '%420%'   ";
	}else{
		$ProdName  = "  product_name like '%Precision+%' AND collection IN ('HBC STOCK')  AND product_name like '%griff%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%' ";
	}
	
	 
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




		
//PARTIE PRECISION
//PRECISION 1ERE PAIRE
case '[GOOD] PRECISION AR+ETC':

	if ($Design<>''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Aucun design n\'est offert pour ce produit. Svp retirer le design et ré-exporter la commande.<br> 
		No design are available for this product. Please remove the design and re-export the order.';
	}

	$ProdName  = "  product_name like '%Precision%' and product_name not like  '%Precision+%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){ 
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
	}//END SWITCH	

break;


case '2ND PRECISION+ ANTI FATIGUE':
case '2ND PRECISION+ ANTI FATIGUE (0.50/0.75)':


	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	
	if ($UV420<>''){
		$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name  like '%420%' ";
	}else{
		$ProdName  = "  product_name like '%anti fatigue%'  AND product_name not like '%promo%'  AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%420%' ";
	}
		
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;



case '[GOOD] PRECISION PHOTO BROWN AR+ETC':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%neochrome%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '7') || ($CORRIDOR == '9')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '[GOOD] PRECISION PHOTO GREY AR+ETC':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%neochrome%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '5')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '[GOOD] PRECISION TRANS BROWN AR+ETC':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name NOT LIKE '%photo%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;

case '[GOOD] PRECISION TRANS GREY AR+ETC':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name  LIKE '%trans%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7, 9 ou 11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '[GOOD] 2ND PRECISION':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

 	

	if ($UV420<>''){
		$ProdName  = "  product_name like '%Precision%' AND product_name like '%420%'  and product_name not like  '%Precision+%'  AND product_name not like '%promo%'  AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	}else{
		$ProdName  = "  product_name like '%Precision%' AND product_name not like '%420%'  and product_name not like  '%Precision+%'  AND product_name not like '%promo%'  AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	}
		
	if ($ORDER_PRODUCT_COATING=='Hard Coat'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" [Good] Precision  n\'est pas offert avec Hard Coat.<br> [Good] Precision is not available with Hard Coat.";
		$InsererDansBD  = false;
	}	

	if ($ORDER_PRODUCT_COATING=='Super AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" [Good] Precision  n\'est pas offert avec Super AR.<br> [Good] Precision is not available with SUPER AR.";
		$InsererDansBD  = false;
	}	
	

	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;





case '[GOOD] 2ND PRECISION TRANS BROWN AR+ETC':
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Brown";
	$ORDER_PRODUCT_POLAR="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


case '[GOOD] 2ND PRECISION TRANS GREY AR+ETC':
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Grey";
	$ORDER_PRODUCT_POLAR="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '[GOOD] 2ND PRECISION PHOTO BROWN AR+ETC';
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Brown";
	$ORDER_PRODUCT_POLAR="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;


case '[GOOD] 2ND PRECISION PHOTO GREY AR+ETC':
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="Grey";
	$ORDER_PRODUCT_POLAR="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


case '[GOOD] 2ND PRECISION POLAR BROWN AR BACK':
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="AR Backside";
	$ORDER_PRODUCT_POLAR="Brown";
	$ORDER_PRODUCT_PHOTO="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


case '[GOOD] 2ND PRECISION POLAR GREY AR BACK':
	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="AR Backside";
	$ORDER_PRODUCT_POLAR="Grey";
	$ORDER_PRODUCT_PHOTO="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

case '[GOOD] 2ND PRECISION AR+ETC':
 	$ProdName  = "  product_name like '%Precision%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="Dream AR";
	$ORDER_PRODUCT_PHOTO="None";
	$ORDER_PRODUCT_POLAR="None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

case '[GOOD] PRECISION POLAR BROWN AR BACKSIDE':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%polar%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="AR Backside";
	$ORDER_PRODUCT_POLAR="Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


case '[GOOD] PRECISION POLAR GREY AR BACKSIDE':
 	$ProdName  = "  product_name like '%Precision%' AND product_name NOT LIKE '%2ieme%' AND product_name not like '%active%' AND product_name NOT LIKE '%pair%' AND product_name like '%polar%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_COATING="AR Backside";
	$ORDER_PRODUCT_POLAR="Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//DÉBUT PARTIE BEST: PRECISION+ 360 ACTIVE 1ere paire, 23 items
//0:  PRECISION+ 360 ACTIVE DRIVEWEAR SUPER AR: FAIT
//1:  PRECISION+ 360 ACTIVE HC: 				FAIT
//2:  PRECISION+ 360 ACTIVE PHOTO BR SUPER AR:	FAIT
//3:  PRECISION+ 360 ACTIVE PHOTO BROWN HC: 	FAIT
//4:  PRECISION+ 360 ACTIVE PHOTO GR SUPER AR: 	FAIT
//5:  PRECISION+ 360 ACTIVE PHOTO GREY HC: 		FAIT
//6:  PRECISION+ 360 ACTIVE POL GREY SUPER AR :	FAIT
//7:  PRECISION+ 360 ACTIVE POLAR BR SUPER AR:	FAIT
//8:  PRECISION+ 360 ACTIVE POLAR BROWN HC : 	FAIT
//9: PRECISION+ 360 ACTIVE POLAR GR SUPER AR: 	FAIT
//10: PRECISION+ 360 ACTIVE POLAR GREY HC: 		FAIT
//11: PRECISION+ 360 ACTIVE SUPER AR: 			FAIT
//12: PRECISION+ 360 ACTIVE TRANS BR SUPER AR: 	FAIT
//13: PRECISION+ 360 ACTIVE TRANS BROWN HC: 	FAIT
//14: PRECISION+ 360 ACTIVE TRANS GR SUPER AR: 	FAIT
//15: PRECISION+ 360 ACTIVE TRANS GREY HC: 		FAIT
//16: PRECISION+ 360 ACTIVE UV420 HC: 			FAIT
//17: PRECISION+ 360 ACTIVE UV420 SUPER AR: 	FAIT
//18: PRECISION+ 360 ACTIVE XTRACT BR SUPER AR: FAIT
//19: PRECISION+ 360 ACTIVE XTRACT GR SUPER AR: FAIT
//20: PRECISION+ 360 ACTIVE XTRACT GREY HC: 	FAIT
//21: PRECISION+ 360 ACTIVE XTRACTIVE BROWN HC:	FAIT

//0
case 'PRECISION+ 360 ACTIVE DRIVEWEAR SUPER AR':
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%drivewear%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;



//1er
case 'PRECISION+ 360 ACTIVE DRIVEWEAR HC':
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%drivewear%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


//2ieme
case 'PRECISION+ 360 ACTIVE HC':
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name not like '%420%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
	
break;




//3ieme 
case 'PRECISION+ 360 ACTIVE PHOTO BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


//4
case 'PRECISION+ 360 ACTIVE PHOTO BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//5
case 'PRECISION+ 360 ACTIVE PHOTO GR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

//6
case 'PRECISION+ 360 ACTIVE PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;


//7
case 'PRECISION+ 360 ACTIVE POL GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




//8
case 'PRECISION+ 360 ACTIVE POLAR BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//9
case 'PRECISION+ 360 ACTIVE POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;


//10
case 'PRECISION+ 360 ACTIVE POLAR GR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;

		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//11
case 'PRECISION+ 360 ACTIVE POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//12
case 'PRECISION+ 360 ACTIVE SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;


//13
case 'PRECISION+ 360 ACTIVE TRANS BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//14
case 'PRECISION+ 360 ACTIVE TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//15
case 'PRECISION+ 360 ACTIVE TRANS GR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name like '%transition%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//16
case 'PRECISION+ 360 ACTIVE TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;

//17
case 'PRECISION+ 360 ACTIVE UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;


//18
case 'PRECISION+ 360 ACTIVE UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (11-13-15) is mandatory for this product. Please add a corridor (11, 13 or 15) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//19
case 'PRECISION+ 360 ACTIVE XTRACT BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//20
case 'PRECISION+ 360 ACTIVE XTRACT GR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//21
case 'PRECISION+ 360 ACTIVE XTRACT GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//22
case 'PRECISION+ 360 ACTIVE XTRACTIVE BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//FIN BEST: PRECISION+ 360 ACTIVE 1ERE PAIRE

//DÉBUT PRECISION+ 360 ACTIVE 2IEME PAIRE:

// 1) 2ND PRECISION+ 360 ACTIVE DRIVEWEAR HC:		FAIT
// 2) 2ND PRECISION+ 360 ACTIVE DRIVEWEAR S-AR:		FAIT
// 3) 2ND PRECISION+ 360 ACTIVE HC:					FAIT
// 4) 2ND PRECISION+ 360 ACTIVE PHOTO BR S-AR:		FAIT
// 5) 2ND PRECISION+ 360 ACTIVE PHOTO BROWN HC: 	FAIT
// 6) 2ND PRECISION+ 360 ACTIVE PHOTO GR S-AR: 		FAIT
// 7) 2ND PRECISION+ 360 ACTIVE PHOTO GREY HC: 		FAIT
// 8) 2ND PRECISION+ 360 ACTIVE POLAR BR S-AR: 		FAIT
// 9) 2ND PRECISION+ 360 ACTIVE POLAR BROWN HC: 	FAIT
// 10)2ND PRECISION+ 360 ACTIVE POLAR GR S-AR: 		FAIT
// 11)2ND PRECISION+ 360 ACTIVE POLAR GREY HC:      FAIT
// 12)2ND PRECISION+ 360 ACTIVE SUPER AR: 			FAIT
// 13)2ND PRECISION+ 360 ACTIVE TRANS BR S-AR: 		FAIT
// 14)2ND PRECISION+ 360 ACTIVE TRANS BROWN HC: 	FAIT
// 15)2ND PRECISION+ 360 ACTIVE TRANS GR S-AR: 		FAIT
// 16)2ND PRECISION+ 360 ACTIVE TRANS GREY HC: 		FAIT
// 17)2ND PRECISION+ 360 ACTIVE UV420 HC:			FAIT
// 18)2ND PRECISION+ 360 ACTIVE UV420 SUPER AR:    	FAIT
// 19)2ND PRECISION+ 360 ACTIVE XTRA BR S-AR:		FAIT
// 20)2ND PRECISION+ 360 ACTIVE XTRA BROWN HC:		FAIT
// 21)2ND PRECISION+ 360 ACTIVE XTRA GR S-AR:		FAIT

// 22)2ND PRECISION+ 360 ACTIVE XTRA GREY HC:

//1
case '2ND PRECISION+ 360 ACTIVE DRIVEWEAR HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;

//2
case '2ND PRECISION+ 360 ACTIVE DRIVEWEAR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH		
break;



//3
case '2ND PRECISION+ 360 ACTIVE HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//4
case '2ND PRECISION+ 360 ACTIVE PHOTO BR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//5
case '2ND PRECISION+ 360 ACTIVE PHOTO BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//6
case '2ND PRECISION+ 360 ACTIVE PHOTO GR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//7
case '2ND PRECISION+ 360 ACTIVE PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name like '%neochrome%' and product_name not like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//8
case '2ND PRECISION+ 360 ACTIVE POLAR BR S-AR':
//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//9
case '2ND PRECISION+ 360 ACTIVE POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//10
case '2ND PRECISION+ 360 ACTIVE POLAR GR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//11
case '2ND PRECISION+ 360 ACTIVE POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//12
case '2ND PRECISION+ 360 ACTIVE SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name not like '%420%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//13
case '2ND PRECISION+ 360 ACTIVE TRANS BR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//14
case '2ND PRECISION+ 360 ACTIVE TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//15
case '2ND PRECISION+ 360 ACTIVE TRANS GR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	





//16
case '2ND PRECISION+ 360 ACTIVE TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//17
case '2ND PRECISION+ 360 ACTIVE UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//18
case '2ND PRECISION+ 360 ACTIVE UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//19
case '2ND PRECISION+ 360 ACTIVE XTRA BR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//20
case '2ND PRECISION+ 360 ACTIVE XTRA BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//21
case '2ND PRECISION+ 360 ACTIVE XTRA GR S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



//22
case '2ND PRECISION+ 360 ACTIVE XTRA GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//FIN 360 ACTIVE (2IEME PAIRE)

//DÉBUT  Precision+ 360 (1ere paire)
//1)PRECISION+ 360 DRIVEWEAR HC:		FAIT
//2)PRECISION+ 360 DRIVEWEAR SUPER AR: 	FAIT
//3)PRECISION+ 360 HC:					FAIT
//4)PRECISION+ 360 PHOTO BR SUPER AR:	FAIT
//5)PRECISION+ 360 PHOTO BROWN HC:		FAIT
//6)PRECISION+ 360 PHOTO GREY HC: 		FAIT
//7)PRECISION+ 360 PHOTO GREY SUPERAR: 	FAIT
//8)PRECISION+ 360 POLAR BR SUPER AR:	FAIT
//9)PRECISION+ 360 POLAR BROWN HC: 		FAIT
//10)PRECISION+ 360 POLAR GREY HC:		FAIT
//11)PRECISION+ 360 POLAR GREY SUPERAR:	FAIT
//12)PRECISION+ 360 SUPER AR:			FAIT
//13)PRECISION+ 360 TRANS BR SUPER AR:	FAIT
//14)PRECISION+ 360 TRANS BROWN HC:		FAIT
//15)PRECISION+ 360 TRANS GR SUPER AR:	FAIT
//16)PRECISION+ 360 TRANS GREY HC:		FAIT
//17)PRECISION+ 360 TRANS GREY SUPER AR:FAIT
//18)PRECISION+ 360 UV420 HC:			FAIT
//19)PRECISION+ 360 UV420 SUPER AR: 	FAIT



//1 
case 'PRECISION+ 360 DRIVEWEAR HC':
//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	


//2
case 'PRECISION+ 360 DRIVEWEAR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//3
case 'PRECISION+ 360 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//4
case 'PRECISION+ 360 PHOTO BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//5
case 'PRECISION+ 360 PHOTO BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//6
case 'PRECISION+ 360 PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//7
case 'PRECISION+ 360 PHOTO GREY SUPERAR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//8
case 'PRECISION+ 360 POLAR BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//9
case 'PRECISION+ 360 POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//10
case 'PRECISION+ 360 POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//11
case 'PRECISION+ 360 POLAR GREY SUPERAR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//12
case 'PRECISION+ 360 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' AND PRODUCT_NAME NOT LIKE '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
		
	
	if ($DESIGN <> ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Aucun design ne sont disponibles pour ce produit. Svp enlever le design et re-exporter la commande. <br> No Design are available 
		for this product. Please remove the design and re-export the order.';
	}	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//13
case 'PRECISION+ 360 TRANS BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//14
case 'PRECISION+ 360 TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//15
case 'PRECISION+ 360 TRANS GR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//16
case 'PRECISION+ 360 TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//17
case 'PRECISION+ 360 TRANS GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//18
case 'PRECISION+ 360 UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name like '%420%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//19
case 'PRECISION+ 360 UV SUPER AR':
case 'PRECISION+ 360 UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name  NOT LIKE '%2ieme%' AND product_name like '%420%' AND product_name NOT LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	

//FIN  Precision+ 360 (1ere paire)


//ROOM de HKO, SANS ARMOUR 420
case 'ROOM (4M) HC': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'ROOM (4M) AR+ETC': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'ROOM (4M) HD AR': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;

//ROOM de HKO, AVEC ARMOUR 420
case 'ROOM (4M) UV420 HC': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'ROOM (4M) UV420 AR+ETC': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'ROOM (4M) UV420 HD AR': //HKO
	$ProdName  = "  product_name like '%Room (4M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;

//OFFICE de HKO, SANS ARMOUR 420
case 'OFFICE (2M) HC': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'OFFICE (2M) AR+ETC': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'OFFICE (2M) HD AR': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name NOT LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;


//OFFICE de HKO, AVEC ARMOUR 420
case 'OFFICE (2M) UV420 HC': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='Hard Coat';
break;

case 'OFFICE (2M) UV420 AR+ETC': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='ITO AR';
break;

case 'OFFICE (2M) UV420 HD AR': //HKO
	$ProdName  = "  product_name like '%Office (2M)%' AND product_name  LIKE '%UV420%'"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING	='HD AR';
break;




//DÉBUT  Precision+ 360 (2IEME paire)

//1)2ND PRECISION+ 360 DRIVEWEAR HC: 			FAIT
//2)2ND PRECISION+ 360 DRIVEWEAR SUPER AR:		FAIT
//3)2ND PRECISION+ 360 HC:						FAIT
//4)2ND PRECISION+ 360 PHOTO BROWN SUPER AR:	FAIT
//5)2ND PRECISION+ 360 PHOTO GREY HC:			FAIT
//6)2ND PRECISION+ 360 PHOTO GREY SUPER AR:		FAIT
//7)2ND PRECISION+ 360 POLAR BROWN HC:			FAIT
//8)2ND PRECISION+ 360 POLAR BROWN SUPER AR: 	FAIT
//9)2ND PRECISION+ 360 POLAR GREY HC:			FAIT
//10)2ND PRECISION+ 360 POLAR GREY SUPER AR:	FAIT
//11)2ND PRECISION+ 360 SUPER AR:				FAIT
//12)2ND PRECISION+ 360 TRANS BROWN HC:			FAIT
//13)2ND PRECISION+ 360 TRANS BROWN SUPER AR: 	FAIT
//14)2ND PRECISION+ 360 TRANS GREY HC: 			FAIT
//15)2ND PRECISION+ 360 TRANS GREY SUPER AR:	FAIT
//16)2ND PRECISION+ 360 UV420 HC:				FAIT
//17)2ND PRECISION+ 360 UV420 SUPER AR: 		FAIT
//18)2ND PRECISION+ 360 XTRACTIVE BR SUPER AR:  FAIT
//19)2ND PRECISION+ 360 XTRACTIVE BROWN HC: 	FAIT
//20)2ND PRECISION+ 360 XTRACTIVE GR SUPER AR: 	FAIT
//21)2ND PRECISION+ 360 XTRACTIVE GREY HC: 		EN COURS



//1
case '2ND PRECISION+ 360 DRIVEWEAR HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//2
case '2ND PRECISION+ 360 DRIVEWEAR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//3
case '2ND PRECISION+ 360 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%420%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//4
case '2ND PRECISION+ 360 PHOTO BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//5
case '2ND PRECISION+ 360 PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	


//6
case '2ND PRECISION+ 360 PHOTO GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' and product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//7
case '2ND PRECISION+ 360 POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//8
case '2ND PRECISION+ 360 POLAR BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//9
case '2ND PRECISION+ 360 POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//10
case '2ND PRECISION+ 360 POLAR GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//11
case '2ND PRECISION+ 360 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name not like '%420%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//12
case '2ND PRECISION+ 360 TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name  NOT like '%photo%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//13
case '2ND PRECISION+ 360 TRANS BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name NOT like '%photo%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//14
case '2ND PRECISION+ 360 TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name NOT like '%photo%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//15
case '2ND PRECISION+ 360 TRANS GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name NOT like '%photo%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//16
case '2ND PRECISION+ 360 UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name  like '%420%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//17
case '2ND PRECISION+ 360 UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%active%' AND product_name  like '%420%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//18	
case '2ND PRECISION+ 360 XTRACTIVE BR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%Precision+ 360 active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//19
case '2ND PRECISION+ 360 XTRACTIVE BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%Precision+ 360 active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//20
case '2ND PRECISION+ 360 XTRACTIVE GR SUPER AR': 
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%Precision+ 360 active%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//21
case '2ND PRECISION+ 360 XTRACTIVE GR HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%Precision+ 360 active%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//FIN  Precision+ 360 (2IEME paire)

//DÉBUT Anti fatigue

case 'PRECISION+ ANTI FATIGUE BLUECUT AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%anti fatigue%' AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "BluCut";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;

case 'PRECISION+ ANTI FATIGUE UV 420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement

	$ProdName  = "  product_name like '%anti fatigue%' AND product_name NOT  LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name  like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;

case '2ND PRECISION+ ANTI FATIGUE BLUECUT AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%anti fatigue%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "BluCut";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;

case '2ND PRECISION+ ANTI FATIGUE UV420 S-AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%anti fatigue%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name  like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;

//FIN ANTI FATIGUE



//DÉBUT PRECISION+ OFFICE
//1)PRECISION+ OFFICE UV420 SUPER AR: 	FAIT
//2)PRECISION+ OFFICE BLUECUT AR:		FAIT
//3)2ND PRECISION+ OFFICE BLUECUT AR:	FAIT
//4)2ND PRECISION+ OFFICE UV420 SUPER AR:


case 'PRECISION+ OFFICE UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%office%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name  like '%420%' and product_name like '%precision%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;


case 'PRECISION+ OFFICE BLUECUT AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%office%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%' AND product_name NOT like '%420%' and product_name like '%precision%' "; 
	$ORDER_PRODUCT_COATING	= "BluCut";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;


case '2ND PRECISION+ OFFICE BLUECUT AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%office%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name NOT like '%420%' and product_name like '%precision%' "; 
	$ORDER_PRODUCT_COATING	= "BluCut";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;


case '2ND PRECISION+ OFFICE UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%office%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%' AND product_name  like '%420%' and product_name like '%precision%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
break;

//FIN PRECISION+ OFFICE



case 'SV SURFACE TEINTE GRIS 85% HC':
case 'SV SURFACE TINTED GREY 85% HC':
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%tinted grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING="Hard Coat";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



case 'PROMO PRECISION+ POL BRUN HC':
case 'PROMO PRECISION+ POL BROWN HC':

$Design="Exterieur";

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




//DÉBUT [BETTER]: PRECISION+: 22 Cas  (1ERE PAIRE) 
/*
1)PRECISION+ DRIVEWEAR HC: 				FAIT
2)PRECISION+ DRIVEWEAR SUPER AR:		FAIT
3)PRECISION+ HC:						FAIT
4)PRECISION+ PHOTO BROWN HC:			FAIT
5)PRECISION+ PHOTO BROWN SUPER AR: 		FAIT
6)PRECISION+ PHOTO GREY HC: 			FAIT
7)PRECISION+ PHOTO GREY SUPER AR: 		FAIT
8)PRECISION+ POLAR BROWN HC: 			FAIT
9)PRECISION+ POLAR BROWN SUPER AR:		FAIT
10)PRECISION+ POLAR GREY HC: 			FAIT
11)PRECISION+ POLAR GREY SUPER AR:		FAIT
12)PRECISION+ SUPER AR: 				FAIT
13)PRECISION+ TRANS BROWN HC:			FAIT
14)PRECISION+ TRANS BROWN SUPER AR: 	FAIT
15)PRECISION+ TRANS GREY HC:			FAIT
16)PRECISION+ TRANS GREY SUPER AR:		FAIT
17)PRECISION+ UV420 HC:					FAIT
18)PRECISION+ UV420 SUPER AR:			FAIT
19)PRECISION+ XTRACTIVE BROWN HC: 		FAIT
20)PRECISION+ XTRACTIVE BROWN SUPER AR:	FAIT
21)PRECISION+ XTRACTIVE GREY HC:		FAIT
22)PRECISION+ XTRACTIVE GREY SUPER AR:	FAIT
*/

//1
case 'PRECISION+ DRIVEWEAR HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//2
case 'PRECISION+ DRIVEWEAR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//3
case 'PRECISION+ HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//4
case 'PRECISION+ PHOTO BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  AND product_name like '%neochrome%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//5
case 'PRECISION+ PHOTO BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  AND product_name like '%neochrome%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//6
case 'PRECISION+ PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  AND product_name like '%neochrome%'"; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//7
case 'PRECISION+ PHOTO GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  AND product_name like '%neochrome%'"; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//8
case 'PRECISION+ POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//9
case 'PRECISION+ POLAR BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name not like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//10
case 'PRECISION+ POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//11
case 'PRECISION+ POLAR GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//12
case 'PRECISION+ SUPER AR':

	switch($DESIGN){

		case 'lecture': case 'premier porteur': case 'quotidien':case 'ultra court':      
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Il y a uniquement  4 designs disponibles pour ce produit: (Tout Usage, Intérieur, Extérieur ou Conduite).<br>
		There are only 4 designs available with this product:All Purpose, Indoor, Outdoor, Drive).';
		break;
		case 'tout usage':	break;	
		case 'interieur':   break;		
		case 'exterieur':   break;
		case 'extérieur':   break;
		case 'conduite':  echo 'Passe';  break;
		
		
	}//End Switch
	
	

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%' AND product_name not like '%promo%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//13
case 'PRECISION+ TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//14
case 'PRECISION+ TRANS BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//15
case 'PRECISION+ TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//16
case 'PRECISION+ TRANS GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') ||($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//17
case 'PRECISION+ UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name  like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//18
case 'PRECISION+ UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name  like '%420%'   "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//19
case 'PRECISION+ XTRACTIVE BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//20
case 'PRECISION+ XTRACTIVE BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//21
case 'PRECISION+ XTRACTIVE GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//22
case 'PRECISION+ XTRACTIVE GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//FIN [BETTER]: Precision+ 1ere PAIRE


//DÉBUT [BETTER]: Precision+ 2IEME PAIRE

/*
1)2ND PRECISION+ DRIVEWEAR HC:				FAIT
2)2ND PRECISION+ DRIVEWEAR SUPER AR:		FAIT
3)2ND PRECISION+ HC: 						FAIT
4)2ND PRECISION+ PHOTO BROWN HC: 			FAIT
5)2ND PRECISION+ PHOTO BROWN SUPER AR:		FAIT
6)2ND PRECISION+ PHOTO GREY HC: 			FAIT
7)2ND PRECISION+ PHOTO GREY SUPER AR:		FAIT
8)2ND PRECISION+ POLAR BROWN HC				FAIT
9)2ND PRECISION+ POLAR BROWN SUPER AR:		FAIT
10)2ND PRECISION+ POLAR GREY HC: 			FAIT
11)2ND PRECISION+ POLAR GREY SUPER AR:		FAIT
12)2ND PRECISION+ SUPER AR: 				FAIT
13)2ND PRECISION+ TRANS BROWN HC: 			FAIT
14)2ND PRECISION+ TRANS BROWN SUPER AR: 	FAIT
15)2ND PRECISION+ TRANS GREY HC:			FAIT
16)2ND PRECISION+ TRANS GREY SUPER AR: 		FAIT
17)2ND PRECISION+ UV420 HC:					FAIT
18)2ND PRECISION+ UV420 SUPER AR: 			FAIT
19)2ND PRECISION+ XTRACTIVE BROWN HC:		FAIT
20)2ND PRECISION+ XTRACTIVE BROWN SUPER AR:	FAIT
21)2ND PRECISION+ XTRACTIVE GREY HC: 		FAIT
22)2ND PRECISION+ XTRACTIVE GREY SUPER AR:	FAIT
*/

//1
case '2ND PRECISION+ DRIVEWEAR HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name not like '%360%'
	AND product_name NOT  like '%420%'  AND product_name NOT like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//2
case '2ND PRECISION+ DRIVEWEAR SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name NOT like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "Drivewear";
	$ORDER_PRODUCT_PHOTO	= "Drivewear";
	
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//3
case '2ND PRECISION+ HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name NOT like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//4
case '2ND PRECISION+ PHOTO BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//5
case '2ND PRECISION+ PHOTO BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//6
case '2ND PRECISION+ PHOTO GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//$
case '2ND PRECISION+ PHOTO GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name   LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%neochrome%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//8
case '2ND PRECISION+ POLAR BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;

//9
case '2ND PRECISION+ POLAR BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Brown";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//10
case '2ND PRECISION+ POLAR GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//11
case '2ND PRECISION+ POLAR GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//12
case '2ND PRECISION+ SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//13
case '2ND PRECISION+ TRANS BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//14
case '2ND PRECISION+ TRANS BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//15
case '2ND PRECISION+ TRANS GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//16
case '2ND PRECISION+ TRANS GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//17
case '2ND PRECISION+ UV420 HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name   like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//18
case '2ND PRECISION+ UV420 SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name   like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



//19
case '2ND PRECISION+ XTRACTIVE BROWN HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 

	AND product_name not like '%360%' AND product_name NOT  like '%420%'   "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//20
case '2ND PRECISION+ XTRACTIVE BROWN SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name   LIKE '%2ieme%' AND product_name  LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Brown";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//21
case '2ND PRECISION+ XTRACTIVE GREY HC':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Hard Coat";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//22
case '2ND PRECISION+ XTRACTIVE GREY SUPER AR':
	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name NOT  like '%420%'  AND product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Super AR";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Extra Active Grey";
	if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


//FIN [BETTER]: Precision+ 2IEME PAIRE


case 'NURBS PROG TEINTE GRIS':  // HKO--> 2016-08-31
case 'NURBS PROG GREY TINT':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name  like '%tinted Grey%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		/*switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}*/	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;



case 'PROG HD TEINTE GRIS 85% AR BACK': 
case 'PROG HD TINTED GREY 85% AR BACK':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

	$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%tinted grey%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING="AR Backside";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '7': $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;   		
		}
}
	if ($EYE == 'Both'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
		}elseif($EYE == 'R.E.'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
		}elseif($EYE == 'L.E.'){
			$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
		}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';		
break;






case 'NURBS PROG TEINTE BRUN':  // HKO--> 2016-08-31
case 'NURBS PROG BROWN TINT':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name  like '%tinted Brown%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;

case 'NURBS PROG POLARISE BRUN':  // HKO--> 2016-08-31
case 'NURBS PROG BROWN POLARIZED':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='Brown' and photo='none' and product_name NOT like '%tinted%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_POLAR = "Brown";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'NURBS PROG POLARISE GRIS':  // HKO--> 2016-08-31
case 'NURBS PROG GREY POLARIZED':  // Halifax
	$ORDER_PRODUCT_POLAR = "Grey";
	$ORDER_PRODUCT_PHOTO = "None";
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv'  and product_name NOT like '%tinted%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;





case 'DUO PREMIUM OFFICE': // HKO--> 2016-08-31 //MEME POUR HALIFAX
		
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" DUO Premium Office (Central Lab) avec Xlr(Swiss) = Combinaison impossible..<br>DUO Premium Office(Central Lab) with Xlr(Swiss) = Impossible combination.";
}	
		
if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" DUO Premium Office (Central Lab) avec StressFree(Swiss) = Combinaison impossible. <br> DUO Premium Office(Central Lab) with StressFree(Swiss) = Impossible combination.";
	$InsererDansBD  = false;
}	
		
	$ProdName  = "  product_name like '%PROMO PREMIUM OFFICE%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'PREMIUM OFFICE':  // HKO--> 2016-08-31 // MEME POUR HALIFAX
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Premium Office (Central Lab ) avec Xlr(Swiss) = Combinaison impossible.<br> Premium Office(Central Lab) with Xlr(Swiss) = Impossible combination.";
	$InsererDansBD  = false;
}



if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Premium Office (Central Lab ) avec StressFree(Swiss) = Combinaison impossible.<br> Premium Office(Central Lab) with StressFree(Swiss) = Impossible combination.";
	$InsererDansBD  = false;
}


if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%PREMIUM OFFICE%' AND product_name like '%armour%'"; 
	}else{
		$ProdName  = "  product_name like '%PREMIUM OFFICE%' AND product_name not like '%armour%'  AND product_name not like '%420%' "; 
	}
	
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
    if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'PROGRESSIF HD ALPHA':  // HKO--> 2016-08-31
case 'PROG HD ALPHA':  // HKO--> 2016-08-31
case 'ALPHA HD':  // Halifax
	$ProdName  = "  product_name like '%Alpha HD%' AND product_name not like '%thin%' AND product_name NOT LIKE '%promo%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	
	if ($ORDER_PRODUCT_COATING=='StressFree'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" PROGRESSIF HD ALPHA (Central Lab/Essilor Lab) avec StressFree(Swiss) = Combinaison impossible.<br>ALPHA HD(Central Lab/Essilor Lab) with StressFree(Swiss) = Impossible combination.";
		$InsererDansBD  = false;
	}
		
	if ($ORDER_PRODUCT_COATING=='Xlr'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" PROGRESSIF HD ALPHA (Central Lab/Essilor Lab) avec Xlr(Swiss) = Combinaison impossible.<br>ALPHA HD(Central Lab/Essilor Lab) with Xlr(Swiss) = Impossible combination";
		$InsererDansBD  = false;
	}
				
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product. Please add the corridor and re-export
		the order.';
	}//Fin si aucun corridor n'a été fournis	
		
		
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break;    
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'SECUR. PROG. NUM. AR': //  HKO--> 2016-08-31
case 'SECUR. NUM. PROGRESSIVE AR': //  Halifax	
if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product. Please add the corridor and re-export the order.';
}//Fin si aucun corridor n'a été fournis			
		
$ProdName  = "  lens_category NOT IN ('sv','bifocal') AND product_name  like '%Digital Progressive%'"; 
$ORDER_PRODUCT_COATING = 'AR';
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

	
case 'SECUR. PROG. NUM. HC': //  HKO--> 2016-08-31
case 'SECUR. NUM. PROGRESSIVE HC': //  Halifax
$ProdName  = "  lens_category NOT IN ('sv','bifocal') AND product_name  like '%Digital Progressive%'"; 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'SECURITE PROGRESSIF HD ':  // HKO--> 2016-08-31
case 'SECURITE PROGRESSIF HD':
case 'SECURITE HD PROGRESSIVE':  // Halifax
case 'SECURITY HD PROGRESSIVE':  // Halifax
$ProdName  = "  product_name like '%Progressive HD%' AND product_name NOT LIKE '%digital%' AND product_name NOT LIKE '%outdoor%' AND product_name NOT LIKE '%indoor%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if (($CORRIDOR <> '')&& ($PHOTO=='none')) {
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break; 
			case '7':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break;    
			case '9':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break;    
			case '11': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
			case '13': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
			case '15': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
		}
}
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'SECURITE PROGRESSIF INDIVIDUALISE': // HKO--> 2016-08-31
case 'SECURITE INDIVIDUALIZED PROGRESSIVE': // Halifax
case 'SECURITY INDIVIDUALIZED PROGRESSIVE': // Halifax
$ProdName  = "  product_name like '%Individuali%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if (($CORRIDOR <> '') && ($ORDER_PRODUCT_INDEX<>'1.67')){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



//SV Tandem
case 'SV RX CLEAR NIGHT VISION':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%clear%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Night Vision';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX POLAR BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%polarized brown%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_POLAR	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX POLAR GREY AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%polarized%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_POLAR	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX TRANS ACCLIMATE BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%acclimate%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX TRANS ACCLIMATE GREY AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%acclimate%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX TRANSITIONS VII BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%transition%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX TRANSITIONS VII GREY AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%transition%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX SOLID TINT 85% BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' AND product_name like '%solid tint%'  AND product_name like '%Brown%' AND product_name not like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;


case 'SV RX SOLID TINT 85% GREY AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' AND product_name like '%solid tint%'  AND product_name like '%Grey%' AND product_name like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;




//2ieme PAIRE:
case '2ND SV RX  TINT 85% BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' AND product_name like '%solid tint%'  AND product_name like '%Brown%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Brown';
break;

case '2ND SV RX  TINT 85% GREY AR BACKSIDE':
   $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' AND product_name like '%solid tint%'  AND product_name like '%Grey%' AND product_name like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
	
	//TODO IMPORTANT, INCLURE l'extra teinte afin qu'il se rende au fournisseur!
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
	$TINT_COLOR		= 'Grey';
break;

case '2ND SV RX CLEAR NIGHT VISION':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%clear%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Night Vision';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case '2ND SV RX POLAR BROWN AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%polarized%' AND product_name like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_POLAR	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case '2ND SV RX POLAR GREY AR BACKSIDE':
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%polarized%' AND product_name like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_POLAR	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case '2ND SV RX TRANS ACCL BROWN AR BACKSIDE':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%acclimate%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case '2ND SV RX TRANS ACCL GREY AR BACKSIDE':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%acclimate%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case '2ND SV RX TRANS VII BROWN AR BACKSIDE':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%transition%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case '2ND SV RX TRANS VII GREY AR BACKSIDE':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%SV RX%' and product_name like '%transition%' AND product_name  like '%paire%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn        = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING  = 'AR Backside';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;





case 'INTERNET PROGRESSIVE PACKAGE ETC':
	$ProdName  = "  product_name like '%internet%' AND product_name not like '%stock%'  AND product_name not like '%fatigue%'   AND product_name not like '%office%' "; 
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING = 'SPC';
break;

//ROBERTO HBC 4 SEPT

case 'SV RX ETC PACKAGE TRANSITIONS GREY': //HBC  == 'Single Vision Rx Transitions VII Brown 1,5 SPC'
	$ProdName  = "    product_name  NOT like '%stock%' AND product_name like '%Single Vision Rx%' and product_name like '%Transitions VII%' AND coating IN ('SPC') "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX ETC PACKAGE TRANSITIONS BROWN': //HBC  == 'Single Vision Rx Transitions VII Brown 1,5 SPC'
	$ProdName  = "    product_name  NOT like '%stock%' AND product_name like '%Single Vision Rx%' and product_name like '%Transitions VII%' AND coating IN ('SPC') "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'PROG PACKAGE TRANSITIONS GREY ETC'://NOUVEL AJOUT POUR ROBERTO le 4 Septembre 2019
	$ProdName  = "  product_name like '%progressive package%' AND product_name  like '%transitions%'  AND product_name not like '%stock%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO	= 'Grey';
break;

case 'PROG PACKAGE TRANSITIONS BROWN ETC'://NOUVEL AJOUT POUR ROBERTO le 4 Septembre 2019
	$ProdName  = "  product_name like '%progressive package%' AND product_name  like '%transitions%'  AND product_name not like '%stock%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO	= 'Brown';
break;


case 'FORFAIT PLANO POLARISE BRUN HC': 
case 'PLANO BROWN POLAR HC SV PACKAGE': //Halifax
	$ProdName  = "  product_name like '%plano%' AND product_name not like '%stock%' AND coating IN ('HC','Hard Coat')"; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_POLAR   = 'Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'MAXIIVUE II SV STOCK'://HBC
	
	
	if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%stock%' AND product_name not like '%impact%' and product_name like '%420%' "; 
	}else{
		$ProdName  = "  product_name like '%stock%' AND product_name not like '%impact%' and product_name NOT like '%420%' "; 
	}
	
	if ($ORDER_PRODUCT_INDEX=='1.59'){
		$ErrorDetail   .="Stock with MaxiVue2 is not available in Index 1.59. You could order it in 1.60";
		$InsererDansBD  = false;	
	}
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING = 'MaxiVue2';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'STRESSFREE SV STOCK'://HBC
	$ProdName  = "  product_name like '%stock%'  "; 
	
	if ($ORDER_PRODUCT_INDEX=='1.59'){
		$ErrorDetail   .="Stock with StressFree is not available in Index 1.59. You could order it in 1.60";
		$InsererDansBD  = false;	
	}
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING = 'StressFree';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


		
case 'FORFAIT PLANO AR+ETC TRANS GRIS':
case 'FORFAIT PLANO AR+ETC TRANS. GRIS'://STC
case 'PLANO ETC GREY TRANS SV PACKAGE'://Halifax
	$ProdName  = "  product_name like '%plano%' AND product_name not like '%stock%' AND coating IN ('AR Backside','AR+ETC','Dream AR','ITO AR')"; 
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	if ($TINT_COLOR <> ''){
		$ProdName  .= "  AND product_name like '%$TINT_COLOR%'"; 
	}else{
		$ProdName  .= "  AND product_name NOT like '%tinted%'"; 	
	}
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'FORFAIT PLANO AR+ETC TRANS BRUN': //STC
case 'FORFAIT PLANO AR+ETC TRANS. BRUN': //STC
case 'FORFAIT PLANO AR+ETC TRANS.BRUN':
case 'PLANO ETC BROWN TRANS SV PACKAGE ': //Halifax
	$ProdName  = "  product_name like '%plano%' AND product_name not like '%stock%' AND coating IN ('AR Backside','AR+ETC','Dream AR','ITO AR')"; 
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	if ($TINT_COLOR <> ''){
		$ProdName  .= "  AND product_name like '%$TINT_COLOR%'"; 
	}else{
		$ProdName  .= "  AND product_name NOT like '%tinted%'"; 	
	}
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
	$RE_ADD = 0;
	$LE_ADD = 0;
break;




	
case 'FORFAIT SIMPLE VISION AR+ETC': //STC / HKO / GKB
case 'ETC SV PACKAGE': //Halifax
case 'ETC SV STOCK': //HBC
case 'ETC SV STOCK ': //HBC
case 'BTS STOCK AR+ETC': //HBC



	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is  impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
	
	if (($ORDER_PRODUCT_PHOTO<>'None') && ($ORDER_PRODUCT_INDEX == 1.59)){
		$ErrorDetail   .=" The product ETC SV STOCK is not available with Transitions in Index 1.59. You could order it in index 1.60";
		$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_INDEX == 1.53){
		$ErrorDetail   .=" Stock products are not available in Index 1.53";
		$InsererDansBD  = false;
	}
	

	

    $RE_ADD = 0;
	$LE_ADD = 0;
	if (($ARMOUR420 == 'armour 420') || ($IIMPACT=='iimpact')){
		$ProdName  = "  product_name like '%single vision stock%' AND product_name like '%impact%' AND product_name like '%armour%' and product_name not like '%-6.00 to 2.75%' "; 
	}else{
		$ProdName  = "  product_name like '%single vision stock%'  AND product_name NOT like '%impact%' AND product_name not like '%armour%' and product_name not like '%-6.00 to 2.75%' AND product_name not like '%420%' "; 
	}

	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING = 'SPC';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



	

case 'HC SV STOCK': //HBC
case 'HC SV STOCK ': //HBC
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
	
	if (($ORDER_PRODUCT_PHOTO<>'None') && ($ORDER_PRODUCT_COATING == 'Hard Coat')){
		$ErrorDetail   .=" No stock product are available with both  Hard Coat coating and Transitions.";
		$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_INDEX == 1.53){
		$ErrorDetail   .=" Stock products are not available in Index 1.53";
		$InsererDansBD  = false;
	}
	
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'PACKAGE SV STOCK HC': //HBC
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'PACKAGE SV STOCK IIMPACT AR+ETC': //HBC
	$ORDER_PRODUCT_COATING = 'SPC';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND  product_name like '%High Impact%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'PACKAGE SV STOCK AR420 AR+ETC': //HBC
	$ORDER_PRODUCT_COATING = 'SPC';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND  product_name like '%Armour 420%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'PACKAGE SV STOCK AR420 MAXIVUE II': //HBC
	$ORDER_PRODUCT_COATING = 'Maxivue2';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND product_name not like '%impact%' AND  product_name like '%Armour 420%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'PACKAGE SV STOCK TRANSITION BROWN AR+ETC': //HBC
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO 	= 'Brown';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' and product_name not like '%420%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'PACKAGE SV STOCK TRANSITION GREY AR+ETC': //HBC
	$ORDER_PRODUCT_COATING 	= 'SPC';
	$ORDER_PRODUCT_PHOTO 	= 'Grey';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' and product_name not like '%420%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'PACKAGE SV STOCK AR420 IIMPACT MAXIVUE 2': //HBC
	$ORDER_PRODUCT_COATING = 'Maxivue2';
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' and product_name like '%High Impact%' AND  product_name like '%Armour 420%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	




case 'PACKAGE SV STOCK AR+ETC': //HBC
	$ORDER_PRODUCT_COATING = 'SPC';
	
$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT
	
	if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%single vision stock%' $ConditionHighImpact AND product_name not like '%griff%' and product_name like '%420%' "; 
	}else{
	$ProdName  = "  product_name like '%single vision stock%' $ConditionHighImpact AND product_name not like '%griff%' and product_name NOT like '%420%' "; 
	}
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'PACKAGE SV STOCK MAXIVUE II': //HBC
	$ORDER_PRODUCT_COATING = 'Maxivue2';
	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It is impossible to add a polarized option on a stock product";
		$InsererDansBD  = false;
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND product_name not like '%impact%' and product_name not like '%420%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	






case 'SV SURFACE': //MINERAL== GLASS
case 'SURFACED SV': //MINERAL== GLASS Halifax
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%mineral sv%' and lens_category='sv' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
	$ORDER_PRODUCT_COATING = 'MultiClear AR';
	else{
	$ORDER_PRODUCT_COATING = 'Uncoated';
	}
	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	

case 'ST28': //MINERAL== GLASS //Halifax
	$ProdName  = "  product_name like '%ft-28 Mineral%' and lens_category='bifocal' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
		$ORDER_PRODUCT_COATING = 'MultiClear AR';
	else{
		$ORDER_PRODUCT_COATING = 'Uncoated';
	}
break;	

case 'PROGRESSIFS CLASSIQUES': //MINERAL== GLASS
case 'CLASSIC': //MINERAL== GLASS Halifax

	$ProdName  = "  product_name like '%mineral%' and lens_category='glass'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
	$ORDER_PRODUCT_COATING = 'MultiClear AR';
	else
	$ORDER_PRODUCT_COATING = 'Uncoated';
	
break;	

case 'PROGRESSIFS CLASSIQUES COURT': //MINERAL== GLASS
case 'SHORT CLASSIC': //MINERAL== GLASS Halifax
	$ProdName  = "  product_name like '%mineral%' and lens_category='glass' and corridor = 9 "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
	$ORDER_PRODUCT_COATING = 'MultiClear AR';
	
break;	

case 'SV STOCK': //MINERAL== GLASS//HALIFAX
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%stock mineral%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
	$ORDER_PRODUCT_COATING = 'MultiClear AR';
	else
	$ORDER_PRODUCT_COATING = 'Uncoated';	
	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'FORFAIT SIMPLE VISION HC':
case 'HC SV PACKAGE'://Halifax	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}

	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdName  = "  product_name like '%single vision stock%' AND coating IN ('HC','Hard Coat')"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	



case 'FORFAIT SV AR+ETC TRANSITIONS GRIS': // GKB / HKO
case 'ETC GREY TRANS SV PACKAGE': //Halifax
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.5'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.6'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS GREY':
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Grey'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.5'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.6'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN 1.67'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS BROWN':
case 'PACKAGE SV AR+ETC TRANSITIONS BRUN':
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;

//SURFACE LENSES
case 'PACKAGE SV RX AR+ETC TRANSITIONS BROWN 1.5'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS BROWN 1.6'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS BROWN 1.67'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS BROWN':
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%'  AND product_name not like '%action%' AND product_name NOT LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;

//SURFACE LENSES
case 'PACKAGE SV RX AR+ETC TRANSITIONS GREY 1.5'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS GREY 1.6'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS GREY 1.67'://HBC
case 'PACKAGE SV RX AR+ETC TRANSITIONS GREY':

 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name not like '%action%' AND product_name NOT LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Grey'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;

case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.5'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.6'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS GREY 1.67'://HBC
case 'PACKAGE SV AR+ETC TRANSITIONS GREY':
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'SPC';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.5'://HBC
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.6'://HBC
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY 1.67'://HBC
case 'PACKAGE SV MAXIIVUE TRANSITIONS GREY':
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'MaxiVue2';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


//Surface lenses
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS GREY 1.5'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS GREY 1.6'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS GREY 1.67'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS GREY':

 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name not like '%Action%' AND product_name NOT LIKE '%stock%'  AND product_name NOT LIKE '%HD%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'MaxiVue2';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


//Stock lenses
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.5'://HBC
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.6'://HBC
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.67'://HBC
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'MaxiVue2';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



//Stock lenses
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS ':
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.5'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.6'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN 1.67'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITIONS BROWN'://HBC
case 'PACKAGE SV RX MAXIIVUE TRANSITION BROWN'://HBC
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
 	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Single Vision%' AND product_name LIKE '%stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Brown'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Brown';
	$ORDER_PRODUCT_COATING = 'MaxiVue2';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;





/*
case 'FORFAIT SV AR+ETC TRANSITIONS ':// GKB / HKO
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%Stock%'  AND product_name NOT LIKE '%HD%'  AND photo ='Grey'";
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO = 'Grey';
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	*/


case 'OFFICE INTERNET PACKAGE ETC': //

	if ($ORDER_PRODUCT_COATING<>'Hard Coat'){ 
		$ErrorDetail   .=" The product Office Internet Package ETC is only available with SPC coating. ";
		$InsererDansBD  = false;	
	}

	$ORDER_PRODUCT_COATING = 'SPC';
	$ProdName  			= "  product_name like '%Internet Office Package%'"; 
	$ProdTable 			= " ifc_ca_exclusive"; 
	$CollectionNotIn  	= " AND collection NOT IN ('')";
	
break;

		
case 'TOP CURVE':case 'CURVE TOP': case 'CURVE TOP 28':
	$ProdName  = "  product_name like '%CURVE TOP%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'CURVE TOP BIFOCAL':
	$ProdName  = "  product_name like '%Curve Top Bifocal%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'FLAT TOP BIFOCAL 70/28':
	$ProdName  = "  product_name like '%Flat Top Bifocal 70/28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'FLAT TOP BIFOCAL 75/28':
	$ProdName  = "  product_name like '%28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'FT28': //STC et SWISS
	$ProdName  			= "  product_name like '%28%' AND product_name not like '%7x28%' and collection not like '%KNR%' and product_name NOT like '%griff%'"; 
	$ProdTable 			= " ifc_ca_exclusive"; 
	$CollectionNotIn  	= " AND collection NOT IN ('')";	
break;
	


case 'PROMO PRECISION+ POL GREY AR BACK':
case 'PROMO PRECISION+ POL GRIS AR BACK':
case 'PROMO PRECISION+ POLAR GREY SUPER AR BACK': 

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+%' AND product_name like '%promo%' AND product_name  NOT LIKE '%2ieme%' AND product_name NOT LIKE '%pair%'  AND product_name like '%precision%' 
	AND product_name not like '%360%' AND product_name not like '%420%'  "; 
	$ORDER_PRODUCT_COATING	= "AR Backside";
	$ORDER_PRODUCT_POLAR	= "Grey";
	$ORDER_PRODUCT_PHOTO	= "None";
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH
break;

	
case 'ST35': //STC //MEME POUR HALIFAX
case 'FT35'://HBO
	$ProdName  = "  product_name like '%FT35%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	

case 'TRIFOCAL 28 ': case 'TRIFOCAL 28': //STC / HKO / GKB //MEME POUR HALIFAX
	$ProdName  = "  product_name like '%7x28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'TRIFOCAL 28 AR+ETC': //STC / HKO / GKB //MEME POUR HALIFAX
	$ProdName  = "  product_name like '%7x28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$order_product_coating ="Dream AR";
break;

case 'TRIFOCAL 28 AR BACKSIDE': //STC / HKO / GKB //MEME POUR HALIFAX
	$ProdName  = "  product_name like '%7x28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$order_product_coating ="AR Backside";
break;

case 'PACKAGE TRIFOCAL 7X28 HC 1.5':
case 'PACKAGE TRIFOCAL 7X28 HC':
	 if($ARMOUR420=='armour 420'){
		$ErrorDetail   .=" Armour 420 option is not available on Trifocal lenses.";
		$InsererDansBD  = false;  
	 }//End IF
 
	$ProdName  = "  product_name like '% 7x28%'"; 
	$ORDER_PRODUCT_COATING='Hard Coat';
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;



case '7X28 TRIFOCAL':
	 if($ARMOUR420=='armour 420'){
		$ErrorDetail   .=" Armour 420 option is not available on Trifocal lenses.";
		$InsererDansBD  = false;  
	 }//End IF
	$ProdName  = "  product_name like '%Trifocal%' and product_name like '%28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'PACKAGE TRIFOCAL 7X28 ':
if($ARMOUR420=='armour 420'){
		$ErrorDetail   .=" Armour 420 option is not available on Trifocal lenses.";
		$InsererDansBD  = false;  
	 }//End IF
 
	$ProdName  = "  product_name like '%7x28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

break;


case 'PACKAGE TRIFOCAL 7X28 ETC/BACKSIDE':
	 if($ARMOUR420=='armour 420'){
		$ErrorDetail   .=" Armour 420 option is not available on Trifocal lenses.";
		$InsererDansBD  = false;  
	 }//End IF
 
	$ProdName  = "  product_name like '%7x28%'"; 
	$ORDER_PRODUCT_COATING='SPC Backside';
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'PACKAGE TRIFOCAL 7X28 AR+ETC':
	 if($ARMOUR420=='armour 420'){
		$ErrorDetail   .=" Armour 420 option is not available on Trifocal lenses.";
		$InsererDansBD  = false;  
	 }//End IF
 
	$ProdName  = "  product_name like '%7x28%'"; 
	$ORDER_PRODUCT_COATING='SPC';
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'FT35': //STC //MEME POUR HALIFAX
	$ProdName  = "  product_name like '%FT35%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'FT 8X35':
	$ProdName  = "  product_name like '%8x35%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case '8X35 TRIFOCAL':
	$ProdName  = "  product_name like '%8x35 trifocal%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'IOFFICE HD': //Swiss //HALIFAX

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

 if(($ARMOUR420=='armour 420') && ($ORDER_PRODUCT_INDEX=='1.50')){
	$ErrorDetail   .=" Armour 420 option is not available on index 1.50 for the iOffice. It starts on index 1.60";
	$InsererDansBD  = false;  
 }//End IF
 
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%i-Office%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%i-Office%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 
}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";

break;

case 'IRELAX': //Swiss //HALIFAX

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


	if ($ORDER_PRODUCT_COATING=='HD AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" iRelax (Swiss) avec HD AR(Central Lab) = Impossible..";
		$InsererDansBD  = false;
	}	
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 
}		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'IRELAX MAXIVUE II': //Swiss //HBO NEW UPDATE
$ORDER_PRODUCT_COATING='MaxiVue2';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


		
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 
}		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;



case 'IRELAX ARMOUR420 STRESSFREE': //Swiss //HBO NEW UPDATE
$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

$ORDER_PRODUCT_COATING='StressFree';
$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'IRELAX ARMOUR420 AR+ETC': //Swiss //HBO NEW UPDATE
$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

$ORDER_PRODUCT_COATING='SPC';
$ProdName  = "  product_name like '%irelax%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;







case 'FORFAIT SIMPLE VISION MAXIIVUE': //Swiss
case 'MAXIIVUE II SV PACKAGE': //Halifax	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%single vision stock%' AND product_name NOT LIKE '%Single Vision Stock 1.60 Aspheric plano to -8.00%' AND product_name like '%420%' "; 
}else{
	$ProdName  = "  product_name like '%single vision stock%' AND product_name NOT LIKE '%Single Vision Stock 1.60 Aspheric plano to -8.00%'  AND product_name not like '%420%' "; 
}
	
	

	$RE_ADD = 0;
	$LE_ADD = 0;
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Xlr';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	
	
		
case 'FORFAIT SIMPLE VISION LOW REFLEXION': //GKB
//case 'MAXIIVUE II SV PACKAGE': //Halifax	
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock.  It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}

	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND coating IN ('Low Reflexion')"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Low Reflexion';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;			

case 'FORFAIT SIMPLE VISION STRESSFREE': //Swiss
case 'STRESSFREE SV PACKAGE': //Halifax
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" It\'s not possible to add polarized on a stock lens. On ne peut pas ajouter une option Polarisé sur un verre de stock";
		$InsererDansBD  = false;
	}
	
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND product_name NOT LIKE '%Single Vision Stock 1.60 Aspheric plano to -8.00%' 
	AND coating IN ('StressFree')"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'StressFree';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	


case 'IFREE PLUS ADVANCE MAXIIVUE':
	$ProdName  				= " product_name like '%Ifree Plus Advance%' and product_name  NOT LIKE '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "Xlr";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}
break;


case 'IREADER': //Swiss//MEME POUR HALIFAX
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%iReader%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%iReader%' AND product_name NOT LIKE '%armour 420%'"; 	
}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'IROOM': //Swiss //MEME POUR HALIFAX
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%iRoom%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%iRoom%' AND product_name not like '%armour 420%'"; 	
}
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;




case 'FORFAIT PLANO HC': 
case 'PLANO CLEAR HC SV PACKAGE ': //Halifax
	$ProdName  = "  product_name like '%plano%'  AND product_name not like '%stock%' AND coating IN ('HC','Hard Coat') AND product_name not like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	if (($TINT == 'Gradient') && ($TINT_COLOR == 'Gray')){
	$ProdName  =$ProdName .  "  AND product_name like '%grey%'"; 	
	}
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;
	
	
	case 'FORFAIT PLANO HC TRANS GRIS': 
	case 'PLANO HC GREY TRANS SV PACKAGE': //Halifax
	$ProdName  = "  product_name like '%plano%'  AND product_name not like '%stock%' AND coating IN ('HC','Hard Coat') AND product_name not like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	if (($TINT == 'Gradient') && ($TINT_COLOR == 'Gray')){
	$ProdName  =$ProdName .  "  AND product_name like '%grey%'"; 	
	}
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_PHOTO   = "Grey";
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;
	
		
case 'FORFAIT PLANO HC TRANS BRUN': 
case 'PLANO HC BROWN TRANS SV PACKAGE': //Halifax
	$ProdName  = "  product_name like '%plano%'  AND product_name not like '%stock%' AND coating IN ('HC','Hard Coat') AND product_name not like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	if (($TINT == 'Gradient') && ($TINT_COLOR == 'Brown')){
	$ProdName  =$ProdName .  "  AND product_name like '%Brown%'"; 	
	}
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_PHOTO   = "Brown";
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


		
case 'FORFAIT PLANO POLARISE GRIS HC': 
case 'PLANO GREY POLAR HC SV PACKAGE': //Halifax
	$ProdName  = "  product_name like '%plano%'  AND product_name not like '%stock%' AND polar ='Grey' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_POLAR   = 'Grey';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	



case 'PROGRESSIF IACTION':  //SWISS
case 'IACTION':  //HALIFAX
case 'IACTION PROGRESSIVE':
$ProdName  = "  product_name like '%i-Action%' AND lens_category <> 'sv'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;
		
		
case 'PROGRESSIF IFREE':  //SWISS
case 'IFREE':  //Halifax
if ($ORDER_PRODUCT_COATING=='HD AR'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" iFree(Swiss) avec HD AR(Central Lab) = Combinaison impossible. iFree(Swiss) with HD AR(Central Lab) = Impossible combination.";
	$InsererDansBD  = false;
	}
	
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%ifree%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%ifree%' AND product_name not like '%armour 420%'"; 	
}
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;		


case 'IFREE 3':  //Halifax

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if ($ORDER_PRODUCT_COATING=='HD AR'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" iFree(Swiss) avec HD AR(Central Lab) = Combinaison impossible. iFree(Swiss) with HD AR(Central Lab) = Impossible combination.";
	$InsererDansBD  = false;
}
	
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%ifree 3%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%ifree 3%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	
		
		
case 'IFREE PLUS ADVANCE TRANS GREY HC':  //HKO
$ProdName  = "  product_name like '%iFree plus advance%' AND product_name like '%transitions grey%'";
$ORDER_PRODUCT_COATING='Hard Coat';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;		
	
case 'IFREE PLUS ADVANCE TRANS GREY AR+ETC':  //HKO
$ProdName  = "  product_name like '%iFree plus advance%' AND product_name like '%transitions grey%'";
$ORDER_PRODUCT_COATING ='Dream AR';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	

case 'IFREE PLUS ADVANCE TRANS GREY HD AR':  //HKO
$ProdName  			   = "  product_name like '%iFree plus advance%' AND product_name like '%transitions grey%'";
$ORDER_PRODUCT_COATING = 'HD AR';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	

case 'IFREE PLUS ADVANCE TRANS BROWN HC':  //HKO
$ProdName  = "  product_name like '%iFree plus advance%' AND product_name like '%transitions brown%'";
$ORDER_PRODUCT_COATING='Hard Coat';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;		
	
case 'IFREE PLUS ADVANCE TRANS BROWN AR+ETC':  //HKO
$ProdName  = "  product_name like '%iFree plus advance%' AND product_name like '%transitions brown%'";
$ORDER_PRODUCT_COATING ='Dream AR';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	

case 'IFREE PLUS ADVANCE TRANS BROWN HD AR':  //HKO
$ProdName  			   = "  product_name like '%iFree plus advance%' AND product_name like '%transitions brown%'";
$ORDER_PRODUCT_COATING = 'HD AR';
$ProdTable 			   = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;	
	
	
	
	
case 'IFREE 2':  //Halifax
if ($ORDER_PRODUCT_COATING=='HD AR'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" iFree (Swiss) avec HD AR(Central Lab) = Impossible..";
	$InsererDansBD  = false;
	}
	
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%ifree 2%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%ifree 2%' AND product_name not like '%armour 420%'"; 	
}
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'PROGRESSIF INDIVIDUALISE IMPRESSION'://HKO
case 'PROGRESSIF INDIVIDUALISE  IMPRESSION'://HKO
case 'INDIVIDUALIZED IMPRESSION': //Halifax
	if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" Impression (Central lab) avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" Impression (Central lab) avec StressFree(Swiss) = Impossible..";
	$InsererDansBD  = false;
	}
	
	$ProdName  = "  product_name like '%Impression%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
		if ($CORRIDOR == ''){
			$InsererDansBD  = false;
			$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
		}//Fin si aucun corridor n'a été fournis		
		
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;    
		}
	}
		
break;



case 'PRECISION ADVANCE': //Swiss //Ajouter 420

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


	if ($ORDER_PRODUCT_COATING=='HD AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Precision Advance (Swiss) with  HD AR(Central Lab) = IMPOSSIBLE COMBINAISON.";
		$InsererDansBD  = false;
	}
		
	if ($ARMOUR420=='armour 420'){
	$ProdName  = "   product_name like '%precision advance%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%Precision Advance%' $ConditionHighImpact AND product_name not like '%420%'"; 
}	
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
break;


case 'PROGRESSIF CAMBER ': //Swiss 
case 'CAMBER ': //Swiss Halifax
	if ($ORDER_PRODUCT_COATING=='HD AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" iFree (Swiss) avec HD AR(Central Lab) = Impossible..";
		$InsererDansBD  = false;
	}
		
	if (($ORDER_PRODUCT_COATING<>'Xlr') && ($ORDER_PRODUCT_COATING<>'StressFree')) {
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Le Camber est uniquement offert les traitements Xlr ou StressFree.";
		$InsererDansBD  = false;
	}		
		
	$ProdName  = "  product_name like '%Ultimate Freestyle Camber%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;


case 'PROGRESSIF MAXIWIDE MAXIIVUE':
case 'PROGRESSIF MAXIWIDE MAXIIVUE': //Swiss 
case 'MAXIWIDE ': //Swiss //HALIFAX
case 'MAXIWIDE': //Swiss //HALIFAX
case 'PROGRESSIF MAXIWIDE':


$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "   product_name like '%MaxiWide%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "   product_name like '%MaxiWide%' $ConditionHighImpact AND  product_name NOT like '%armour 420%'"; 	
}


if (($ARMOUR420=='armour 420') && ($ORDER_PRODUCT_INDEX=='1.5')){
	$ErrorDetail.="Armour 420 is not available with Maxiwide in Index 1.50";
	$InsererDansBD  = false;	
}

//$ORDER_PRODUCT_COATING='Xlr';



	/*if (($ORDER_PRODUCT_INDEX=='1.74') && ($ORDER_PRODUCT_PHOTO=='Grey' || $ORDER_PRODUCT_PHOTO=='Extra Active Grey')){
	//Transitions Non offert  en 1.74
	$ErrorDetail.=" Transitions are not available with Maxiwide  1.74";
	$InsererDansBD  = false;	
	}*/	
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
break;	



	
case 'MAXIWIDE MAXIVUE II': //Swiss //HBC

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if (($ARMOUR420=='armour 420') && ($ORDER_PRODUCT_INDEX=='1.5')){
	$ErrorDetail.="Armour 420 is not available with Maxiwide in Index 1.50";
	$InsererDansBD  = false;	
}

if ($ARMOUR420=='armour 420'){
	$ProdName  = "   product_name like '%MaxiWide%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "   product_name like '%MaxiWide%' $ConditionHighImpact AND  product_name NOT like '%armour 420%'"; 	
}

$ORDER_PRODUCT_COATING	= 'MaxiVue2';
$ProdTable 				= "ifc_ca_exclusive"; 
$CollectionNotIn       	= " AND collection NOT IN ('')";
break;			
		
		

case 'PROGRESSIF HD ULTIMATE': //HKO 
case 'HD ULTIMATE': //HKO //Halifax
	$ProdName  = "  product_name like '%ultimate par RDTK%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	
	if ($ORDER_PRODUCT_COATING=='StressFree'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Progressif Hd Ultimate(Central Lab) avec StressFree(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_COATING=='Xlr'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Progressif Hd Ultimate(Central Lab)  avec Xlr(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
		
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
		
	}//Fin si aucun corridor n'a été fournis		
		
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '7': $ProdName  .= " AND corridor = 15 "; $SauterValidationFH = "yes"; break; 
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
  
		}
	}
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

break;


case 'FORFAIT PLANO TEINTE HC': 
case 'PLANO TINT HC SV PACKAGE': 	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}

	$ProdName  = "  product_name like '%plano%'  AND product_name not like '%stock%' "; 
	
	if ($TINT_COLOR == 'Brown'){
		$TINT_COLOR     = 'Brown';
		$ProdName = $ProdName . "  AND product_name like '%Brown%' ";
	}
	
	if ($TINT_COLOR == 'Grey'){
		$TINT_COLOR     = 'Grey';	
		$ProdName = $ProdName . " AND product_name like '%Grey%' "; 
	}
	
	if ($TINT_COLOR == 'Gray'){
		$TINT_COLOR     = 'Grey';	
		$ProdName = $ProdName . " AND product_name like '%Grey%' "; 
	}
	
	if ($TINT_COLOR == 'G-15'){
		$TINT_COLOR     = 'G-15';
		$ProdName = $ProdName . " AND product_name like '%G-15%' "; 
	}
	/*switch(strtoupper($TINT_COLOR=='BROWN')){
		case 'BROWN':$ProdName .= " AND product_name like '%tinted Brown%' ";  break;
		case 'G-15': $ProdName .= " AND product_name like '%tinted G-15%'  ";   break;
		case 'GREY': $ProdName .= " AND product_name like '%tinted Grey%'  ";   break;
		default :   echo '<br>Tint color:'. 	strtoupper($TINT_COLOR);
	}*/

	$ProdTable 			   = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;	


case 'PROGRESSIF INTERNET AR+ETC': // STC / GKB
case 'INTERNET PROGRESSIVE ETC': // STC / GKB Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS %' AND product_name not like '%office%' ";
$ORDER_PRODUCT_COATING = 'Dream AR'; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('')";

if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;      
		}	
}
break;

case 'PROGRESSIF INTERNET AR+ETC POL. GRIS ': // STC / GKB
case 'PROGRESSIF INTERNET AR+ETC POL. GRIS':
case 'INTERNET PROGRESSIVE ETC GREY POLAR'://Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS %'";
$ORDER_PRODUCT_COATING = 'Dream AR'; 
$ORDER_PRODUCT_POLAR = 'GREY'; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;
		
		
case 'PROGRESSIF INTERNET AR+ETC POL. BRUN ': // STC / GKB
case 'PROGRESSIF INTERNET AR+ETC POL. BRUN':
case 'INTERNET PROGRESSIVE ETC BROWN POLAR ': //Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS %'";
$ORDER_PRODUCT_COATING = 'Dream AR'; 
$ORDER_PRODUCT_POLAR = 'Brown'; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'ROND 22': //STC //GKB
case 'ROUND 22': //Halifax
case 'RD 22': //Halifax
case 'RD22': //HBC
$ProdName  = "  product_name like '%RD22%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;




case 'PROGRESSIF INTERNET AR+ETC TRANS. BRUN': 
case 'PROGRESSIF INTERNET AR+ETC TRANS. BRUN ': //GKB / STC
case 'INTERNET PROGRESSIVE ETC BROWN TRANS ': //GKB / STC Halifax
$ORDER_PRODUCT_COATING = "Dream AR";
$ProdName  = "  product_name like '%Promo Internet%'"; 
$ORDER_PRODUCT_PHOTO = "Brown";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROGRESSIF INTERNET HC POL GRIS ': 
case 'PROGRESSIF INTERNET HC POL GRIS': 
case 'PROGRESSIF INTERNET HC POL. GRIS':
case 'INTERNET PROGRESSIVE HC GREY POLAR': //Halifax
$ProdName  = "  product_name like '%Promo Internet%'";
$ORDER_PRODUCT_COATING = "Hard Coat"; 
$ORDER_PRODUCT_POLAR = "Grey";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'PROGRESSIF INTERNET HC POL BRUN ': 
case 'PROGRESSIF INTERNET HC POL BRUN': 
case 'PROGRESSIF INTERNET HC POL. BRUN': 	
case 'INTERNET PROGRESSIVE HC BROWN POLAR': 	//Halifax			
$ProdName  = "  product_name like '%Promo Internet%'";
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;      
		}	
}
$ORDER_PRODUCT_COATING = "Hard Coat"; 
$ORDER_PRODUCT_POLAR = "Brown";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}

	
break;


case 'PROGRESSIF INTERNET AR+ETC TRANS. GRIS ': 
case 'PROGRESSIF INTERNET AR+ETC TRANS. GRIS': 
case 'INTERNET PROGRESSIVE ETC GREY TRANS': //Halifax
$ProdName  = "  product_name like '%Promo Internet%'";
$ORDER_PRODUCT_COATING = "Dream AR"; 
$ORDER_PRODUCT_PHOTO = "Grey";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROGRESSIF INTERNET HC': //STC
case 'INTERNET PROGRESSIVE HC': //STC Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROGRESSIF INTERNET HC TRANS. BRUN':
case 'INTERNET PROGRESSIVE HC BROWN TRANS'://Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS%'"; 
$ORDER_PRODUCT_PHOTO = 'Brown';
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

		
case 'CURVETOP 28':
case 'CURVETOP 28 ':
case ' CURVETOP 28':
$ProdName  = "  product_name like '%curve%'"; 
$ORDER_PRODUCT_PHOTO = 'none';
$ORDER_PRODUCT_POLAR = 'none';
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;	
		
		
case 'PROGRESSIF INTERNET HC TRANS. GRIS': //STC
case 'PROGRESSIF INTERNET HC TRANSITION GRIS': //STC
case 'INTERNET PROGRESSIVE HC GREY TRANS': //STC Halifax
$ProdName  = "  product_name like '%Promo Internet/MAS%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_PHOTO = 'Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'SOLOTECH HD': //Halifax

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Solotech HD%' $ConditionHighImpact AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%Solotech HD%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;





case 'SIMPLE VISION HD': 
case 'HD SINGLE VISION': //Halifax
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%HD Single Vision%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%HD Single Vision%' AND product_name not like '%armour 420%'"; 	
}

	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



//NOUVELLE PROMO MIS EN PLACE LE 24 MARS 2017
case 'SV SURFACE TEINTE GRIS': 
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%tinted grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



case 'PACKAGE STOCK TINTED GREY HC': 
	$ORDER_PRODUCT_COATING='Hard Coat';
	$ProdName  = "  product_name like '%package stock%' AND product_name  like '%tinted grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;





case 'PACKAGE STOCK CLEAR AR+ETC': 
	$ORDER_PRODUCT_COATING='SPC';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '0';
			$RE_HEIGHT = '0';	
	}
	$ProdName  = "  product_name like '%stock%' and product_name like '%package%' and product_name not like '%tinted%' AND product_name  like '%SPC%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$LE_HEIGHT = '0';
	$RE_HEIGHT = '0';
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
break;

case 'PACKAGE STOCK PHOTOSUN GREY AR+ETC': 
	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$ProdName  = "  product_name like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;


case 'PACKAGE RX HC': 

	$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
	if ($IIMPACT<>''){
		$ConditionHighImpact= " AND product_name like '%High Impact%' ";
	}//FIN SI EXTRA IIMPACT

	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ProdName  = "  product_name like '%single vision%' $ConditionHighImpact and product_name not like '%420%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;

case 'PACKAGE RX AR+ETC': 

if ($ARMOUR420=='armour 420'){
		$ProdName  = "  product_name like '%single vision%' AND product_name not like '%HD%' and product_name not like '%stock%' and product_name not like '%tinted%' AND product_name like '%420%'"; 	
	}else{
		$ProdName  = "product_name like '%single vision%' AND product_name not like '%HD%' and product_name not like '%stock%' and product_name not like '%tinted%' AND product_name not like '%420%'"; 	
	}

	$ORDER_PRODUCT_COATING = 'SPC';	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;

case 'PACKAGE RX PHOTOSUN GREY HC': 
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;

case 'PACKAGE RX PHOTOSUN BROWN HC': 
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;



case 'PACKAGE RX PHOTOSUN GREY AR+ETC': 
	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_PHOTO   = 'Grey';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;


case 'PACKAGE RX PHOTOSUN BROWN AR+ETC': 
	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_PHOTO   = 'Brown';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;


case 'PACKAGE RX POLARIZED GREY HC': 
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_POLAR   = 'Grey';
	
	$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
	if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
	}//FIN SI EXTRA IIMPACT
	
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%' $ConditionHighImpact"; 	
	
	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;

case 'PACKAGE RX POLARIZED BROWN HC': 
	$ORDER_PRODUCT_COATING = 'Hard Coat';
	$ORDER_PRODUCT_POLAR   = 'Brown';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;
                                                                                                                                                                                                                                                           
case 'PACKAGE RX POLARIZED GREY AR+ETC': 
	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_POLAR   = 'Grey';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%impact%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;


case 'PACKAGE RX POLARIZED BROWN AR+ETC': 
//
	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_POLAR   = 'Brown';
	$ProdName  = "  product_name like '%single vision%' and product_name not like '%stock%' and product_name not like '%impact%' and product_name not like '%tinted%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;

case 'PACKAGE STOCK TINTED GREY AR+ETC': 
	$ORDER_PRODUCT_COATING='SPC';
	$ProdName  = "  product_name like '%package stock tinted grey%' AND product_name  like '%tinted grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;


case 'PACKAGE STOCK TINTED BROWN HC': 
	$ORDER_PRODUCT_COATING='Hard Coat';
	$ProdName  = "  product_name like '%package stock tinted grey%' AND product_name  like '%tinted brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Brown';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;




case 'PACKAGE STOCK TINTED BROWN AR+ETC': 
	$ORDER_PRODUCT_COATING='SPC';
	$ProdName  = "  product_name like '%package stock%' AND product_name  like '%tinted brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Brown';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



case 'SV SURFACE TEINTE BRUN': 
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%tinted brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';
			
break;



case 'PACKAGE RX TINTED GREY AR+ETC': 
	$ORDER_PRODUCT_COATING='SPC';
	$ProdName  = "  product_name like '%package rx%' AND product_name like '%tinted grey%' "; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



// MIS EN COMMENTAIRE CAR ON ATTENDS LES CODES...2022/04/14
case 'SIMPLE VISION HD': 
case 'HD SINGLE VISION': //Halifax
case 'SIMPLE VISION HD ASPH': 
case 'HD ASPH SINGLE VISION':
if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%HD Single Vision%' AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%HD Single Vision%' AND product_name not like '%armour 420%'"; 	
}
	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'PACKAGE RX TINTED BROWN AR+ETC': 
	$ORDER_PRODUCT_COATING='SPC';
	$ProdName  = "  product_name like '%package rx%' AND product_name like '%tinted brown%' "; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Brown';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



case 'PACKAGE RX POLARIZED GREY AR+ETC': 
	$ORDER_PRODUCT_COATING	= 'SPC';
	$ORDER_PRODUCT_POLAR	= 'Grey';
	$ProdName  = "  product_name like '%package rx'%  and product_name like '%polarized grey%' "; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'PACKAGE RX POLARIZED BROWN AR+ETC': 
	$ORDER_PRODUCT_COATING	= 'SPC';
	$ORDER_PRODUCT_POLAR	= 'Brown';
	$ProdName  = "  product_name like '%package rx'%  and product_name like '%polarized brown%' "; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'SV SURFACE POL GRIS': 
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ORDER_PRODUCT_POLAR='Grey';
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	




case 'SV SURFACE POL BRUN': 
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_POLAR='Brown';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	





case 'PROG HD TEINTE GRIS': 
if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor (9 , 11mm ou 13mm) est obligatoire  pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> A corridor of 9, 11 or 13 mm is mandatory for this product';
		
}//Fin si aucun corridor n'a été fournis	
	$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%tinted grey%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break; 
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;     
		}
}
	if ($EYE == 'Both'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
		}elseif($EYE == 'R.E.'){
			$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
		}elseif($EYE == 'L.E.'){
			$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
		}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';		
break;


case 'RX AR-ETC UV 420 - BLUE CUT':
case 'SIMPLE VISION SURFACE UV420 AR+ETC':
case 'ASPHERIC SINGLE VISION UV420 ETC'://Halifax
	$ProdName  				= " product_name like '%Single Vision RX%' and product_name like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "ITO AR";
	$CollectionNotIn       	= "  ";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'SIMPLE VISION SURFACE UV420 LOW REF':
case 'ASPHERIC SINGLE VISION UV420 ETC'://Halifax
	$ProdName  				= " product_name like '%Single Vision RX%' and product_name like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "Low Reflexion";
	$CollectionNotIn       	= "  ";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'PROG HD TEINTE BRUN': 
if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor (9 , 11mm ou 13mm) est obligatoire  pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> A corridor of 9, 11 or 13 mm is mandatory for this product';
		
}//Fin si aucun corridor n'a été fournis	
$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%tinted brown%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;  		
		}
}
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;



case 'PROG HD POL GRIS AR BACK': 
case 'PROG HD POL GREY AR BACK':

$Design="Exterieur";

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

if ($ORDER_PRODUCT_COATING =='AR+ETC'){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Ce produit polarisé est uniquement disponible avec AR+ETC Face Interne ou Hard Coat Svp faites la modification nécessaire et ré-exporter la commande.<br>This polarized 
		product is only available in Hard Coat or AR Backside<br>';
		
}//Fin si aucun corridor n'a été fournis	

$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized grey%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="AR Backside";
$ORDER_PRODUCT_POLAR = 'Grey';
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '7': $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;  
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;   
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<> '') && ($LE_HEIGHT<> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'PROG HD POL GRIS': 
if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor (9 , 11mm ou 13mm) est obligatoire  pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> A corridor of 9, 11 or 13 mm is mandatory for this product';
		
}//Fin si aucun corridor n'a été fournis	

if ($CORRIDOR == '13'){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor de 13mm est indisponible pour ce produit. Vous pouvez choisir entre (9 ou 11mm). Svp modifier le corridor et re-exporter la commande.<br>';
		
}//Fin si aucun corridor n'a été fournis	
$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized grey%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR = 'Grey';
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;   
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;		
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<> '') && ($LE_HEIGHT<> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'PROG HD POL BRUN AR BACK':
	
if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor (9 , 11mm ou 13mm) est obligatoire  pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> A corridor of 9, 11 or 13 mm is mandatory for this product';		
}//Fin si aucun corridor n'a été fournis	
$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized brown%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR = 'Brown';
$ORDER_PRODUCT_COATING = "AR Backside";
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;	
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<>'') && ($LE_HEIGHT<>'')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROG HD POL BRUN':
	
if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor (9 , 11mm ou 13mm) est obligatoire  pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> A corridor of 9, 11 or 13 mm is mandatory for this product';		
}//Fin si aucun corridor n'a été fournis	
$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized brown%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR = 'Brown';
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;	
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<>'') && ($LE_HEIGHT<>'')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PACKAGE SV MAXIIVUE TRANSITIONS BROW 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROW':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN 1.5':
case 'PACKAGE SV MAXIIVUE TRANSITIONS BROWN':
$ProdName  = "  product_name LIKE '%stock%' AND  lens_category = 'sv'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING = 'MaxiVue2';
$ORDER_PRODUCT_PHOTO   = 'Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
break;

case 'SIMPLE VISION IACTION':
case 'IACTION SINGLE VISION '://HALIFAX
case 'IACTION SINGLE VISION':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%i-Action%' AND  lens_category = 'sv'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'IACTION DIGITAL SV':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%iAction Digital SV%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'RX AR-ETC UV 420 - BLUE CUT':
	$ProdName  				= " product_name like '%Single Vision RX%' and product_name like '%420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "ITO AR";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;
		
	
case 'PROGRESSIF OPTOTECH MD2 AR+ETC':
	$ProdName  				= " product_name like '%Digital Progressive par Optotech%' "; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "ITO AR";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9': $ProdName  .= " AND corridor  = 9 "; $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
		}
break;		

		
case 'STOCK AR-ETC UV 420 - BLUE CUT':
	$ProdName  				= " product_name like '%Single Vision Stock%' and product_name like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "SPC";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;		
		
		
		
		
case 'SIMPLE VISION SURFACE': 
case 'ASPHERIC SINGLE VISION': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
	if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
	}//FIN SI EXTRA IIMPACT
	
	

	if ($ARMOUR420=='armour 420'){
		$ProdName  = "   product_name like '%Single Vision%' AND collection not like '%knr%' $ConditionHighImpact  AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv'AND product_name like '%420%'"; 	
	}else{
		$ProdName  = " product_name like '%Single Vision%' AND collection not like '%knr%' $ConditionHighImpact  AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name not like '%420%'"; 	
	}
		
	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;
		
		

case 'STRESSFREE SV STOCK':
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name LIKE '%stock%' AND  lens_category = 'sv'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING='StressFree';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'RX SINGLE VISION':
$ConditionHighImpact= " AND product_name NOT like '%High Impact%' AND collection not like '%KNR%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' AND collection not like '%KNR%' ";
}//FIN SI EXTRA IIMPACT
	
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	
	//Product not available in 1.60 polarized Green
	if (($ORDER_PRODUCT_INDEX=='1.60')&&($ORDER_PRODUCT_POLAR=='Green')){
		$ErrorDetail.=" The product RX Single Vision 1.6 Polarized Green is not available, you could get it in 1.59.";
		$InsererDansBD  = false;		
	}//End IF
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.59'){
	$ErrorDetail.=" Armour 420 is not available on index 1.59";
	$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.50'){
	$ErrorDetail.=" Armour 420 is not available on index 1.50";
	$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420'){
		$ProdName  = "   product_name like '%Single Vision%' $ConditionHighImpact  AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%'
		AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name like '%armour 420%'"; 	
	}else{
		$ProdName  = " product_name like '%Single Vision%' $ConditionHighImpact AND product_name not like '%420%' AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' 
		AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name not like '%armour 420%'"; 	
	}
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;





case 'BTS ASP RX AR+ETC':
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	$ORDER_PRODUCT_COATING='SPC';
	
	/*if (($ORDER_PRODUCT_INDEX=='1.74') && ($ORDER_PRODUCT_PHOTO=='Grey' || $ORDER_PRODUCT_PHOTO=='Brown' || $ORDER_PRODUCT_PHOTO=='Extra Active Grey')){
	//Transitions Non offert  en 1.74
	$ErrorDetail.=" Transitions are not available with Single Vision 1.74";
	$InsererDansBD  = false;	
	}*/
	
	//Product not available in 1.60 polarized Green
	if (($ORDER_PRODUCT_INDEX=='1.60')&&($ORDER_PRODUCT_POLAR=='Green')){
		$ErrorDetail.=" The product BTS ASP RX AR+ETC 1.6 Polarized Green is not available, you could get it in 1.59.";
		$InsererDansBD  = false;		
	}//End IF
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.59'){
		$ErrorDetail.=" Armour 420 is not available on index 1.59";
		$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.50'){
		$ErrorDetail.=" Armour 420 is not available on index 1.50";
		$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420'){
		$ProdName  = "   product_name like '%Single Vision%' AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%'
		AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name like '%armour 420%'"; 	
	}else{
		$ProdName  = " product_name like '%Single Vision%' AND product_name not like '%action%' AND product_name NOT like '%tinted%' AND product_name NOT like '%promo%' 
		AND product_name not like '%mineral%' AND product_name NOT LIKE '%stock%' AND product_name NOT LIKE '%HD Single%' AND lens_Category ='sv' AND product_name not like '%armour 420%'"; 	
	}
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;





case 'BTS INTERNET ANTIFATIGUE AR+ETC':
case 'BTS INTERNET ANTIFATIGUE':
	$RE_ADD = 0;
	$LE_ADD = 0;
	
	$ORDER_PRODUCT_COATING='SPC';
	
	/*if (($ORDER_PRODUCT_INDEX=='1.74') && ($ORDER_PRODUCT_PHOTO=='Grey' || $ORDER_PRODUCT_PHOTO=='Brown' || $ORDER_PRODUCT_PHOTO=='Extra Active Grey')){
	//Transitions Non offert  en 1.74
	$ErrorDetail.=" Transitions are not available with Single Vision 1.74";
	$InsererDansBD  = false;	
	}*/
	
	//Product not available in 1.60 polarized Green
	if (($ORDER_PRODUCT_INDEX=='1.60')&&($ORDER_PRODUCT_POLAR=='Green')){
		$ErrorDetail.=" The product BTS ASP RX AR+ETC 1.6 Polarized Green is not available, you could get it in 1.59.";
		$InsererDansBD  = false;		
	}//End IF
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.59'){
		$ErrorDetail.=" Armour 420 is not available on index 1.59";
		$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420' && $ORDER_PRODUCT_INDEX=='1.50'){
		$ErrorDetail.=" Armour 420 is not available on index 1.50";
		$InsererDansBD  = false;	
	}
	
	if ($ARMOUR420=='armour 420'){
		$ProdName  = "   product_name like '%relax%'  AND product_name like '%armour 420%'"; 	
	}else{
		$ProdName  = "   product_name like '%relax%' AND product_name not like '%armour 420%'"; 	
	}
		
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;



case 'SECUR. SV AR':   // EDLL et Halifax
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category =  'sv' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
$RE_ADD = 0;
$LE_ADD = 0;
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$stock  = 'possible';
$ORDER_PRODUCT_COATING = 'AR';
	
	if (($RE_SPHERE > 2.00) || ($LE_SPHERE > 2.00))
	$stock = 'impossible';
	
	if (($RE_SPHERE < -4) || ($LE_SPHERE < -4))
	$stock = 'impossible';

	$ProdName  = "  product_name like '%Single Vision %' and product_name not like '%revo%'"; 
	if (($ORDER_PRODUCT_INDEX == '1.59') && ($stock == 'possible')) {
		$ProdName  = "  product_name like '%Polycarbonate +2.00 -4.00%'  and product_name not like '%revo%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 	
	}
break;




case 'SECUR. SV REVOLUTION AR':  //Edll et Halifax
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category =  'sv' AND product_name like '%revolution%' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
$RE_ADD = 0;
$LE_ADD = 0;
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$stock  = 'possible';
$ORDER_PRODUCT_COATING = 'AR';
	
	if (($RE_SPHERE > 2.00) || ($LE_SPHERE > 2.00))
	$stock = 'impossible';
	
	if (($RE_SPHERE < -4) || ($LE_SPHERE < -4))
	$stock = 'impossible';

	$ProdName  = "  product_name like '%Single Vision %' and product_name not like '%revo%'"; 
	if (($ORDER_PRODUCT_INDEX == '1.59') && ($stock == 'possible')) {
		$ProdName  = "  product_name like '%Polycarbonate +2.00 -4.00%'  and product_name not like '%revo%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 	
	}
break;



case 'SECUR. SV REVOLUTION HC': //Edll et Halifax 
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category =  'sv' AND product_name like '%revolution%' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
$RE_ADD = 0;
$LE_ADD = 0;
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$stock  = 'possible';
$ORDER_PRODUCT_COATING = 'Hard Coat';
	
	if (($RE_SPHERE > 2.00) || ($LE_SPHERE > 2.00))
	$stock = 'impossible';
	
	if (($RE_SPHERE < -4) || ($LE_SPHERE < -4))
	$stock = 'impossible';

	$ProdName  = "  product_name like '%Single Vision %' and product_name not like '%revo%'"; 
	if (($ORDER_PRODUCT_INDEX == '1.59') && ($stock == 'possible')) {
		$ProdName  = "  product_name like '%Polycarbonate +2.00 -4.00%'  and product_name not like '%revo%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 	
	}
break;


case 'SECUR. SV HC':   //Halifax
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category =  'sv' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
$RE_ADD = 0;
$LE_ADD = 0;
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$stock  = 'possible';
$ORDER_PRODUCT_COATING = 'Hard Coat';
	
	if (($RE_SPHERE > 2.00) || ($LE_SPHERE > 2.00))
	$stock = 'impossible';
	
	if (($RE_SPHERE < -4) || ($LE_SPHERE < -4))
	$stock = 'impossible';

	$ProdName  = "  product_name like '%Single Vision %' and product_name not like '%revo%'"; 
	if (($ORDER_PRODUCT_INDEX == '1.59') && ($stock == 'possible')) {
		$ProdName  = "  product_name like '%Polycarbonate +2.00 -4.00%'  and product_name not like '%revo%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 	
	}
break;


case 'SECUR. ST28 AR'://2016-12-12  Edll et Halifax
$ProdName  = "  lens_category like '%bifocal%'";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
$ORDER_PRODUCT_COATING = 'AR';
break;


case 'PROGRESSIF DUO NUMERIQUE HD': //GKB
case 'DUO OPTOTECH': //Halifax
$ProdName  = "  product_name like '%Promo Duo Digital%' AND product_name not like '%IOT%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

	if ($CORRIDOR <> ''){
			
			//Produit GKB + corridor = On doit filtrer le corridor avec le code produit
			switch($CORRIDOR){
				case '9':  $ProdName  .= " AND corridor =  9 ";  break;    
				case '11': $ProdName  .= " AND corridor = 11 "; break;    
				case '13': $ProdName  .= " AND corridor = 13 "; break;    
			}	

		}//End IF

	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
break;



case 'SECURITE SIMPLE VISION':
case 'SECURITE SV'://Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$stock = 'possible';
	
	if (($RE_SPHERE > 2.00) || ($LE_SPHERE > 2.00))
	$stock = 'impossible';
	
	if (($RE_SPHERE < -4) || ($LE_SPHERE < -4))
	$stock = 'impossible';
	
	
	$ProdName  = "  product_name like '%Single Vision %' and product_name not like '%revo%'"; 
	//if (($ORDER_PRODUCT_INDEX == '1.59') && ($stock == 'possible')) {
		//$ProdName  = "  product_name like '%Polycarbonate +2.00 -4.00%'  and product_name not like '%revo%'"; 
	//}else{
		//$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 	
	//}
	
	
	if ($ORDER_PRODUCT_PHOTO == 'Grey'){
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'  and product_name not like '%revo%'"; 
	}
	
	if ($ORDER_PRODUCT_PHOTO == 'Brown'){
		$ProdName  = "  product_name like '%Single Vision %' AND product_name not like '%+2.00 -4.00%'   and product_name not like '%revo%'"; 
	}
	
	
	$ProdTable = "safety_exclusive"; 
	$CollectionNotIn = "   ";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;

//NURBS SV
case 'NURBS SV  CLAIR': 
case 'NURBS SV  CLEAR': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%Deep%' and lens_category = 'sv' AND polar='none' and photo='none' and product_name not like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


//SAFETY
case 'SECURITE PROGRESSIF CLASSIQUE': //STC
case 'SECURITE CLASSIC PROGRESSIVE': //Halifax
$ProdName  = "  product_name like '%Basic progressive%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
if (($EYE == 'Both') && ($RE_HEIGHT<> '') && ($LE_HEIGHT<> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
}
break;



case 'SECUR. PROG. DE BASE HC': //
case 'SECUR. CLASSIC PROGRESSIVE HC': //Halifax
$ProdName  = "  product_name like '%Basic progressive%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
if (($EYE == 'Both') && ($RE_HEIGHT<> '') && ($LE_HEIGHT<> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
}
break;

case 'SECURITE IRELAX': //Swiss //HALIFAX
	$ProdName  = "  product_name like '%iRelax%'"; 
	$ProdTable = "safety_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC')";
break;

case 'NURBS SV  TEINTE GRIS': 
case 'NURBS SV  GREY TINT': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ORDER_PRODUCT_COATING = "Dream AR";
	$ProdName  = "  product_name like '%Deep%' and lens_category = 'sv' AND polar='none' and photo='none' and product_name  like '%tinted grey%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;

case 'NURBS SV  POLARISE GRIS': 
case 'NURBS SV  GREY POLARIZED'://Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%Deep%' and lens_category = 'sv' and photo='none' and product_name NOT like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$ORDER_PRODUCT_POLAR ='Grey';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;

case 'NURBS SV  POLARISE BRUN': 
case 'NURBS SV  BROWN POLARIZED': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%Deep%' and lens_category = 'sv' AND polar='Brown' and photo='none' and product_name NOT like '%tint%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$ORDER_PRODUCT_POLAR = "Brown";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;


case 'NURBS SV  TEINTE BRUN': 
case 'NURBS SV  BROWN TINT': //Halifax
	$RE_ADD = 0;
	$LE_ADD = 0;
	$ORDER_PRODUCT_COATING = 'Dream AR';
	$ProdName  = "  product_name like '%Deep%' and lens_category = 'sv' AND polar='None' and photo='none' and product_name  like '%tinted Brown%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;



case 'PROGRESSIF OPTOTECH': //GKB
case 'OPTOTECH': //Halifax
if ($ORDER_PRODUCT_COATING=='StressFree'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Progressif Optotech(Essilor Lab) avec StressFree(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_COATING=='Xlr'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.="  Progressif Optotech(Essilor Lab)  avec Xlr(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
		
	if ($ORDER_PRODUCT_COATING=='HD AR'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.=" Progressif Optotech(Essilor Lab) avec HD AR(Central Lab) = Impossible..";
		$InsererDansBD  = false;
	}

$ProdName  = "  product_name like '%Digital Progressive par Optotech%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";

	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (9-11 ou 13mm). Svp ajouter le corridor et re-exporter la commande.<br>';
		
	}//Fin si aucun corridor n'a été fournis

if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9': $ProdName  .= " AND corridor  = 9 "; $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



//PROMO ENFANTS/PROMO DE LA RENTRÉE --> Débute le 7 Août 2017
case 'ENFANT STOCK AR+ETC': 
case 'ETC KIDS STOCK ':  //Halifax
$ORDER_PRODUCT_COATING = 'Dream AR';
$ORDER_PRODUCT_PHOTO = 'None';
$ORDER_PRODUCT_POLAR = 'None';
$ProdName  = "  product_name like '%Promo Etudiant%' and product_name not like '%surface%' and lens_category = 'sv' 
AND sphere_min <= $RE_SPHERE AND sphere_min <= $LE_SPHERE 
AND sphere_max >= $RE_SPHERE AND sphere_max >= $LE_SPHERE";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
$RE_ADD = 0;
$LE_ADD = 0;	
break;





case 'ENFANT SURFACE AR+ETC': 
case 'ETC KIDS ASPHERIC': //Halifax
$ORDER_PRODUCT_COATING = 'Dream AR';
$ORDER_PRODUCT_PHOTO = 'None';
$ORDER_PRODUCT_POLAR = 'None';
$ProdName  = "  product_name like '%Promo Etudiant surface%' and lens_category = 'sv' 
AND sphere_min <= $RE_SPHERE AND sphere_min <= $LE_SPHERE 
AND sphere_max >= $RE_SPHERE AND sphere_max >= $LE_SPHERE";
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
$RE_ADD = 0;
$LE_ADD = 0;	
		
break;



case 'SECUR. ST28 HC': //STC //HALIFAX
case 'SECUR. ST28 HC': //Halifax
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category <> 'sv'  AND lens_category = 'bifocal' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
break;


case 'SECURITE ST28':  //STC //HALIFAX
$ProdName  = "  product_name NOT LIKE '%HD%' and lens_category <> 'sv'  AND lens_category = 'bifocal' ";
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
break;



/////////////////////////////////////////////////////////////////////////////////
//DEBUT HKO

case 'PROGRESSIF INDUVIDUALISE 4D':
case 'PROGRESSIF INDIVIDUALISE 4D': 
case 'PROGRESSIF INDIVIDUALISE 4D ALPHA':
case 'INDIVIDUALIZED ALPHA 4D': //Halifax
if (strtoupper($DESIGN) == 'PREMIER PORTEUR'){
	//Design impossible dans le 4d, on doit faire un message d'erreur. 
	$ErrorDetail.="  Le design Premier Porteur n\'est pas offert sur le produit  INDIVIDUALISE 4D. Changer de design svp ou de produit.";
	$InsererDansBD  = false;
	}	

	//Ultra Court
if (strtoupper($DESIGN) == 'ULTRA COURT'){
	//Design impossible dans le 4d, on doit faire un message d'erreur. 
	$ErrorDetail.="  Le design Ultra Court Porteur n\'est pas offert sur le produit  INDIVIDUALISE 4D. Il est uniquement offert sur le produit Alpha HD. Changer de design ou de produit svp.";
	$InsererDansBD  = false;
	}	
	
	
if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" PROGRESSIF INDIVIDUALISE 4D(Central Lab/Essilor Lab) avec StressFree(Swiss) = Impossible..";
	$InsererDansBD  = false;
}
	
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.="  PROGRESSIF INDIVIDUALISE 4D(Central Lab/Essilor Lab)  avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
}

$ProdName  = "  product_name like '%alpha 4d%' AND product_name NOT LIKE '%promo%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break;    
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND corridor = 15 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




//PROMO RENTRÉE
case 'ENFANT ANTI FATIGUE 0.40 AR+ETC'://GKB
case 'ENFANT ANTI FATIGUE 0.40  AR+ETC':
case 'ETC KIDS ANTI-FATIGUE 0.40'://Halifax

	$ProdName  = "  product_name like '%Promo Etudiant%' and product_name like '%(0.40)%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	//$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS)  . ' Regression: 0.40 ';
	$SPECIAL_INSTRUCTIONS = mysqli_real_escape_string($con,$SPECIAL_INSTRUCTIONS);
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'PROGRESSIF NUM IOT'://HKO 100%--> 2016-08-30
case 'NUM IOT'://Halifax
case 'PROG NUM IOT':	$ProdName  = "  product_name like '%Digital Progressive IOT%' AND product_name NOT LIKE '%promo%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	
	
	if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" Digital Progressive IOT(Central Lab) avec StressFree(Swiss) = Impossible..";
	$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur ici
	$ErrorDetail.=" Digital Progressive IOT(Central Lab)  avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
	}
		
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
		
	}//Fin si aucun corridor n'a été fournis	
		
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break;    
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROG. DUO NUMERIQUE HD IOT': //HKO--> 2016-08-31
case 'PROG. DUO NUMERIQUE HD IOT ': //HKO--> 2016-08-31
case 'DUO IOT ': //Halifax	
if ($CORRIDOR == ''){
			$InsererDansBD  = false;
			$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
}//Fin si aucun corridor n'a été fournis	
		
$ProdName  = "  product_name like '%Promo%' AND product_name  like '%IOT%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break;    
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND corridor = 15 "; $SauterValidationFH = "yes"; break;      
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'NURBS PROG CLAIR':  // HKO--> 2016-08-31
case 'NURBS PROG CLEAR':  //Halifax 
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name NOT like '%tinted%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'NURBS PROG TEINTE GRIS':  // HKO--> 2016-08-31
case 'NURBS PROG GREY TINT':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name  like '%tinted Grey%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		/*switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}*/	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Grey';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;

case 'NURBS PROG TEINTE BRUN':  // HKO--> 2016-08-31
case 'NURBS PROG BROWN TINT':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='None' and photo='none' and product_name  like '%tinted Brown%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING = "Dream AR";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	$AjoutTeintePromo = 'oui';
	//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
	//Inserer dans extra_product_order
	$frame_type		= "";
	$color			= "";
	$order_type		= "";
	$temple			= "";
	$order_num		= -1;
	$main_lab_id	= $LAB;
	$category		= "Tint";
	$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
	$ep_prod_id     = $listItem[prod_id];
	$TINT           = 'Solid';
	$TINT_COLOR     = 'Brown';
	$FROM_PERC      = '85';
	$TO_PERC        = '85';	
break;

case 'NURBS PROG POLARISE BRUN':  // HKO--> 2016-08-31
case 'NURBS PROG BROWN POLARIZED':  // Halifax
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv' AND polar='Brown' and photo='none' and product_name NOT like '%tinted%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	$ORDER_PRODUCT_POLAR = "Brown";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'NURBS PROG POLARISE GRIS':  // HKO--> 2016-08-31
case 'NURBS PROG GREY POLARIZED':  // Halifax
	$ORDER_PRODUCT_POLAR = "Grey";
	$ORDER_PRODUCT_PHOTO = "None";
	$ProdName  = "  product_name like '%Deep%' and lens_category <> 'sv'  and product_name NOT like '%tinted%'"; 
	$ProdTable = "ifc_ca_exclusive";
	$ORDER_PRODUCT_COATING = "Dream AR"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;





case 'DUO PREMIUM OFFICE': // HKO--> 2016-08-31 //MEME POUR HALIFAX
		
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" DUO Premium Office (Central Lab) avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
}	
		
if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" DUO Premium Office (Central Lab) avec StressFree(Swiss) = Impossible..";
	$InsererDansBD  = false;
}	
		
	$ProdName  = "  product_name like '%PROMO PREMIUM OFFICE%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'PREMIUM OFFICE':  // HKO--> 2016-08-31 // MEME POUR HALIFAX
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Premium Office (Central Lab ) avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
}

if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Premium Office (Central Lab ) avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
}
	$ProdName  = "  product_name like '%PREMIUM OFFICE%' AND product_name not like '%promo%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
    if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'PROGRESSIF HD ALPHA':  // HKO--> 2016-08-31
case 'PROG HD ALPHA':  // HKO--> 2016-08-31
case 'ALPHA HD':  // Halifax
	$ProdName  = "  product_name like '%Alpha HD%' AND product_name NOT LIKE '%promo%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	
	if ($ORDER_PRODUCT_COATING=='StressFree'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" PROGRESSIF HD ALPHA (Central Lab/Essilor Lab) avec StressFree(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
		
	if ($ORDER_PRODUCT_COATING=='Xlr'){
		//Produit impossible avec XLR, on doit afficher l'erreur 
		$ErrorDetail.=" PROGRESSIF HD ALPHA (Central Lab/Essilor Lab) avec Xlr(Swiss) = Impossible..";
		$InsererDansBD  = false;
	}
				
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
	}//Fin si aucun corridor n'a été fournis	
		
		
	$SauterValidationFH = "";
	if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND corridor = 15 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'SECUR. PROG. NUM. AR': //  HKO--> 2016-08-31
case 'SECUR. NUM. PROGRESSIVE AR': //  Halifax	
if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
}//Fin si aucun corridor n'a été fournis			
		
$ProdName  = "  lens_category NOT IN ('sv','bifocal') AND product_name  like '%Digital Progressive%'"; 
$ORDER_PRODUCT_COATING = 'AR';
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

	
case 'SECUR. PROG. NUM. HC': //  HKO--> 2016-08-31
case 'SECUR. NUM. PROGRESSIVE HC': //  Halifax
$ProdName  = "  lens_category NOT IN ('sv','bifocal') AND product_name  like '%Digital Progressive%'"; 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
	if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'SECURITE PROGRESSIF HD ':  // HKO--> 2016-08-31
case 'SECURITE HD PROGRESSIVE':  // Halifax
$ProdName  = "  product_name like '%Progressive HD%' AND product_name NOT LIKE '%digital%' AND product_name NOT LIKE '%outdoor%' AND product_name NOT LIKE '%indoor%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if (($CORRIDOR <> '')&& ($PHOTO=='none')) {
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break; 
			case '7':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break;    
			case '9':  $ProdName  .= " AND product_code like '%H%'";  $SauterValidationFH = "no"; break;    
			case '11': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
			case '13': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
			case '15': $ProdName  .= " AND product_code like '%H%'"; $SauterValidationFH = "no"; break;    
		}
}
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'SECURITE PROGRESSIF INDIVIDUALISE': // HKO--> 2016-08-31
case 'SECURITE INDIVIDUALIZED PROGRESSIVE': // Halifax
$ProdName  = "  product_name like '%Individuali%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = " ";
$SauterValidationFH = "";
if (($CORRIDOR <> '') && ($ORDER_PRODUCT_INDEX<>'1.67')){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND product_code like '%5H%'";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND product_code like '%7H%'";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND product_code like '%9H%'";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND product_code like '%11H%'"; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND product_code like '%13H%'"; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND product_code like '%15H%'"; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'PROGRESSIF DUO HD': //HKO--> 2016-08-31
case 'DUO HD': //Halifax
if ($ORDER_PRODUCT_COATING=='Xlr'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Promo Duo HD (Central Lab) avec Xlr(Swiss) = Impossible..";
	$InsererDansBD  = false;
}

if ($ORDER_PRODUCT_COATING=='StressFree'){
	//Produit impossible avec XLR, on doit afficher l'erreur 
	$ErrorDetail.=" Promo Duo HD (Central Lab) avec StressFree(Swiss) = Impossible..";
	$InsererDansBD  = false;
}

if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
}//Fin si aucun corridor n'a été fournis	

$ProdName  = "  (product_name like '%Promo duo 4K%') AND product_name NOT LIKE '%4D%' AND lens_category <> 'sv'"; 
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
$SauterValidationFH = "";
if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
			case '13': $ProdName  .= " AND corridor = 13 "; $SauterValidationFH = "yes"; break;    
			case '15': $ProdName  .= " AND corridor = 15 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

		
		
case 'PROGRESSIF DUO INDIVIDUALISE': //HKO
case 'PROGRESSIF DUO INDIVIDUALISE 4D':
case 'DUO INDIVIDUALIZED': //Halifax
//$ProdName  = "  (product_name like '%Promo Duo Alpha 4D%' OR  product_name like '%Promo prog Ind%')";
$ProdName  = "  (product_name like '%Promo Duo Alpha 4D%')";
if ($ORDER_PRODUCT_COATING=='HD AR'){
	if ($DESIGN == ''){
		$DESIGN = 'quotidien';
	}	
$ProdName  = "  product_name like '%Promo Duo Alpha 4D%' AND product_name not like '%thin%'";
}
		
		
if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br>';
}//Fin si aucun corridor n'a été fournis			

if ($ORDER_PRODUCT_COATING=='Xlr'){
//Produit impossible avec XLR, on doit afficher l'erreur 
$ErrorDetail.=" Alpha 4d (Central Lab ou Essilor) avec Xlr(Swiss) = Impossible..";
$InsererDansBD  = false;
}
$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
$SauterValidationFH = "";
if ($CORRIDOR <> ''){
		//Produit HKO + corridor = On doit filtrer le corridor avec le code produit
		switch($CORRIDOR){
			case '5':  $ProdName  .= " AND corridor = 5 ";  $SauterValidationFH = "yes"; break; 
			case '7':  $ProdName  .= " AND corridor = 7 ";  $SauterValidationFH = "yes"; break;    
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;    
		}	
	}elseif ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

//////////////////////////////////////////////FIN HKO




case 'PRECISION+ LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name NOT like '%polarized%' AND product_name not like '%transition%'
AND product_name not like '%360%' AND product_name not like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ DRIVEWEAR LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%drivewear%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%' ";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ DRIVEWEAR LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%drivewear%' AND product_name not like '%360%' AND product_name  LIKE '%2ieme%' ";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ POLAR BROWN LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%polarized Brown%' AND product_name  NOT LIKE '%2ieme%' AND product_name not like '%360%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ POLAR BROWN LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%polarized Brown%' AND product_name   LIKE '%2ieme%' AND product_name not like '%360%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;





case 'PRECISION+ POLAR GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%polarized Grey%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ POLAR GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name like '%polarized Grey%' AND product_name not like '%360%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case '2ND PRECISION+ LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name NOT like '%polarized%' AND product_name not like '%transition%' AND product_name not like '%360%' AND product_name not like '%420%'
AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ TRANS BROWN LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%transition%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ TRANS BROWN LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%transition%' AND product_name not like '%360%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ TRANS GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%transition%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ TRANS GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%transition%' AND product_name not like '%360%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ UV420 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name NOT like '%transitions%' AND product_name like '%420%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ UV420 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name NOT like '%transitions%' AND product_name like '%420%' AND product_name not like '%360%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ XTRACTIVE GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%Xtractive%' AND product_name NOT like '%420%' AND product_name not like '%360%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ XTRACTIVE GREY LOW REF':
$ProdName  = "  product_name like '%Precision+%' AND product_name  like '%Xtractive%' AND product_name NOT like '%420%' AND product_name not like '%360%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;





case 'PRECISION+ 360 DRIVEWEAR LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name  like '%drivewear%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 POLAR BR LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%polarized brown%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;





case '2ND PRECISION+ 360 POLAR BR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%polarized brown%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 POLAR GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%polarized Grey%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case '2ND PRECISION+ 360 POLAR GREY LOW REF':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%polarized Grey%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case '2ND PRECISION+ 360 DRIVEWEAR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name  like '%drivewear%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name not like '%polarized Grey%' AND product_name not like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name not like '%polarized Grey%' AND product_name not like '%420%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case 'PRECISION+ 360 TRANS BR LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%transition%' AND product_name not like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 TRANS BR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%transition%' AND product_name not like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 TRANS GREY LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%transition%'
AND product_name not like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING = "Low Reflexion";
$ORDER_PRODUCT_PHOTO   = 'Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 TRANS GREY LOW REF':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name like '%transition%'
AND product_name not like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING = "Low Reflexion";
$ORDER_PRODUCT_PHOTO   = 'Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ 360 UV420 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name not like '%transition%' 
AND product_name  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
	
break;



case '2ND PRECISION+ 360 UV420 LOW REFLEXION':
$ProdName  = "  product_name like '%Precision+ 360%' AND product_name not like '%360 active%' AND product_name not like '%transition%' 
AND product_name  like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
	
break;



case 'PRECISION+ 360 ACTIVE DRIVEWEAR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%' AND product_name  like '%drivewear%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%' ";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 ACTIVE DRIVEWEAR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%' AND product_name  like '%drivewear%' AND product_name NOT  like '%420%' AND product_name   LIKE '%2ieme%' ";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 ACTIVE POL GREY LOW REF':
case 'PRECISION+ 360 ACTIVE POLAR GR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized grey%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;






case '2ND PRECISION+ 360 ACTIVE POL GREY LR':
case '2ND PRECISION+ 360 ACTIVE POLAR GR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized grey%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ 360 ACTIVE POLAR BR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized brown%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 ACTIVE POLAR BR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized brown%' AND product_name NOT  like '%420%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Brown';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case 'PRECISION+ 360 ACTIVE POL GREY LOW REF':
case 'PRECISION+ 360 ACTIVE POLAR GR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized grey%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;






case '2ND PRECISION+ 360 ACTIVE POL GREY LR':
case '2ND PRECISION+ 360 ACTIVE POLAR GR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%polarized grey%' AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_POLAR='Grey';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ 360 ACTIVE LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name NOT like '%polarized%' AND product_name not like '%transitions%' 
AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case '2ND PRECISION+ 360 ACTIVE LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name NOT like '%polarized%' AND product_name not like '%transitions%' 
AND product_name NOT  like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;




case 'PRECISION+ 360 ACTIVE TRANS BR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%transition%'  AND product_name NOT  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO="Brown";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ 360 ACTIVE TRANS BR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%transition%'  AND product_name NOT  like '%420%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$ORDER_PRODUCT_PHOTO="Brown";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case '2ND PRECISION+ 360 ACTIVE TRANS GR LR':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
		$InsererDansBD = false;	
		$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
		}
}//END Switch

	//Partie commune
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$SauterValidationFH = "";
	//Paramètres propre à ce produit seulement
	$ProdName  = "  product_name like '%Precision+ 360 Active%'  AND product_name not like '%promo%'  AND product_name  LIKE '%2ieme%' AND product_name  LIKE '%pair%' and product_name like '%transition%' "; 
	$ORDER_PRODUCT_COATING	= "Low Reflexion";
	$ORDER_PRODUCT_POLAR	= "None";
	$ORDER_PRODUCT_PHOTO	= "Grey";
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor is mandatory for this product. Please add the corridor and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	if (($CORRIDOR == '') || ($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;	



case 'PRECISION+ 360 ACTIVE UV420 LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 ACTIVE UV420 LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%'  AND product_name  like '%420%' AND product_name  LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case 'PRECISION+ 360 ACTIVE XTRACT GR LOW REF':
$ProdName  = "  product_name like '%Precision+ 360 active%' and product_name like '%xtractive%'  AND product_name NOT like '%420%' AND product_name  NOT LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;



case '2ND PRECISION+ 360 ACTIVE XTRACT GR LR':
$ProdName  = "  product_name like '%Precision+ 360 active%' and product_name like '%xtractive%'  AND product_name NOT like '%420%' AND product_name   LIKE '%2ieme%'";
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Low Reflexion";
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
	
	if ($CORRIDOR == ''){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit. Svp ajouter le corridor et re-exporter la commande.<br> 
		The corridor  is mandatory for this product. Please add a corridor  and re-export the order.';
	}//Fin si aucun corridor n'a été fournis	
	
	switch($CORRIDOR){
			case '7': 	$ProdName  .= " AND corridor = 7 "; 	$SauterValidationFH = "yes"; break;    
			case '9': 	$ProdName  .= " AND corridor = 9 "; 	$SauterValidationFH = "yes"; break;    
			case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;     
	}//END SWITCH	
break;


case 'PROGRESSIF HD IOT ARMOUR420 MAXIIVUE':
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

$ProdName  = "  product_name like '%Progressif HD IOT Freeform%' AND product_name like '%armour 420%'"; 	

if (($ORDER_PRODUCT_INDEX=='1.60') || ($ORDER_PRODUCT_INDEX=='1.59')){
	$PolarLowercase = strtolower($ORDER_PRODUCT_POLAR);
		if ($PolarLowercase<>'none'){
		//Un polarisé a été demandé,  afficher le message d'erreur qui correspond car non disponible	
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le  produit <b>Progressif HD IOT</b> <u> est indisponible</u> en polarisé dans les indices 1.59 et 1.60.<br>The product <b>HD IOT</b> <u> is not available</u> 
		in Polarized in the following index:1.59 and 1.60.';
		}
}//End IF



if ($CORRIDOR == ''){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (11-13 ou 15mm). Svp ajouter le corridor et re-exporter la commande.<br><br> The corridor is mandatory for this product. 
	Please add the corridor (11-13 or 15mm) and re-export the order.';
}//Fin si aucun corridor n'a été fournis	

if ($CORRIDOR == 9){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (11-13 ou 15mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product. 
	Please add the corridor (11-13 or 15mm) and re-export the order.';
}//Fin si aucun corridor n'a été fournis	

if ($CORRIDOR <> ''){
	switch($CORRIDOR){
		case '9':  $ProdName  .= " AND (corridor = 9  OR corridor='11-13-15') ";  $SauterValidationFH = "yes"; break;    
		case '11': $ProdName  .= " AND (corridor = 11 OR corridor='11-13-15') ";  $SauterValidationFH = "yes"; break; //Ex:   
		case '13': $ProdName  .= " AND (corridor = 13 OR corridor='11-13-15') ";  $SauterValidationFH = "yes"; break; //Ex:
		case '15': $ProdName  .= " AND (corridor = 15 OR corridor='11-13-15') ";  $SauterValidationFH = "yes"; break; //Ex:
	}
}

$ORDER_PRODUCT_COATING='Xlr';//Hard Codé
$ProdTable = "ifc_ca_exclusive"; 
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'FREEDOM HD':   //HBC = PREMIUM PROGRESSIVE PACKAGE = Corridor 8, 10, 12, 14  qui correspondent à MFH: 15, 17, 19, 21mm.

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD XTR%' AND product_name NOT like '%package%' $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD XTR%' AND product_name NOT like '%package%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'FREEDOM HD HC':   //HBC = REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis



$ORDER_PRODUCT_COATING='Hard Coat';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'  $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;





case 'FREEDOM HD AR+ETC':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor IN ('11') "; break;
		case '20MM': 	$Lecorridor = " AND corridor IN ('13') "; break;
		case '22MM': 	$Lecorridor = " AND corridor IN ('15') "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING='SPC';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'  $Lecorridor   $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'FREEDOM HD HD AR':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING='HD AR';

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'FREEDOM HD PHOTOSUN GREY HC':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis



$ORDER_PRODUCT_COATING ='Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'Grey';



$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'FREEDOM HD PHOTOSUN BROWN HC':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING ='Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'Brown';

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'FREEDOM HD PHOTOSUN GREY AR+ETC':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

	$ORDER_PRODUCT_COATING = 'SPC';
	$ORDER_PRODUCT_PHOTO   = 'Grey';

	$ConditionHighImpact= " ";
	if ($IIMPACT<>''){
		$ConditionHighImpact= " AND product_name like '%High Impact%' ";
	}//FIN SI EXTRA IIMPACT

	if (($RE_HEIGHT==0)&&($LE_HEIGHT==0)){
		$ErrorDetail   .=" The fitting heights are incorrect.";
		$InsererDansBD  = false;	
	}//End IF
	

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%' $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'  $Lecorridor $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'FREEDOM HD PHOTOSUN BROWN AR+ETC':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'Brown';

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'    $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'FREEDOM HD PHOTOSUN GREY HD AR':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING = 'HD AR';
$ORDER_PRODUCT_PHOTO   = 'Grey';

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'   $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'   $Lecorridor  $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'FREEDOM HD PHOTOSUN BROWN HD AR':   //HBC= REGULAR PACKAGE = DISPO AVEC CORRIDOR 11/13/15 = MFH de 18,20,22mm

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ORDER_PRODUCT_COATING = 'HD AR';
$ORDER_PRODUCT_PHOTO   = 'Brown';

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD%'  $Lecorridor $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD%'  $Lecorridor $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'FREEDOM HD TINTED GREY AR+ETC':   //HBC

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD XTR%' and product_name like '%tinted grey%' $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD XTR%'  and product_name like '%tinted grey%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;



case 'FREEDOM HD TINTED BROWN AR+ETC':   //HBC

if (($MFH != '18MM') && ($MFH != '20MM') && ($MFH != '22MM')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>The MFH (18mm-20mm-22mm) is mandatory for this product. Please add the Minimum Fitting Height (18, 20 or 22mm) with the menu Minimum Fitting Height in Optipro, save and  re-export the order.';
}else{
		$Lecorridor=" AND 91=91 ";
		switch($MFH){
		case '18MM': 	$Lecorridor = " AND corridor=11 "; break;
		case '20MM': 	$Lecorridor = " AND corridor=13 "; break;
		case '22MM': 	$Lecorridor = " AND corridor=15 "; break;
		}//END SWITCH
}//Fin si aucun MFH n'a été fournis

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD XTR%' $ConditionHighImpact and product_name like '%tinted brown%'  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = "  product_name like '%Freedom HD XTR%' $ConditionHighImpact and product_name like '%tinted brown%'  AND product_name not like '%armour 420%'"; 	
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;




case 'FREEDOM HD POLARIZED GREY AR+ETC':   //HBC
$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD XTR%' and product_name like '%polarized grey%' $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD XTR%'  and product_name like '%polarized grey%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable 			   = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR   = 'Grey';
$ORDER_PRODUCT_COATING = 'SPC';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'FREEDOM HD POLARIZED BROWN AR+ETC':   //HBC

$ConditionHighImpact= " ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra Armour 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%Freedom HD XTR%' $Lecorridor and product_name like '%polarized brown%' $ConditionHighImpact  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%Freedom HD XTR%'  and product_name like '%polarized brown%' $ConditionHighImpact AND product_name not like '%armour 420%'"; 	
}

$ProdTable 			   = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR   = 'Brown';
$ORDER_PRODUCT_COATING = 'SPC';
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'PROG HD POL BRUN AR BACK':
case 'PROG HD POL BROWN AR BACK':
case 'NUM POL BRUN AR BACK':
case 'NUM POL BROWN AR BACK':
$design="Exterieur";
switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized brown%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_POLAR 	= 'Brown';
$ORDER_PRODUCT_COATING	= "AR Backside";
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '7': $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;  
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<>'') && ($LE_HEIGHT<>'')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;


case 'PROGRESSIF HD IOT':   //Swiss / GKB / HKO(5%)
case 'HD IOT':   //Halifax et HBC

if ($ORDER_PRODUCT_PHOTO=='Green'){
	$ErrorDetail   .=" Transitions Green is not available with the HD IOT";
	$InsererDansBD  = false;	
}

if (($ORDER_PRODUCT_INDEX=='1.5') && ($ARMOUR420=='armour 420')){
	$ErrorDetail   .=" The extra UV 420 is not available in index 1.5";
	$InsererDansBD  = false;	
}//End IF

if ((strtolower($ORDER_PRODUCT_POLAR)<>'none') && ($ORDER_PRODUCT_INDEX<>'1.50')){
	$ErrorDetail   .=" The product HD IOT with option Polarized is only available in index 1.50";
	$InsererDansBD  = false;	
}

if (($ORDER_PRODUCT_INDEX=='1.53') || ($ORDER_PRODUCT_INDEX=='1.59')){
	$ErrorDetail   .=" HD IOT is not available  in 1.53, 1.59 index";
	$InsererDansBD  = false;	
}


if ($ARMOUR420=='armour 420'){
	$ProdName  = "  product_name like '%HD IOT%' and product_name like '%HD IOT-G%'  AND product_name like '%armour 420%'"; 	
}else{
	$ProdName  = " product_name like '%HD IOT%' AND product_name not like '%armour 420%'"; 	
}


if ($ORDER_PRODUCT_COATING=='HD AR'){
	//Produit impossible avec HD AR
	$ErrorDetail.=" PROGRESSIF HD IOT (Swiss/Essilor Lab) avec HD AR(Central Lab) = Impossible..";
	$InsererDansBD  = false;
}

$ProdTable = "ifc_ca_exclusive"; 
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;

case 'PROGRESSIF ADVANCE HD AR':
	$ProdName  				= " product_name like '%Progressif Advance%' and product_name NOT like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "HD AR";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}//End Switch
break;



case 'PROGRESSIF ADVANCE':

	if ($ORDER_PRODUCT_COATING=='Xlr'){
		//Produit impossible avec XLR, on doit afficher l'erreur ici
		$ErrorDetail.="  Progressif Advance(Central Lab)  avec Xlr(Swiss) = Combinaison impossible. <br>Progressive Advance(Central Lab) with Xlr(Swiss) = Impossible combination.";
		$InsererDansBD  = false;
	}
	
	if ($ORDER_PRODUCT_COATING=='Low Reflexion'){
		//Produit impossible avec Low Reflexion, on doit afficher l'erreur ici
		$ErrorDetail.="  Progressif Advance(Central Lab)  avec Low Reflexion(Essilor) = Combinaison impossible. <br>Progressive Advance(Central Lab) with Low Reflexion(Essilor) = Impossible combination.";
		$InsererDansBD  = false;
	}
	
	if ($UV400<>''){
		$ProdName  				= " (product_name like '%Progressif Advance%' AND product_name like '%420%')"; 
	}else{
		$ProdName  				= " (product_name like '%Progressif Advance%' AND product_name not like '%420%')"; 
	}
	
	
	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '5':  	$ProdName  .= " AND corridor = 5  "; 	$SauterValidationFH = "yes"; break;    
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (5, 7 ,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options:5, 7, 9, 11mm. Please add the corridor and re-export the order.';
	}
break;



case 'PROGRESSIF ADVANCE TRANS GREY AR+ETC':
	$ProdName  				= " product_name like '%Progressif Advance%' and product_name NOT like '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO	= "Grey";
	$ORDER_PRODUCT_COATING	= "Dream AR";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}//End Switch
break;





case 'PROG HD POL GRIS HC': 
case 'PROG HD POL GREY HC':

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

if (($CORRIDOR == '5') || ($CORRIDOR == '13') || ($CORRIDOR == '15') || ($CORRIDOR == '')) {
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7, 9 ou 11). Svp ajouter le corridor (7-9-11) et re-exporter la commande.<br> 
		The corridor (7-9-11) is mandatory for this product. Please add a corridor (7, 9 or 11) and re-export the order.';
}//Fin si aucun corridor n'a été fournis

if ($ORDER_PRODUCT_COATING =='AR+ETC'){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Ce produit polarisé est uniquement disponible avec AR+ETC Face Interne ou Hard Coat Svp faites la modification nécessaire et ré-exporter la commande.<br>This polarized 
		product is only available in Hard Coat or AR Backside<br>';
		
}//Fin si aucun corridor n'a été fournis	

$ProdName  = "  product_name like '%promotion progressif%' AND  product_name like '%polarized grey%'"; 
$ProdTable = "ifc_ca_exclusive"; 
$ORDER_PRODUCT_COATING ="Hard Coat";
$ORDER_PRODUCT_POLAR = 'Grey';
if ($CORRIDOR <> ''){
		switch($CORRIDOR){
			case '9':  $ProdName  .= " AND corridor = 9 ";  $SauterValidationFH = "yes"; break;    
			case '11': $ProdName  .= " AND corridor = 11 "; $SauterValidationFH = "yes"; break;  
			case '7': $ProdName  .= " AND corridor = 7 "; $SauterValidationFH = "yes"; break;   
		}
}
$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
if (($EYE == 'Both') && ($RE_HEIGHT<> '') && ($LE_HEIGHT<> '')){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;



case 'SV SURFACE POL BRUN HC': 
case 'SV SURFACE POL BROWN HC':
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_POLAR='Brown';
	$ORDER_PRODUCT_COATING=	"Hard Coat";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	


case 'SV SURFACE POL BRUN AR BACK': 
case 'SV SOL SURFACE POL. BRUN ETC BACK':
case "SV SURFACE POL BROWN AR BACK":
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized brown%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_POLAR='Brown';
	$ORDER_PRODUCT_COATING=	"AR Backside";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	



case 'SV SURFACE TEINTE GRIS 85% AR BACK': 
case 'SV SURFACE TINTED GREY 85% AR BACK':
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%tinted grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING="AR Backside";
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
$AjoutTeintePromo = 'oui';
//Ajouter l'extra teinte immédiatement mais sans ajouter de montant associé car déja inclus dans le prix
//Inserer dans extra_product_order
$frame_type		= "";
$color			= "";
$order_type		= "";
$temple			= "";
$order_num		= -1;
$main_lab_id	= $LAB;
$category		= "Tint";
$price          = 0; //0 car déja inclus dans le prix du produit 'promotion'
$ep_prod_id     = $listItem[prod_id];
$TINT           = 'Solid';
$TINT_COLOR     = 'Grey';
$FROM_PERC      = '85';
$TO_PERC        = '85';	
break;


//Nouveau Ifree Plus Advance 2021-01-26
case 'IFREE PLUS ADVANCE STRESSFREE':
	$ProdName  				= " product_name like '%Ifree Plus Advance%' and product_name  NOT LIKE '%UV420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "StressFree";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}
break;


case 'IFREE PLUS ADVANCE':

	if ($ARMOUR420=='armour 420'){
			$ProdName  				= " product_name like '%Ifree Plus Advance%' AND product_name     like '%420%'";	
	}else{
			$ProdName  				= " product_name like '%Ifree Plus Advance%' AND product_name not like '%420%'"; 	
	}

	$ProdTable 				= "ifc_ca_exclusive"; 
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}
break;





case 'SV SURFACE POL GRIS HC': 
case 'SV SURFACE POL GREY HC':
	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ORDER_PRODUCT_POLAR='Grey';
	$ORDER_PRODUCT_COATING="Hard Coat";
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	


case 'FORFAIT SIMPLE VISION LOW REFLEXION': //GKB
case 'LOW REFLEXION SV PACKAGE': //Halifax	
case 'STOCK SIMPLE VISION LOW REFLEXION': //GKB
	//Si Stock + polarisé = impossible, on affiche l'erreur
	if ($ORDER_PRODUCT_POLAR<>'None'){
		$ErrorDetail   .=" On ne peut pas ajouter une option Polarise sur un verre de stock.  It is not possible to add a polarized option on a stock lense.";
		$InsererDansBD  = false;
	}

	$RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%single vision stock%' AND coating IN ('Low Reflexion')"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'Low Reflexion';
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}	
break;		


case 'SV SURFACE POL GRIS AR BACK':
case 'SV SURFACE POL GREY AR BACK':

	$ProdName  = "  product_name like '%promotion single vision%' AND product_name  like '%polarized grey%'"; 	
	$RE_ADD = 0;
	$LE_ADD = 0; 
	$ORDER_PRODUCT_POLAR='Grey';
	$ORDER_PRODUCT_COATING="AR Backside";
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
			$OPTICAL_CENTER = $LE_HEIGHT;
			$LE_HEIGHT = '';
			$RE_HEIGHT = '';	
	}
break;	


case 'SECURITE PROGRESSIF NUMERIQUE HD': //HKO /GKB
case 'SECURITE PROGRESSIF NUMERIQUE HD ': 
case 'SECURITE NUM. PROGRESSIVE': //HALIFAX		

if ($ORDER_PRODUCT_COATING=='Xlr'){
//Produit impossible avec XLR, on doit afficher l'erreur 
$ErrorDetail.=" SECURITE PROGRESSIF NUMERIQUE HD (Central Lab ou Essilor) avec Xlr(Swiss) = Impossible..";
$InsererDansBD  = false;
}
		
$ProdName  = "  product_name like '%Digital Progressive%'"; 
$ProdTable = "safety_exclusive"; 
$CollectionNotIn = "   ";
if ($EYE == 'Both'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";
	}elseif($EYE == 'R.E.'){
		$ProdHeight = " AND min_height <= $RE_HEIGHT AND max_height >= $RE_HEIGHT ";	
	}elseif($EYE == 'L.E.'){
		$ProdHeight = " AND min_height <= $LE_HEIGHT AND max_height >= $LE_HEIGHT ";
	}
break;




case 'SV RX': //
$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT


if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name like '%420%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name not like '%420%'"; 
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX HC': 
$ORDER_PRODUCT_COATING='Hard Coat';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name like '%armour%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name not like '%armour%'"; 
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX AR+ETC': 
$ORDER_PRODUCT_COATING='SPC';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name like '%420%'"; 
	}else{
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name not like '%420%'"; 
	}

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX MAXIVUE II': 
$ORDER_PRODUCT_COATING='Maxivue2';

$ConditionHighImpact= " AND product_name NOT like '%High Impact%'  ";
if ($IIMPACT<>''){
	$ConditionHighImpact= " AND product_name like '%High Impact%' ";
}//FIN SI EXTRA IIMPACT

if ($ARMOUR420 == 'armour 420'){
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name like '%armour%'"; 
}else{
		$ProdName  = "  product_name like '%Single Vision Lense%' $ConditionHighImpact AND product_name not like '%armour%'"; 
}
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'SV RX HIGH IMPACT MAXIVUE II': 
$ORDER_PRODUCT_COATING='Maxivue2';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX TRANS GREY HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX TRANS GREY AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX TRANS GREY MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX TRANS BROWN MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX TRANS GREY HIGH IMPACT MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX DRIVEWEAR MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'Drivewear';
$ORDER_PRODUCT_POLAR   = 'Drivewear';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'SV RX POLARIZED GREY HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX POLARIZED BROWN HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'SV RX POLARIZED GREY AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX POLARIZED BROWN AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'SV RX POLARIZED GREY HIGH IMPACT HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX POLARIZED BROWN HIGH IMPACT HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX POLARIZED GREY HIGH IMPACT AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX POLARIZED BROWN HIGH IMPACT AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX POLARIZED GREY HIGH IMPACT MAXI2': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Grey';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;




case 'FT28 AR+ETC': //STC / HKO / GKB //MEME POUR HALIFAX

switch($EYE){	
	case 'Both': 
		if ($RE_ADD=='' || $LE_ADD==''){
			if ($UnSvUnProg == false){	
				$InsererDansBD = false;	
				$ErrorDetail.= '<br>Il manque vos additions. Your additions are missing '.' <br>';
			}
		}	
}//END Switch

	/*if (($ORDER_PRODUCT_INDEX=='1.60') && ($ORDER_PRODUCT_PHOTO=='Grey')){
		$ErrorDetail.=" Le produit FT28 n\'est pas disponible en transitions dans l\'indice 1.60. Vous pourriez l''avoir en 1.59.<br> The product FT28 is not available in Transitions 1.60. You could get it in 1.59.";
		$InsererDansBD  = false;
	}elseif(($ORDER_PRODUCT_INDEX=='1.60') && ($ORDER_PRODUCT_PHOTO=='Brown')){
		$ErrorDetail.=" Le produit FT28 n\'est pas disponible en transitions dans l\'indice 1.60. Vous pourriez l''avoir en 1.59.<br> The product FT28 is not available in Transitions 1.60. You could get it in 1.59.";
		$InsererDansBD  = false;
	}//End IF*/

	
	$ProdName  = "  product_name like '%FT28%'"; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING='Dream AR';
break;


case 'SV RX POLARIZED BROWN HIGH IMPACT MAXI2': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'None';
$ORDER_PRODUCT_POLAR   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'SV RX AR420 HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%420%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX AR420 AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%420%' AND product_name"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX AR420 MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%420%' AND product_name not like '%high%' AND product_name"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'AFFO SV RX AR':		
	$ProdName  = "  product_name like '%AFFORDABLE%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'ORBIT SV RX AR PHOTOSUN BROWN':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO ='Brown';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	


case 'ORBIT SV RX AR PHOTOSUN GREY':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_PHOTO ='Grey';
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	




case 'ORBIT SV RX AR':		
	$ProdName  = "  product_name like '%ORBIT%' AND product_name not like '%stock%' AND product_name not like '%PROG%' AND product_name not like '%tint%' "; 	
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	$ORDER_PRODUCT_COATING = 'ITO AR';
	$RE_ADD = 0;
	$LE_ADD = 0;
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;	



case 'SV RX AR420 HIGH IMPACT MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%420%' AND product_name  like '%High Impact%' AND product_name"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

case 'IFREE PLUS ADVANCE UV420 MAXIIVUE':
	$ProdName  				= " product_name like '%Ifree Plus Advance%' and product_name like '%420%'"; 
	$ProdTable 				= "ifc_ca_exclusive"; 
	$ORDER_PRODUCT_COATING 	= "Maxivue2";
	$CollectionNotIn       	= " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	switch($CORRIDOR){
		case '7':  	$ProdName  .= " AND corridor = 7  "; 	$SauterValidationFH = "yes"; break;    
		case '9': 	$ProdName  .= " AND corridor = 9  "; 	$SauterValidationFH = "yes"; break;    
		case '11': 	$ProdName  .= " AND corridor = 11 "; 	$SauterValidationFH = "yes"; break;    
		default: 
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>Le corridor est obligatoire pour ce produit (7,9 ou 11mm). Svp ajouter le corridor et re-exporter la commande.<br> The corridor is mandatory for this product.
		Options: 7, 9, 11mm. Please add the corridor and re-export the order.';
	}//End Switch
break;


case 'SV RX TRANS BROWN HIGH IMPACT MAXIVUE II': 
$ORDER_PRODUCT_COATING = 'Maxivue2';
$ORDER_PRODUCT_PHOTO   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name  like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX TRANS BROWN AR+ETC': 
$ORDER_PRODUCT_COATING = 'SPC';
$ORDER_PRODUCT_PHOTO   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;


case 'SV RX TRANS BROWN HC': 
$ORDER_PRODUCT_COATING = 'Hard Coat';
$ORDER_PRODUCT_PHOTO   = 'Brown';
$ProdName  = "  product_name like '%Single Vision Lense%' AND product_name NOT like '%High Impact%' AND product_name NOT like '%armour%'"; 

    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
		
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;



case 'SV RX ': //
    $RE_ADD = 0;
	$LE_ADD = 0;
	$ProdName  = "  product_name like '%Single Vision Mineral%' "; 
	$ProdTable = "ifc_ca_exclusive"; 
	$CollectionNotIn       = " AND collection NOT IN ('IFC Crystal', 'IFC CA Free','IFC Swiss','SV IFC','IFC SteCath','FT IFC','Optimize IFC','IFC Club','IFC SteCath','')";
	
	if ($ORDER_PRODUCT_COATING == 'AR+ETC')
	$ORDER_PRODUCT_COATING = 'MultiClear AR';
	
	if (($LE_HEIGHT >0) || ($RE_HEIGHT >0)){
		$OPTICAL_CENTER = $LE_HEIGHT;
		$LE_HEIGHT = '';
		$RE_HEIGHT = '';	
	}
break;

	

default: 
if (($PRODUCT_NAME_OPTIPRO<>'CUSTOMER LENS') && ($PRODUCT_NAME_OPTIPRO<>'LENTILLE DU CLIENT') && ($PRODUCT_NAME_OPTIPRO<>'')){
	echo '<br>Product not Product not created  yet'; 
	$ProduitIdentifier  = false;
	$InsererDansBD      = false;
	$ErrorDetail.= '<br>Product not created yet<br>';
}else{
	//Mean it's the customer lens
	echo 'Customer Lens,  therefore we dont import..';	
	$ProduitIdentifier  = false;
	$InsererDansBD      = false;
}
break;
	
//default: //echo '<br>Produit Optipro non reconnu:'. $PRODUCT_NAME_OPTIPRO; 
/*
$ProduitIdentifier  = false;
$InsererDansBD      = false;
$ErrorDetail.= '<br>Produit Optipro non reconnu:<b>('. $PRODUCT_NAME_OPTIPRO . ')</b><br>';
*/

}//End Switch

echo '<br><br>sorti while';
	
if ($OPTICAL_CENTER <> '')
$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' ' . 'OPTICAL CENTER:'. $OPTICAL_CENTER . 'mm ';

if ($BASE_CURVE <> '')
$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . ' ' . 'BASE CURVE:'. $BASE_CURVE ;


if ($CORRIDOR <> '') {
	$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . '  ' . 'Corridor:'. $CORRIDOR . 'mm ';
}elseif ($CORRIDOR <> ''){
	$INTERNAL_NOTE = $INTERNAL_NOTE . ' ' .   'Corridor:'. $CORRIDOR . 'mm ';	
}

if ($READING_DISTANCE <> '')
$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . '  ' . 'READING DISTANCE:'. $READING_DISTANCE;		

if ($INTERMEDIATE_DISTANCE <> '')
$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . '  ' . 'INTERMEDIATE DISTANCE:'. $INTERMEDIATE_DISTANCE;	

if ($AS_THIN_AS_POSSIBLE	 <> '')
$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS . '  ' . '***AS THIN AS POSSIBLE*** ';

if ($ORDER_FROM == 'safety'){
//Ajouter 'Industrial Thickness' si ce n'est pas déja dans l'instruction spéciale
$PositionIndustrialThickness = strpos(strtolower($INTERNAL_NOTE),'industrial thickness');
		if ($PositionIndustrialThickness == false) {
			$SPECIAL_INSTRUCTIONS   =  'Industrial Thickness. ' .  $SPECIAL_INSTRUCTIONS ;
		}
	
}//End IF SAFETY 	



//Section Info demandé par l'entrepot
$RxData .='
<table width="650" cellpadding="5"  cellspacing="0" border="1" class="TextSize">
	<tr><td align="center" bgcolor="#E4E4E4" colspan="16"><h2>REQUESTED PRODUCT DETAILS</h2></td></tr>
	<tr>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>NAME</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>INDEX</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>COATING</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PHOTO</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>POLAR</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>CORRIDOR</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>DESIGN</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TRACE</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>MIRROR</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>LENTICULAIRE</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>ARMOUR 420</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>IIMPACT</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>CODE REMISE OPTIPRO</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>ABC Warranty</strong></th>
	<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>MFH</strong></th>
	</tr>
	<tr>
	<td class="formCellNosides" align="center">'.$PRODUCT_NAME_OPTIPRO.'</td>
	<td class="formCellNosides" align="center">'.$ORDER_PRODUCT_INDEX.'</td>
	<td class="formCellNosides" align="center">'.$ORDER_PRODUCT_COATING.'</td>
	<td class="formCellNosides" align="center">'.$ORDER_PRODUCT_PHOTO.'</td>
	<td class="formCellNosides" align="center">'.$ORDER_PRODUCT_POLAR.'</td>
	<td class="formCellNosides" align="center">'.$CORRIDOR.'</td>
	<td class="formCellNosides" align="center">'.$DESIGN.'</td>
	<td class="formCellNosides" align="center">'.$TRACE.'</td>
	<td class="formCellNosides" align="center">'.$MIRROR .'</td>
	<td class="formCellNosides" align="center">'.$LENTICULAIRE .'</td>
	<td class="formCellNosides" align="center">'.$ARMOUR420 .'</td>
	<td class="formCellNosides" align="center">'.$IIMPACT .'</td>
	<td class="formCellNosides" align="center">'.$CODE_REMISE_OPTIPRO .'</td>
	<td class="formCellNosides" align="center">'.$WARRANTY .'</td>
	<td class="formCellNosides" align="center">'.$MFH .'</td>
	
	</tr>
</table><br>

<table width="950" cellpadding="2"  cellspacing="0" border="1" class="TextSize">
<tr><td align="center" bgcolor="#E4E4E4" colspan="16"><h2>INFO ENTREPOT</h2></td></tr>
<tr>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>USER ID</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>BASKET</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>LIMITE BASKET</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>MAIN LAB</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>LAB ID</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong># Optipro</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>EYE</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PATIENT</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>PATIENT REF</strong></th>
<th class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>OPTICIEN</strong></th>
</tr>';
$INTERDICTIONDEFFACER   = 'non';


//Validation cylindre positif
if ($EYE == 'Both'){
	if (($RE_CYL>0)||($LE_CYL>0)){
		$InsererDansBD  = false;
		$ErrorDetail.= '<br>All cylinders must be negative. There are positive cylinder(s) in your order, Please do the necessary to have it transformed into  a negative cyl and re-export the order.<br>';		
	}
}
		

//FILTRE JOBS BASKET
//Calculer combien de commandes dans le basket ce compte client à en ce momentà
$queryBasket  = "SELECT COUNT(order_num_optipro) as NbrOrderBasket FROM orders WHERE user_id = '$USER_ID' AND order_num = -1";
$resultBasket =  mysqli_query($con,$queryBasket)		or die  ('I cannot select items because  1: ' .$queryBasket  . mysqli_error($con));
$DataBasket   =  mysqli_fetch_array($resultBasket,MYSQLI_ASSOC);
$NbrJobBasket = $DataBasket[NbrOrderBasket];				  
$LimiteJobBasketAvantRenommage = 17;
if ($NbrJobBasket >= $LimiteJobBasketAvantRenommage){
	//On doit 1- NE pas importer le fichier csv, on doit plutot le renommer
	echo '<br>'. $NbrJobBasket . ': Trop de job dans le basket, on ne doit pas importer. Limite en place:'.  $LimiteJobBasketAvantRenommage .' <br>';
	$ESTUNEREPRISE   = 'oui';
	$INTERDICTIONDEFFACER   = 'oui';
	//2- Renommer le fichier csv afin qu'il soit traité en dernier (ordre alphabétique)	
	//ici
	$LongeurAncienNom = strlen($newest_file) -2;
	$NewName = substr($newest_file,0,2)	. 'z' . substr($newest_file,2,$LongeurAncienNom);
	$DeuxPremierCaracteres = substr($newest_file,2,10);
	//A TESTER
	if ($DeuxPremierCaracteres <> 'zzzzzzzzzz'){	
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
		ftp_pasv($conn_id,true);
		ftp_chdir($conn_id,"Optipro");
		$directory=ftp_pwd($conn_id);
		//Renommer le fichier en ajoutant un Z au début
		echo "Deux premiers caracteres: $DeuxPremierCaracteres "; 
		echo "<br>Ancien Nom:  $newest_file";
		echo "<br>Nouveau Nom:   $NewName";
		if (ftp_rename($conn_id,$newest_file,$NewName))
		echo "Renommage avec success de $newest_file en $Newname";
		else
		echo "Probleme durant le renommage de $newest_file en $Newname";	
		ftp_close($conn_id);
	}//END IF
}//End IF



$RxData .= '<tr>
<td class="formCellNosides" align="center">'.$USER_ID.'</td>
<td class="formCellNosides" align="center">'.$NbrJobBasket.'</td>
<td class="formCellNosides" align="center">'.$LimiteJobBasketAvantRenommage.'</td>
<td class="formCellNosides" align="center">'.$MAIN_LAB.'</td>
<td class="formCellNosides" align="center">'.$LAB.'</td>
<td class="formCellNosides" bgcolor="#C36F70" align="center">'.$OrderNumberOptipro.'</td>
<td class="formCellNosides" align="center">'.$EYE.'</td>
<td class="formCellNosides" align="center">'.$ORDER_PATIENT_FIRST  . ' ' . $ORDER_PATIENT_LAST.'</td>
<td class="formCellNosides" align="center">'.$PATIENT_REF_NUM.'</td>
<td class="formCellNosides" align="center">'.$OPTICIEN.'</td>
</tr>';
$RxData .= '</table><br>';


if ($SIDE_SHIELD == 'Cotes protecteurs securites')
$FRAME_MODEL = $FRAME_MODEL.'P';

$RxData .='<table width="950" cellpadding="2"  cellspacing="0" border="1" class="TextSize">
<tr><td align="center" bgcolor="#E4E4E4" colspan="11"><h2>FRAME DETAILS</h2></td></tr>
<tr>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>SUPPLIER</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>MODEL</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>COLOR</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>FRAME A</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>FRAME B</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>FRAME ED</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>FRAME DBL</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>TYPE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>KNIFE EDGE</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>SIDE SHIELD</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>BISEAUX POLIS</strong></td>
</tr>';
$RxData .= '<tr>
<td class="formCellNosides" align="center">'.$SUPPLIER.'</td>
<td class="formCellNosides" align="center">'.$FRAME_MODEL.'</td>
<td class="formCellNosides" align="center">'.$COLOR.'</td>
<td class="formCellNosides" align="center">'.$FRAME_A.'</td>
<td class="formCellNosides" align="center">'.$FRAME_B.'</td>
<td class="formCellNosides" align="center">'.$FRAME_ED.'</td>
<td class="formCellNosides" align="center">'.$FRAME_DBL.'</td>
<td class="formCellNosides" align="center">'.$FRAME_TYPE.'</td>
<td class="formCellNosides" align="center">'.$KNIFE_EDGE.'</td>
<td class="formCellNosides" align="center">'.$SIDE_SHIELD.'</td>
<td class="formCellNosides" align="center">'.$BISEAUX_POLIS.'</td>
</tr>';
$RxData .= '</table><br>';
echo '<br>' . $RxData;



if (str_replace(' ','',$EYE) == '')//Aucun oeil n'est demandé, erreur
{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>Aucun oeil sélectionne<b>('. $EYE . ')</b><br>';
}

//Pour ne pas traiter la ligne d'entête	
if ($OrderNumberOptipro <> 'ORDER NUMBER'){
		
		$ValidateLeft  = 'no';		
		$ValidateRight   = 'no';	
			
		if ($EYE == 'Both'){
			$ValidateLeft    = 'yes';
			$ValidateRight   = 'yes';		
		}
			
		if ($EYE == 'R.E.'){
			$ValidateLeft    = 'no';
			$ValidateRight   = 'yes';		
			$LE_ADD    = '';
			$LE_CYL    = '';
			$LE_SPHERE = '';
			$LE_HEIGHT = '';
			$LE_AXIS   = '';
			$LE_CT     = '';
			$LE_ET     = '';
			$LE_PD     = '';
			$LE_PD_NEAR= '';
			$LE_PR_AX  = '';
			$LE_PR_AX2 = '';
			$LE_PR_IO  = '';
			$LE_PR_UD  = '';
		}
		
		if ($EYE == 'L.E.'){
			$ValidateLeft    = 'yes';
			$ValidateRight   = 'no';	
			$RE_ADD    = '';
			$RE_CYL    = '';
			$RE_SPHERE = '';
			$RE_HEIGHT = '';
			$RE_AXIS   = '';
			$RE_CT     = '';
			$RE_ET     = '';
			$RE_PD     = '';
			$RE_PD_NEAR= '';
			$RE_PR_AX  = '';
			$RE_PR_AX2 = '';
			$RE_PR_IO  = '';
			$RE_PR_UD  = '';
		}
	
}//Fin IF (Pour ne pas traiter la ligne d'entête)		

//Quelques valeurs hardcodé
$ORDER_SHIPPING_METHOD = 'RX Shipping';
$CURRENCY 			   = 'CA';
$ORDER_QUANTITY 	   = 1;
$ORDER_PRODUCT_TYPE    = 'exclusive';			
$datedujour 		   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ORDER_DATE_PROCESSED  = date("Y-m-d", $datedujour); 
$ORDER_STATUS 		   = 'basket';
	
	

			//1- Valider que le numéro eyelation  n'est pas vide et pas deja importé dans NOTRE systeme
			if (($OrderNumberOptipro <> '') &&  ($InsererDansBD==true)) 
			{
				  $queryValiderPO  =  "SELECT order_num FROM orders WHERE LAB= 1 AND user_id = '$USER_ID' AND order_num_optipro = '$OrderNumberOptipro' AND order_status <> 'cancelled'";
				  echo '<br>query PO: ' . $queryValiderPO. '<br>' ;
				  $resultValiderPO =  mysqli_query($con,$queryValiderPO)		or die  ('I cannot select items because  1: ' .$queryValiderPO  . mysqli_error($con));
				  $nbrResultPO  	 =  mysqli_num_rows($resultValiderPO);
				  
			 
				  
				  if ($nbrResultPO > 0){
					 echo '<br>COMMANDE ' .	 $OrderNumberOptipro . ' Deja importee';
					 //$ErrorDetail.= '<br>Numero de facture Optipro <b>'. $OrderNumberOptipro. '</b>  a deja ete importee pour ce client<br>';
					 $InsererDansBD  = false;
				  }else{
				  $InsererDansBD  = true;// Po_Num inconnue, on peut importer la commande
				  // echo '<br>COMMANDE ' .	 $OrderNumberOptipro . ' prete pour etre  importee.'; 
				  }
			}else{
				if ($OrderNumberOptipro == ''){
				$ErrorDetail.= '<br>Le champ Numero de commande Optipro est vide, impossible de verifier si la commande a deja ete importee<br>';
				$InsererDansBD  = false;// Pas d'identifiant Eyelation ON NE PEUT PAS VALIDER SI LA COMMANDE A DEJA ETE RECUE
				echo '<br>Pas de <b>reference Optipro</b>, on ne peut pas valider si la commande a deja ete recu';
				}
			}//END IF EYELATION ORDER NUM IS NOT EMPTY
			
			
		
switch($DESIGN){
	case 'conduite':        $FiltreDesign = " AND product_name like '%conduite%' ";        break;
	case 'tout usage':      $FiltreDesign = " AND product_name like '%tout usage%' ";        break;
	case 'interieur':       $FiltreDesign = " AND product_name like '%interieur%' ";       break;
	case 'exterieur':       $FiltreDesign = " AND product_name like '%exterieur%' ";       break;
	case 'extérieur':       $FiltreDesign = " AND product_name like '%exterieur%' ";       break;
	case 'outdoor':     	$FiltreDesign = " AND product_name like '%exterieur%' ";       break;
	case 'lecture':         $FiltreDesign = " AND product_name like '%lecture%' ";         break;
	case 'premier porteur': $FiltreDesign = " AND product_name like '%premier porteur%' "; break;
	case 'quotidien':       $FiltreDesign = " AND product_name like '%quotidien%' ";       break;
	case 'ultra court':     $FiltreDesign = " AND product_name like '%Ultra court%' ";     break;
	case '':                $FiltreDesign = " AND product_name NOT like '%quotidien%'  AND product_name NOT like '%premier%'  AND product_name NOT like '%lecture%'  AND product_name NOT like '%exterieur%' AND product_name NOT like '%conduite%'  ";       break;
	default: $FiltreDesign = ' AND 9=9 ';	
}//End Switch	 

 //$FiltreDesign = "";
	
/*	
if ($FRAME_TYPE == 'OTHER'){
	$InsererDansBD  = false;
	$ErrorDetail.= '<br>Aucun design reconnu sélectionné dans Optipro.<br>';
}//End IF aucun type de monture sélectionné dans Optipro
*/
	
if ($USER_ID==''){
	$ErrorDetail.= '<br>Aucun USER ID.<br>';
	$InsererDansBD  = false;
}
	

switch($ORDER_PRODUCT_INDEX){
	case '1.5':  $ProdIndex  = " AND index_v = '1.50'";  break;	
	case '1.50': $ProdIndex  = " AND index_v = '1.50'";  break;
	case '1.52': $ProdIndex  = " AND index_v = '1.52'";  break;
	case '1.53': $ProdIndex  = " AND index_v = '1.53'";  break;	
	case '1.56': $ProdIndex  = " AND index_v = '1.56'";  break;	
	case '1.59': $ProdIndex  = " AND index_v = '1.59'";  break;
	case '1.6':  $ProdIndex  = " AND index_v = '1.60'";  break;
	case '1.60': $ProdIndex  = " AND index_v = '1.60'";  break;
	case '1.67': $ProdIndex  = " AND index_v = '1.67'";  break;
	case '1.70': $ProdIndex  = " AND index_v = '1.70'";  break;
	case '1.80': $ProdIndex  = " AND index_v = '1.80'";  break;
	case '1.90': $ProdIndex  = " AND index_v = '1.90'";  break;
	case '1.74': $ProdIndex  = " AND index_v = '1.74'";  break;	
	default:     $ProdIndex  = " AND 1 = 2 ";  $InsererDansBD  = false;
	
	if (($ImporterCetteCommande <> 'non')  && ($PRODUCT_NAME_OPTIPRO<>'LENTILLE DU CLIENT') && ($PRODUCT_NAME_OPTIPRO<>'CUSTOMER LENS') && ($PRODUCT_NAME_OPTIPRO<>'')){
		$ErrorDetail.= '<br>Aucun indice choisit pour la commande.<br>'; //De façon à ce qu'aucun produit soit dispo
	}
}//End Switch			
			

switch(strtoupper($ORDER_PRODUCT_PHOTO)){
	case 'BROWN':             $ProdPhoto  = " AND photo = 'Brown'";             break;	
	case 'DRIVEWEAR':         $ProdPhoto  = " AND photo = 'Drivewear'";         break;
	case 'EXTRA ACTIVE GREY': $ProdPhoto  = " AND photo = 'Extra Active Grey'"; break;
	case 'GREY':     		  $ProdPhoto  = " AND photo = 'Grey'";              break;
	case 'GREEN':     		  $ProdPhoto  = " AND photo = 'Green'";             break;
	case 'NONE':     	      $ProdPhoto  = " AND photo = 'None'";              break;
	default:     			  $ProdPhoto  = " AND 3 = 4";  			    	    //De façon à ce qu'aucun produit soit dispo	
}//End Switch

switch(strtoupper($ORDER_PRODUCT_POLAR)){
	case '':     	    	  $ProdPolar  = " AND polar = 'None'";      break;
	case 'GREY':     		  $ProdPolar  = " AND polar = 'Grey'";      break;
	case 'BROWN':             $ProdPolar  = " AND polar = 'Brown'";     break;	
	case 'GREEN': 			  $ProdPolar  = " AND polar = 'Green'";     break;	
	case 'DRIVEWEAR':         $ProdPolar  = " AND polar = 'Drivewear'"; break;
	case 'NONE':     	      $ProdPolar  = " AND polar = 'None'";      break;
	case 'G15':     		  $ProdPhoto  = " AND polar = 'Green'";     break;
	case 'G-15':     		  $ProdPhoto  = " AND polar = 'Green'";     break;
	default:     			  $ProdPolar  = " AND 5 = 6";  				break;//De façon à ce qu'aucun produit soit dispo	
}//End Switch

switch(strtoupper($ORDER_PRODUCT_COATING)){
	case 'HARD COAT':    				$ProdCoating  = " AND coating = 'Hard Coat'";       		 break;	
	case 'HDARIN': 		 				$ProdCoating  = " AND coating = 'HD AR Backside'";           break;	
	case 'AR BACKSIDE':  				$ProdCoating  = " AND coating IN ('SPC Backside','Super AR Backside','AR Backside')";     		 break;
	case 'SPC':   case 'DREAM AR':     	$ProdCoating  = " AND coating IN ('SPC','Dream AR','ITO AR')"; 		 break;
	case 'STRESSFREE':   				$ProdCoating  = " AND coating IN ('StressFree')"; 			 break;
	case 'AR+ETC':       				$ProdCoating  = " AND coating IN ('SPC','Dream AR','ITO AR','MultiClear AR')"; 	 break;
	case 'SPC BACKSIDE': 				$ProdCoating  = " AND coating IN ('SPC Backside','AR Backside')"; break;
	case 'MULTICLEAR AR': 				$ProdCoating  = " AND coating IN ('Multiclear AR')"; 		 break;
	case 'HD AR': 		 				$ProdCoating  = " AND coating = 'HD AR'";           		 break;	
	case 'XLR':     	 				$ProdCoating  = " AND coating = 'MaxiVue2'";        		 break;
	case 'XLR BACKSIDE': 				$ProdCoating  = " AND coating = 'MaxiVue2 Backside'"; 		 break;
	case 'XLR 2':     	 				$ProdCoating  = " AND coating = 'MaxiVue2'";          		 break;
	case 'XLR2':     					$ProdCoating  = " AND coating = 'MaxiVue2'";          		 break;
	case 'MAXIVUE2':     				$ProdCoating  = " AND coating = 'MaxiVue2'";          		 break;
	case 'XLR 2 BACKSIDE': 				$ProdCoating  = " AND coating = 'MaxiVue2 Backside'";        break;
	case 'ITO AR':		 				$ProdCoating  = " AND coating IN ('SPC','ITO AR')"; 		 break;
	case 'SUPER AR': 	 				$ProdCoating  = " AND coating = 'Super AR'"; 				 break;	
	case 'BLUCUT':		 				$ProdCoating  = " AND coating = 'BluCut'"; 					 break;	
	case 'SUPER AR BACKSIDE':			$ProdCoating  = " AND coating = 'Super AR Backside'"; 		 break;	
	case 'LOW REFLEXION':				$ProdCoating  = " AND coating = 'Low Reflexion'"; 			 break;	
	case 'LOW REFLEXION BACKSIDE':		$ProdCoating  = " AND coating = 'Low Reflexion Backside'"; 	 break;	
	case 'AR-ES':						$ProdCoating  = " AND coating = 'AR-ES'"; 		 			 break;	
	case 'SUN AR-ES':					$ProdCoating  = " AND coating = 'SUN AR-ES'"; 		 		 break;	
	

	default:     		 				$ProdCoating  = " AND 13 = 15";  echo '<br>COATING:'. $ORDER_PRODUCT_COATING;			        break;//De façon à ce qu'aucun produit soit dispo	
}//End Switch





	

if (($ProdTable == "ifc_ca_exclusive") &&  ($InsererDansBD))
$queryProduit  = "SELECT * FROM ". $ProdTable . " WHERE 
 $ProdName  $ProdIndex  $ProdPhoto $FiltreLenticular $FiltreDesign  $ProdPolar $CollectionNotIn  $ProdCoating $ProdHeight AND prod_status='active'  AND price_can <> 0 $FiltreAccountIsSubLicensee $FiltreLenticulaire";
elseif($InsererDansBD)
$queryProduit  = "SELECT * FROM ". $ProdTable . " WHERE 
$ProdName  $ProdIndex  $ProdPhoto $FiltreLenticular  $FiltreDesign  $ProdPolar $CollectionNotIn  $ProdCoating $ProdHeight AND prod_status='active'  $FiltreLenticulaire"; 

if (($InsererDansBD) && ($ProduitIdentifier)){
	echo '<br><br>'.$queryProduit . '<br><br>'; 
	$resultProduit =  mysqli_query($con,$queryProduit)		or die  ('I cannot select items because 2: ' .$queryProduit  . mysqli_error($con));
	$Resultat_Produit_Trouvé = mysqli_num_rows($resultProduit);
}



 echo '<br>Nombre de resultat trouves:'. $Resultat_Produit_Trouvé;

		 
		 if (($Resultat_Produit_Trouvé == 0) && ($InsererDansBD)) {
				echo '<br>Aucun produit identifi&eacute;:on STOP l\'importation.<br>';
				$InsererDansBD  = false;
				$ErrorDetail.= '<br><b>Product not found<b><br>';
		 }//End if aucun produit trouvés
		 

		   if ($Resultat_Produit_Trouvé > 1){
			  $Cles_Correspondante = '';
			  while ($DataCleTrouves  =  mysqli_fetch_array($resultProduit,MYSQLI_ASSOC)){
				$Cles_Correspondante .=  ' ' . $DataCleTrouves[primary_key]. ' '; 
			  }//End While
			
				$InsererDansBD  = false;
				$ErrorDetail.= $Resultat_Produit_Trouvé. ' produits correspondent &agrave; cette Rx.';
		 }//End if plusieurs produit trouvés
		 
	

	
	if (($Resultat_Produit_Trouvé == 1) && ($InsererDansBD)){
				echo '<br>Cle primaire trouvee, on continue l\'importation.<br>';
				$DataProduct =  mysqli_fetch_array($resultProduit,MYSQLI_ASSOC);
			
		switch($DataProduct[collection]){
				case 'HBC SWISS': 	 	$PRESCRIPT_LAB = 10; $FournisseurOriginal="SWISS"; break;//SWISS
				case 'HBC STC':		 	$PRESCRIPT_LAB =  3; $FournisseurOriginal="STC";   break;//Sainte-Catharines
				case 'HBC SURFACE':		$PRESCRIPT_LAB =  2; $FournisseurOriginal="HKO";   break;//HBC SURFACE = HKO
				case 'HBC STOCK':		$PRESCRIPT_LAB =  4; $FournisseurOriginal="GKB";   break;//HBC STOCK = GKB TODO AJOUTER LA CLÉ PRIMAIRE DU LAB QUE JE VAIS CRÉER POUR GKB
				case 'HBC KNR':			$PRESCRIPT_LAB =  73; $FournisseurOriginal="KNR";   break;
				default: $PRESCRIPT_LAB = 3;
			}
		echo '<br>PRESCRIPT LAB: '   . $PRESCRIPT_LAB ;	
			
			
		//Évaluer si le magasin fait partie des franchisés, si c'est le cas, on utilise le champ price_sublicensee au lieu de price_can	
		
		
		if ($AccountIsSubLicensee=="oui"){
			//Ce compte utilise les prix de sub-license	
			$price_can = $DataProduct[price_sublicensee];
			if (strtolower($EYE) <> 'both') 
			$price_can = $price_can/2;	
		}elseif (strtolower($EYE) <> 'both') 	
			$price_can = $DataProduct[price_can]/2;
		else
			$price_can = $DataProduct[price_can];

	
	
//Les miroirs utilisés seront UNIQUEMENT ceux de Swiss. Les HBC  utiliseront cet extra  uniquement sur les produits qu'on sait fabriqué par Swiss. Confirmé par Roberto 17 oct 2018	
switch ($MIRROR){//Si mirroir = Swiss, on change la collection pour Swiss.
						case 'MIRA':  $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRBB': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRC':  $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRD':  $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIROF': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRPG': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRPP': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRS':  $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRT':  $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRRG': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRRGO':$DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRROB':$DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRRR': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRRS': $DataProduct[collection] = 'HBC SWISS'; break;
						case 'MIRRY': $DataProduct[collection] = 'HBC SWISS'; break;
}	
		
		
//Si teinte Swiss, on redirige vers Swiss		
switch (strtoupper($TINT)){
	case 'SW001':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW001';    $TINT = 'SOLID'; break;
	case 'SW004':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW004';    $TINT = 'SOLID'; break;
	case 'SW007':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW007';    $TINT = 'SOLID'; break;
	case 'SW010':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW010';    $TINT = 'SOLID'; break;
	case 'SW012':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW012';    $TINT = 'SOLID'; break;
	case 'SW015':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW015';    $TINT = 'SOLID'; break;
	case 'SW023': 	 $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW023';    $TINT = 'SOLID'; break;
	case 'SW025':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW025';    $TINT = 'SOLID'; break;
	case 'SW026':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW026';    $TINT = 'SOLID'; break;
	case 'SW027/50': $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW027/50'; $TINT = 'SOLID'; break;
	case 'SW028':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW028';    $TINT = 'SOLID'; break;
	case 'SW030/50': $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW030/50'; $TINT = 'SOLID'; break;
	case 'SW032':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW032';    $TINT = 'SOLID'; break;
	case 'SW034':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW034';    $TINT = 'SOLID'; break;
	case 'SW035':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW035';    $TINT = 'SOLID'; break;
	case 'SW036':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW036';    $TINT = 'SOLID'; break;
	case 'SW046':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW046';    $TINT = 'SOLID'; break;
	case 'SW051':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW051';    $TINT = 'SOLID'; break;
	case 'SW054':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW054';    $TINT = 'SOLID'; break;
	case 'SW062':    $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'SW062';    $TINT = 'SOLID'; break;
	case 'GOL':      $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'GOL';      $TINT = 'Gradient'; break;
	case 'RAV':      $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'RAV';      $TINT = 'SOLID'; break;
	case 'TEN':      $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'TEN';      $TINT = 'SOLID'; break;
	case 'AZU':      $DataProduct[collection] = 'HBC SWISS'; $TINT_COLOR = 'AZU';      $TINT = 'SOLID'; break;
	
}	




//Si le fournisseur original est HKO, et qu'il y a un Miroir Swiss ou une Teinte Swiss, on doit reconfigurer afin d'être certain que ça aille vers le vrai fournisseur 
if ($FournisseurOriginal== "HKO"){
	$DataProduct[collection] = 'HBC SURFACE';	
}//END IF	

if ($FournisseurOriginal== "GKB"){
	$DataProduct[collection] = 'HBC STOCK';	
}//END IF		
			
$ProdData .='<table width="950" cellpadding="2"  cellspacing="0" border="1" class="TextSize">
<tr><td align="center" bgcolor="#E5E5E5" colspan="8"><h2>PRODUCT RANGE</h2></td></tr>
<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">Spheres</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[sphere_over_min]. '</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[sphere_over_max]. '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Product Name</td>
<td colspan="3" class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[product_name]. '</strong></td>
</tr>

<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">Cylinders</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[cyl_over_min] . '</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[cyl_max]. '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Product ID</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $DataProduct[primary_key]. '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">';

if ($AccountIsSubLicensee=="oui"){
$ProdData .= 'Sub Licensee Price';
}else{
$ProdData .= 'Price';	
}

$ProdData .='</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'. $price_can. '</strong></td>';
if ($AccountIsSubLicensee=="oui"){
$ProdData .='<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>'.$price_sublicensee. '</strong></td>';
}

$ProdData .='</tr>

<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">Fitting Height</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[min_height] .'</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[max_height] .'</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Index</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[index_v] . '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Coating</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[coating] . '</strong></td>
</tr>

<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">Additions</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[add_min] . '</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[add_max] . '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Lens Category</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong> ' .$DataProduct[lens_category] . '</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Photochromic</td>
<td colspan="2" class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong> '. $DataProduct[photo]. '</strong></td>
</tr>

<tr>
<td class="formCellNosides" bgcolor="#E5E5E5">Product Code</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[product_code] . '</strong></td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5">&nbsp;</strong></td>

<td class="formCellNosides" bgcolor="#E5E5E5">Polarized</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[polar] .'</strong></td>
<td class="formCellNosides" bgcolor="#E5E5E5">Collection</td>
<td class="formCellNosides" align="center" bgcolor="#E5E5E5"><strong>' .$DataProduct[collection] .'</strong></td>
</tr></table>';
		
		
		echo '<br>'. $ProdData;
				//ALLER COLLECTER LES DONNÉES DU PRODUIT SÉLECTIONNÉ AFIN DE FAIRE DES VALIDATIONS
				$SPHERE_MAX = $DataProduct[sphere_over_max];
				$SPHERE_MIN = $DataProduct[sphere_over_min];
				
				
				//VALIDATION FRAME
				if ($FRAME_A == ''){ 
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The frame measurement A is missing. This value is mandatory. Please add it in Optipro,save and re-export the order.<br>';
				}
				
				if ($FRAME_B == ''){ 
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The frame measurement B is missing. This value is mandatory. Please add it in Optipro,save and re-export the order.<br>';
				}
				
				if ($FRAME_ED == ''){ 
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The frame measurement ED is missing. This value is mandatory. Please add it in Optipro,save and re-export the order.<br>';
				}
				
				if ($FRAME_DBL == ''){ 
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The frame measurement DBL is missing. This value is mandatory. Please add it in Optipro,save and re-export the order.<br>';
				}
				
				
				
				//VALIDATION PDs
				switch($EYE){	
					case 'Both': 
					echo '<br>Both';
						if (($RE_PD == '') &&  ($RE_PD <> 0)){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The right eye PD is mandatory, it is currently empty.' . ' <br>';
						}	
						if (($LE_PD == '') &&  ($LE_PD <> 0)){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is mandatory, it is currently empty.' . ' <br>';
						}		
						if ($RE_PD<12){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The right eye PD is too low: '.$RE_PD . ' <br>';
						}
						if ($RE_PD>43){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The right eye PD is too high: ' .$RE_PD .' <br>';
						}	
						if ($LE_PD<12){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is too low '.$LE_PD . ' <br>';
						}
						if ($LE_PD>43){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is too high: ' .$LE_PD .' <br>';
						}	
								
					break;
								
					case 'L.E.': 
					echo '<br>L.E.';  
						if ($LE_PD == ''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is mandatory, it is currently empty. ' . ' <br>';
						}
						if ($LE_PD<19){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is too low: '.$LE_PD . ' <br>';
						}
						if ($LE_PD>39){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The left eye PD is too high: ' .$LE_PD .' <br>';
						}	
					break;
					
					case 'R.E.': 
					echo '<br>R.E.';
						if ($RE_PD == ''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The righ teye PD is mandatory, it is currently empty. ' . ' <br>';
						}	
						if ($RE_PD<19){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Le Right eye PD is too low: '.$RE_PD . ' <br>';
						}
						if ($RE_PD>39){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>The right eye PD is too high: ' .$RE_PD .' <br>';
						}	
					break;	
						
				}//End Switch Validation PDs
				
				
				
				//VALIDATION Axis
				switch($EYE){	
					case 'Both': 
						if (($RE_CYL<> '') && ($RE_AXIS == '') && ($RE_CYL <> '0.00')){//On a un cylindre mais pas d'axe = erreur
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Right axis is empty. ' . ' <br>';
						}
						
						if (($LE_CYL<> '') && ($LE_AXIS == '')){//On a un cylindre mais pas d'axe = erreur
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Left axis is empty. ' . ' <br>';
						}		
									
					break;
								
					case 'L.E.': 
						if (($LE_CYL<> '') && ($LE_AXIS == '') && ($LE_CYL <> '0.00')){//On a un cylindre mais pas d'axe = erreur
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Left axis is empty. ' . ' <br>';
						}
					break;
					
					case 'R.E.': 
						if (($RE_CYL<> '') && ($RE_AXIS == '') && ($RE_CYL <> '0.00')){//On a un cylindre mais pas d'axe = erreur
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Right axis is empty. ' . ' <br>';
						}
					break;	
						
				}//End Switch Validation Axis
				
				
					//VALIDATION PDs-->20 mars 2017
				switch($EYE){	
					case 'Both': 
						if ($RE_PD==''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Right eye PD is missing. ' . ' <br>';
						}
						
						if ($LE_PD==''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Left eye PD is missing. ' . ' <br>';
						}		
									
					break;
								
					case 'L.E.': 	
						if ($LE_PD==''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Left PD is empty. ' . ' <br>';
						}	
					break;
					
					case 'R.E.': 
						if ($RE_PD==''){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Right PD is empty. ' . ' <br>';
						}
					break;	
						
				}//End Switch Validation Axis
				
				
				//VALIDATION SPHERES
				if (($RE_SPHERE > $SPHERE_MAX) && ($ValidateRight =='yes'))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The right Sphere value (<b>'. $RE_SPHERE . '</b>) is higher than the maximum accepted:<b>'. $SPHERE_MAX. '</b><br>';
				}
				
				if (($RE_SPHERE < $SPHERE_MIN) && ($ValidateRight =='yes'))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The right Sphere value (<b>'. $RE_SPHERE . '</b>) is lower than the minimum accepted:  <b>'. $SPHERE_MIN. '</b><br>';
				}
				
				
				if (($LE_SPHERE > $SPHERE_MAX) && ($ValidateLeft =='yes'))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The left Sphere value (<b>'. $LE_SPHERE . '</b>)  is higher than the maximum accepted: <b>'. $SPHERE_MAX. '</b><br>';
				}
				
				if (($LE_SPHERE < $SPHERE_MIN) && ($ValidateLeft =='yes'))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>The left Sphere value (<b>'. $LE_SPHERE . '</b>) is lower than the minimum accepted: <b>'. $SPHERE_MIN. '</b><br>';
				}
			
				
				$CYL_MAX    = $DataProduct[cyl_max];
				$CYL_MIN    = $DataProduct[cyl_over_min];
				
				
				
			
				//VALIDATIONS CYLINDRES
			
				if (($RE_CYL > $CYL_MAX) && ($ValidateRight =='yes') && ($RE_CYL <> ''))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>Right Cylinder <b>('. $RE_CYL . ')</b> is higher than  the maximum accepted: <b>'. $CYL_MAX. '</b><br>';
				}
				
				if (($RE_CYL < $CYL_MIN) && ($ValidateRight =='yes') && ($RE_CYL <> ''))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>Right Cylinder <b>('. $RE_CYL . ')</b> is lower than the minimum accepted: <b>'. $CYL_MIN. '</b><br>';
				}
				
				if (($LE_CYL > $CYL_MAX) && ($ValidateLeft =='yes') && ($LE_CYL <> ''))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>Left Cylinder <b>('. $LE_CYL . ')</b> is higher than the maximum accepted: <b>'. $CYL_MAX. '</b><br>';
				}
				
				if (($LE_CYL < $CYL_MIN) && ($ValidateLeft =='yes') && ($LE_CYL <> ''))
				{
					$InsererDansBD  = false;
					$ErrorDetail.= '<br>Left Cylinder <b>('. $LE_CYL . ')</b> is lower than the minimum accepted: <b>'. $CYL_MIN. '</b><br>';
				}
				 
				
				
				
				
				$MIN_HEIGHT = $DataProduct[min_height];
				$MAX_HEIGHT = $DataProduct[max_height];
				//Si sv, on doit sauter cette validation
				if (strtolower($DataProduct[lens_category]<>'sv') && ($UnSvUnProg == false))
				{
				
					
				if ($SauterValidationFH <> "yes"){
					//Validations Fitting Height			
					if (($RE_HEIGHT > $MAX_HEIGHT) && ($ValidateRight =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>Right eye fitting Height  <b>('. $RE_HEIGHT . ')</b>  is higher than the maximum accepted: <b>'. $MAX_HEIGHT. '</b><br>';
					}
					
					if (($RE_HEIGHT < $MIN_HEIGHT) && ($ValidateRight =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>Right eye fitting height <b>('. $RE_HEIGHT . ')</b> is lower than the minimum accepted: <b>'. $MIN_HEIGHT. '</b><br>';
					}
					
					if (($LE_HEIGHT > $MAX_HEIGHT) && ($ValidateLeft =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>Left eye fitting height  <b>('. $LE_HEIGHT . ')</b> is higher than the maximum accepted: <b>'. $MAX_HEIGHT. '</b><br>';
					}
					
					if (($LE_HEIGHT < $MIN_HEIGHT) && ($ValidateLeft =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>Left eye fitting height <b>('. $LE_HEIGHT . ')</b> is lower than the minimum accepted: <b>'. $MIN_HEIGHT. '</b><br>';
					}
				}//End if $SauterValidationFH <> "yes";
				

					$ADD_MIN    = $DataProduct[add_min];
					$ADD_MAX    = $DataProduct[add_max];
					
					//Validation Additions
				 
					if (($RE_ADD > $ADD_MAX) && ($ValidateRight =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>The right eye addition value: <b>('. $RE_ADD . ')</b> is higher than the maximum addition accepted: <b>'. $ADD_MAX. '</b><br>';
					}
					
					if (($RE_ADD < $ADD_MIN) && ($ValidateRight =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>The right eye addition value: <b>('. $RE_ADD . ')</b>   is lower than the maximum addition accepted: <b>'. $ADD_MIN. '</b><br>';
					}
					
					if (($LE_ADD > $ADD_MAX) && ($ValidateLeft =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>The left eye addition value<b>('. $LE_ADD . ')</b> is higher than the maximum accepted: <b>'. $ADD_MAX. '</b><br>';
					}
					
					if (($LE_ADD < $ADD_MIN) && ($ValidateLeft =='yes'))
					{
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>The left eye addition value: <b>('. $LeAddition . ')</b>  is lower than the minimum accepted: <b>'. $ADD_MIN. '</b><br>';
					}
					
					/*//Validations si les additions sont differents
					if (($LE_ADD <> $RE_ADD) &&  ($ValidateRight =='yes')  && ($ValidateLeft =='yes')){
						$InsererDansBD  = false;
						$ErrorDetail.= '<br>Left eye addition <b>('. $LeAddition . ')</b> is Different from Right eye addition <b>('. $ReAddition . ')</b> Please confirm that this is not an error<br>';	
					}*/
					
				
				}//Fin si le produit commandé n'est pas un SV

}elseif($InsererDansBD){
	$ErrorDetail.= '<br>Product ID : <b>'. $ORDER_PRODUCT_ID. '</b>  introuvable parmis les produits de la table ifc_ca_exclusive<br>';
	$InsererDansBD  = false;
	echo '<br>ID de produit introuvable. Importation cancelled';
}


if ($InsererDansBD){
			//3- valider le Lens_Category
			switch (strtolower($DataProduct[lens_category])){
				
			case 'sv': 
			$validerSv   = true;
			$validerProg = false;
			break;	

			case 'prog ff': 
			$validerSv   = false;
			$validerProg = true;
			break;	
			
			case 'prog ds': 
			$validerSv   = false;
			$validerProg = true;
			break;	
			
			
			case 'bifocal':
			$validerSv   = false;
			$validerProg = true; 
			break;	
			
			case 'glass':
			$validerSv   = false;
			$validerProg = false;
			break;
			
			default: 	
			$ErrorDetail.= '<br>Product does not have a Lens category';
			$InsererDansBD  = false;	
			}
		}//End IF InsererDansBD

		
		
	if ($InsererDansBD){//Si oui,on continue		  
				if ($validerSv){
					echo '<br><br>Debut validation SV';
						switch($EYE){	
							case 'Both': 
							if ($RE_HEIGHT > 0){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>SV: RE_HEIGHT is incorrect: '. $RE_HEIGHT . ' <br>';
							}
							if ($LE_HEIGHT > 0){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>SV: LE_HEIGHT is incorrect: '. $LE_HEIGHT . ' <br>';
							}
							break;
							
							case 'L.E.':   
							if ($LE_HEIGHT > 0){
								$InsererDansBD = false;
								$ErrorDetail.= '<br>SV: LE_HEIGHT is incorrect: '. $LE_HEIGHT . ' <br>';	
							}	
							break;
				
							case 'R.E.': 
							if ($RE_HEIGHT > 0) {
								$InsererDansBD = false;	
								$ErrorDetail.= '<br>SV: RE_HEIGHT is incorrect: '. $RE_HEIGHT . ' <br>';	
							}	
							break;	
						}//End switch
				}		
		}				
						echo '<br>Resultat validationSv:';	


if ($InsererDansBD){//Si oui,on continue
				if ($validerProg){
				echo '<br>Debut validation Prog';
				//4- Valider qu'on a les hauteurs des les deux yeux
						
						switch($eye){	
							case 'Both': 
							echo '<br>Eye: Both'; 
							echo '<br> $RE_HEIGHT: '  . $RE_HEIGHT ; 	
							echo '<br> $LE_HEIGHT: '  . $LE_HEIGHT ;
							if ($RE_HEIGHT < 3){
							$InsererDansBD = false;
							$ErrorDetail.= '<br>Prog: RE_HEIGHT is incorrect: '. $RE_HEIGHT . ' <br>';
							}
							
							if ($LE_HEIGHT < 3){
							$InsererDansBD = false;	
							$ErrorDetail.= '<br>Prog: LE_HEIGHT is incorrect: '. $LE_HEIGHT . ' <br>';
							}
							
							break;
							
							case 'L.E.': 
							echo '<br>Eye: L.E.';   
							if ($LE_HEIGHT < 3){
								$InsererDansBD = false;	
								$ErrorDetail.= '<br>Prog: LE_HEIGHT is incorrect: '. $LE_HEIGHT . ' <br>';
							}	
							break;
				
							case 'R.E.': 
							echo '<br>Eye: R.E.'; 
							if ($RE_HEIGHT < 3) {
								$InsererDansBD = false;	
								$ErrorDetail.= '<br>Prog: RE_HEIGHT is incorrect: '. $RE_HEIGHT . ' <br>';
							}	
							break;	
						}//End switch
						
						echo '<br>Resultat validationProg:';
					
					//Valider qu'on a une hauteur dans chaque oeil
				
				}//End if ($validerProg)
		}//End IF InsererDansBD

	
			
			//Ce compte utilise les prix de sub-license	
			$price_can = $DataProduct[price_sublicensee];
			
			
	//SI SAFE, UTILISER LE PRICE_DISCOUNTED..
		if (($InsererDansBD) && ($AccountIsSubLicensee<>"oui")){
			//Si oui,on continue
		
			if ($EYE =='Both'){
				//echo '<br>Product Price: '. $DataProduct[price];
				//Majoration de prix 2% (supprimé le 18 Janvier 2019, Remis le 4 Mars 2019)
				
				if (($USER_ID!="88433")&&($USER_ID!="88438")&&($USER_ID!="88439")){
					$order_product_price 	 = $DataProduct[price] * 1.02;
					$order_product_discount  = $DataProduct[price] * 1.02;
				}else{
					$order_product_price 	 = $DataProduct[price] * 1;
					$order_product_discount  = $DataProduct[price] * 1;
				}
				//$order_product_price 	 = $DataProduct[price];
				//$order_product_discount  = $DataProduct[price];
				
			}elseif(($EYE== 'R.E.') || ($EYE == 'L.E.')){
				//echo '<br>Product Price: '. $DataProduct[price]/2;
				if (($USER_ID!="88433")&&($USER_ID!="88438")&&($USER_ID!="88439")){	
					$order_product_price 	 = ($DataProduct[price]/2) * 1.02;
					$order_product_discount  = ($DataProduct[price]/2) * 1.02;
				}else{
					$order_product_price 	 = ($DataProduct[price]/2) * 1;
					$order_product_discount  = ($DataProduct[price]/2) * 1;
				}
			}
			$order_shipping_cost     = 0;
			$order_product_name      = $DataProduct[product_name];
			$ORDER_PRODUCT_ID        = $DataProduct[primary_key];
			$ORDER_PRODUCT_INDEX     = $DataProduct[index_v];
			$ORDER_PRODUCT_COATING   = $DataProduct[coating];
			$ORDER_PRODUCT_POLAR     = $DataProduct[polar];
			$ORDER_PRODUCT_PHOTO     = $DataProduct[photo];
		}else{//Cas Sub Prix sub license
			
			if (($EYE =='Both') && ($AccountIsSubLicensee=="oui")){
				$order_product_price 	 = $DataProduct[price_sublicensee];
				$order_product_discount  = $DataProduct[price_sublicensee];
			}elseif(($EYE== 'R.E.') || ($EYE == 'L.E.')){
				$order_product_price 	 = ($DataProduct[price_sublicensee]/2);
				$order_product_discount  = ($DataProduct[price_sublicensee]/2);
			}
			$order_shipping_cost     = 0;
			$order_product_name      = $DataProduct[product_name];
			$ORDER_PRODUCT_ID        = $DataProduct[primary_key];
			$ORDER_PRODUCT_INDEX     = $DataProduct[index_v];
			$ORDER_PRODUCT_COATING   = $DataProduct[coating];
			$ORDER_PRODUCT_POLAR     = $DataProduct[polar];
			$ORDER_PRODUCT_PHOTO     = $DataProduct[photo];
			
		}//End IF
		
		
	
			
		if ($InsererDansBD)	// 5- Pour le moment, on ajoute dans le basket pour validation
			{
				//La commande n'est pas déja dans la BD, on l'importe
				echo '<br>Importation de la Commande '. $OrderNumberOptipro .  ' en cours.... : ';   ;			
                //POUR LE MOMENT ON UTILISE -1 COMME NUMERO DE COMMANDE PUISQU'ON AJOUTE AU BASKET
				$New_Order_Num 		  = -1 ;	
			}//End if $InsererDansBD

if (($InsererDansBD) && (strtolower($SAFETY)=='safety')){
	if ($RE_SPHERE > 0) $RE_ET = 3.00 ;
	if ($RE_SPHERE < 0) $RE_CT = 3.00 ;
	if ($LE_SPHERE > 0) $LE_ET = 3.00 ;
	if ($LE_SPHERE < 0) $LE_CT = 3.00 ;	
}//End if InsererDansDB (Assign Thickness)	

//Cas ou le verre droit est Plano et que l'épaisseur du verre gauche est au centre.
if (($RE_SPHERE==0) && ($LE_CT==3) && ($EYE == 'Both')){ 
		$RE_CT=3.00;
}
	
//Cas ou le verre droit est Plano et que l'épaisseur du verre gauche est au bord.
if (($RE_SPHERE==0) && ($LE_ET==3) && ($EYE == 'Both')){
		$RE_ET=3.00;
}	

//Cas ou le verre droit est Plano et que l'épaisseur du verre gauche est au centre.
if (($LE_SPHERE==0) && ($RE_CT==3) && ($EYE == 'Both')){
		$LE_CT=3.00;
}
	
//Cas ou le verre droit est Plano et que l'épaisseur du verre gauche est au bord.
if (($LE_SPHERE==0) && ($RE_ET==3) && ($EYE == 'Both')){
		$LE_ET=3.00;
}
	
//Preparation Knife Edge
if ($KNIFE_EDGE <> ''){//Demander Knife Edge dans l'instruction spéciale
	echo '<br>Ajout Knife Edge<br>';
	$SPECIAL_INSTRUCTIONS = $SPECIAL_INSTRUCTIONS. '  ***KNIFE EDGE ***';
}//End if Knife Edge est demandé		
	
	

//Vérification si doit demander le UV400 automatiquement
if ((strtolower($TINT) == "solid")  || (strtolower($TINT) == "gradient")){
		//On a une teinte, si l'indice est 1.50, on doit ajouter le UV400 automatiquement.
		if ($ORDER_PRODUCT_INDEX=='1.50'){
			$UV400="UV400";
		}else{
		//Indice différent de 1.50. On ne doit PAS demander de UV400	
			$UV400="";
		}//END IF
}//END IF THERE IS A TINT	
	
	
$order_shipping_cost     = 0;//Roberto request Ticket #5736 shipping free for every account	
	
if (($InsererDansBD) && ($ESTUNEREPRISE =='')  && ($ESTENATTENTE =='')){//Si oui,on continue			
					$InsertQuery="INSERT INTO orders(VERTEX, PT, PA, authorized_by, salesperson_id, order_num, order_date_processed,user_id, eye, re_height, le_height, re_add, le_add, re_cyl, le_cyl,
					re_sphere, le_sphere, order_status, re_axis, le_axis, re_pd, le_pd, re_pd_near, le_pd_near, frame_a, frame_b, frame_ed, frame_dbl, order_shipping_method, order_product_type,
					order_patient_first, order_patient_last, order_item_date, order_quantity, re_pr_ax, le_pr_ax, re_pr_ax2, le_pr_ax2, re_pr_io, le_pr_io, re_pr_ud, le_pr_ud, frame_type, currency,
					order_from, lab, order_product_id, order_product_index, order_product_photo, order_product_polar	, order_product_coating, patient_ref_num,order_product_price, order_product_discount,
					order_shipping_cost,order_product_name, prescript_lab, warranty, re_ct, le_ct, re_et, le_et,order_num_optipro, optical_center, internal_note, shape_name_bk, myupload, SPECIAL_INSTRUCTIONS,
					BASE_CURVE, opticien, nom_produit_optipro, code_remise_optipro, UV400, code_source_monture,total_optipro,total_monture_optipro)										
					VALUES('$VERTEX', '$PT', '$PA', '$SALESPERSON_ID','$SALESPERSON_ID','$New_Order_Num','$ORDER_DATE_PLACED', '$USER_ID','$EYE', '$RE_HEIGHT', '$LE_HEIGHT',
					'$RE_ADD', '$LE_ADD', '$RE_CYL', '$LE_CYL', '$RE_SPHERE', '$LE_SPHERE','$ORDER_STATUS', '$RE_AXIS', '$LE_AXIS', '$RE_PD', '$LE_PD', '$RE_PD_NEAR', '$LE_PD_NEAR',
					'$FRAME_A', '$FRAME_B', '$FRAME_ED', '$FRAME_DBL',  '$ORDER_SHIPPING_METHOD', '$ORDER_PRODUCT_TYPE','$ORDER_PATIENT_FIRST', '$ORDER_PATIENT_LAST', '$ORDER_DATE_PLACED', 
					1, '$RE_PR_AX', '$LE_PR_AX', '$RE_PR_AX2', '$LE_PR_AX2',        '$RE_PR_IO', '$LE_PR_IO', 		 '$RE_PR_UD',    '$LE_PR_UD', '$FRAME_TYPE', '$CURRENCY', '$ORDER_FROM',
					'$LAB', '$ORDER_PRODUCT_ID', '$ORDER_PRODUCT_INDEX',        '$ORDER_PRODUCT_PHOTO', '$ORDER_PRODUCT_POLAR', '$ORDER_PRODUCT_COATING','$PATIENT_REF_NUM','$order_product_price',
					'$order_product_discount','$order_shipping_cost','$order_product_name',$PRESCRIPT_LAB,'$WARRANTY', '$RE_CT', '$LE_CT', '$RE_ET', '$LE_ET','$OrderNumberOptipro','$OPTICAL_CENTER', 
					'$INTERNAL_NOTE','$TRACE','$TRACE','$SPECIAL_INSTRUCTIONS','$BASE_CURVE','$OPTICIEN','$PRODUCT_NAME_OPTIPRO','$CODE_REMISE_OPTIPRO','$UV400','$CODE_SOURCE_MONTURE','$TOTAL_OPTIPRO','$TOTAL_MONTURE_OPTIPRO')" ;
															 
					echo '<br>Requete : '.$InsertQuery   ; 
					$InsertResult=mysqli_query($con,$InsertQuery)		or die  ('INSERTQUERY: I cannot select items because 3 : ' . mysqli_error($con));
					
					$QueryLastID  = "SELECT LAST_INSERT_ID() as LastID";
					$resultLastID = mysqli_query($con,$QueryLastID)		or die  ('QueryLastID: I cannot select items because 4 : ' . mysqli_error($con));
					$DataLastID   = mysqli_fetch_array($resultLastID,MYSQLI_ASSOC);	
					$LastID       = $DataLastID[LastID]; 
					echo '<br>Last ID: ' . $LastID . '<br>';
					
					//Sauvegarder le status 'basket' dans l'historique (status_history)
					$todayDate = date("Y-m-d g:i a");// current date
					$currentTime = time($todayDate); //Change date into time
					$timeAfterOneHour = $currentTime+((60*60)*6);	//Add 3 hours to server time to get actual time
					$datecomplete     = date("Y-m-d H:i:s",$timeAfterOneHour);				
					$order_status     = "Basket";
					$queryHistorique  = "INSERT INTO status_history (order_primary_key, order_status, update_time, update_type) VALUES ($LastID, '$order_status','$datecomplete','Importation Optipro 2.0')";
					echo '<br>'. $queryHistorique;
					$resultHistorique = mysqli_query($con,$queryHistorique) or die  ('I cannot select items because: ' . mysqli_error($con));
					
}//END IF InsererDansBD


if (   ($InsererDansBD) &&  ($JOB_TYPE <> '') && ($ESTUNEREPRISE == '')  && ($ESTENATTENTE == '')    ){//Si ce n'est pas uncut et pas vide, on doit ajouter l'extra Edging			
		//Préparation ajout extra_product	: EDGING
		$queryOrderID  = "SELECT max(primary_key) as order_id FROM orders WHERE order_num_optipro = $OrderNumberOptipro ";
		$resultOrderID = mysqli_query($con,$queryOrderID)		or die  (' QUERY ORDER ID I cannot select items because 5: ' . mysqli_error($con));
		$DataOrderID   = mysqli_fetch_array($resultOrderID,MYSQLI_ASSOC);	
		$order_id      = $DataOrderID[order_id];
				
	
		$price        =  6;//PROGRESSIF
		if (($USER_ID=="88433")||($USER_ID=="88438")||($USER_ID=="88439")){//Si sub license, prix du taillage est déja inclus dans les verres
			$price        =  0;//Taillage déja inclus dans le prix des 3 magasins sous license
		}//END IF
		
		$order_num    =  -1;
		$category     =  'Edging';
		$ep_prod_id   =  3;
		$frame_type   = $FRAME_TYPE;
		$order_type   = 'Provide';//Provide ou To Follow ?
		$ep_frame_a   = $FRAME_A;
		$ep_frame_b   = $FRAME_B;
		$ep_frame_ed  = $FRAME_ED;
		$ep_frame_dbl = $FRAME_DBL;
			
		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	

			$queryEdging = "INSERT INTO extra_product_orders (order_id, price, order_num, category, ep_prod_id, job_type, frame_type, order_type, ep_frame_a, ep_frame_b, ep_frame_ed, ep_frame_dbl, temple_model_num, color, supplier)
			VALUES ($order_id,'$price', '$order_num', '$category', $ep_prod_id, '$JOB_TYPE', '$frame_type', '$order_type', $ep_frame_a, $ep_frame_b, $ep_frame_ed, $ep_frame_dbl, '$FRAME_MODEL', '$COLOR','$SUPPLIER')";
			echo '<br><br>Requete Edging: '. $queryEdging. '<br><br>';
			$InsertqueryEdging=mysqli_query($con,$queryEdging)	or die  ('I cannot select items because 6: ' . mysqli_error($con));
		}
	}				
	
	
//Préparation ajout  extra_product:  Frame
	$order_id     = $DataOrderID[order_id];
	$price        =  0;
	
switch($FRAME_MODEL){
	case 'FUGLIES_C':   	 $price = 49.95; break;
	//Sous collections appartenant a Fuglies_C
	case 'RX03':   	         $price = 49.95; break;
	case 'RX04':   	         $price = 49.95; break;
	case 'RX14':   	         $price = 49.95; break;
	case 'RX15':   	         $price = 49.95; break;
	case 'RX16':   	         $price = 49.95; break;	
	case 'FUGLIES_B':  	 	 $price = 38.95; break;
	//Sous collections appartenant a Fuglies_B
	case 'RX05':  	 	     $price = 38.95; break;
	case 'RX06':  	 	     $price = 38.95; break;
	case 'FUGLIES_A':   	 $price = 27.95; break;
	//Sous collections appartenant a Fuglies_A
	case 'RX01':   		 	 $price = 27.95; break;
	case 'RX02':   	 		 $price = 27.95; break;
	case 'RX07':   	 		 $price = 27.95; break;
	case 'RX08':   			 $price = 27.95; break;
	case 'RX09':   			 $price = 27.95; break;
	case 'RX10':   			 $price = 27.95; break;
	case 'RX11':   			 $price = 27.95; break;
	case 'RX12':   		 	 $price = 27.95; break;
	case 'RX13':   			 $price = 27.95; break;
}//End Switch
	
	if ($SAFETY=='safety'){
		$collectionaUtiliser = "safety_frames_french";	
		$ChampaUtiliser      = "frame_interco";
	}else{
		$collectionaUtiliser = "ifc_frames_french";
		$ChampaUtiliser      = "stock_price_entrepot";	
	}
	

$NbrResultat = 1;

switch(strtoupper($SUPPLIER)){
	//PARTIE ARMOURX
	case 'ARMOURX':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT $ChampaUtiliser  FROM $collectionaUtiliser WHERE model = '$FRAME_MODEL' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[$ChampaUtiliser];
	if ($price == 0){
		EnvoyerEmailFramea0($OrderNumberOptipro,$USER_ID, $queryPrice);	
	}
	break;
	//Sous collections faisant partie de ARmouRx
	case 'BASIC':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT $ChampaUtiliser  FROM $collectionaUtiliser WHERE model = '$FRAME_MODEL' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[$ChampaUtiliser];
	if ($price == 0){
		EnvoyerEmailFramea0($OrderNumberOptipro,$USER_ID, $queryPrice);	
	}
	break;
	
	case 'CLASSIC':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT $ChampaUtiliser  FROM $collectionaUtiliser WHERE model = '$FRAME_MODEL' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[$ChampaUtiliser];
	if ($price == 0){
		EnvoyerEmailFramea0($OrderNumberOptipro,$USER_ID, $queryPrice);	
	}
	break;
	case 'METRO':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT $ChampaUtiliser  FROM $collectionaUtiliser WHERE model = '$FRAME_MODEL' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[$ChampaUtiliser];
	if ($price == 0){
		EnvoyerEmailFramea0($OrderNumberOptipro,$USER_ID, $queryPrice);	
	}
	break;
	case 'WRAP-RX':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT $ChampaUtiliser  FROM $collectionaUtiliser WHERE model = '$FRAME_MODEL' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[$ChampaUtiliser];
	if ($price == 0){
		EnvoyerEmailFramea0($OrderNumberOptipro,$USER_ID, $queryPrice);	
	}
	break;
	
	//GESTION DEEP ET NURBS
	case 'DEEP':  
	//Prix différents pour chaque monture, aller le chercher dans la base de données
	$queryPrice  	= "SELECT  stock_price_entrepot FROM ifc_frames_french WHERE model like '%$FRAME_MODEL%' AND color_en =  '$COLOR'";
	echo '<br>queryPrice: '. $queryPrice;
	$resultPrice 	= mysqli_query($con,$queryPrice)	or die  ("An error occured.". mysqli_error($con));
	$NbrResultat    = mysqli_num_rows($resultPrice);
	$DataPrice   	= mysqli_fetch_array($resultPrice,MYSQLI_ASSOC);
	$price          = $DataPrice[stock_price_entrepot];
	break;
	
	default: echo '<br>SUPPLIER:'. $SUPPLIER;
//FIN PARTIE ARMOURX
}	
	
	
	

//Verifier si la monture n'a pas été identifiée correctement, on doit un courriel pour demander d'ajouter le prix de la monture sur cette facture 
if ($NbrResultat == 0){
	//Envoyer courriel 
	EnvoyerEmailFrameaCreer($OrderNumberOptipro,$USER_ID, $queryPrice);   
	echo '<br>EnvoyerEmailFrameaCreer';                 
	$ErrorDetail.= '<br>La monture ne correspond pas au type de verre voulu. (Verre de securite + monture sans cot&eacute;s protecteur ou verre hors securite avec cot&eacute;s protecteurs<br>';
	$InsererDansBD  = false;
}//End IF
	
	$order_num    =  -1;
	$category     =  'Frame';
	$ep_prod_id   =  0;
	$frame_type   = $FRAME_TYPE;
	$order_type   = 'Provide';//Provide ou To Follow ?
	$ep_frame_a   = $FRAME_A;
	$ep_frame_b   = $FRAME_B;
	$ep_frame_ed  = $FRAME_ED;
	$ep_frame_dbl = $FRAME_DBL;
		
	if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE ==''))//Si oui,on continue	
	{			
		$queryFrame = "INSERT INTO extra_product_orders (order_id, price, order_num, category, ep_prod_id, job_type, frame_type, order_type, ep_frame_a, ep_frame_b, ep_frame_ed, ep_frame_dbl, temple_model_num, color, supplier)
		VALUES ($order_id,'$price', '$order_num', '$category', $ep_prod_id, '$JOB_TYPE', '$frame_type', '$order_type', $ep_frame_a, $ep_frame_b, $ep_frame_ed, $ep_frame_dbl, '$FRAME_MODEL', '$COLOR','$SUPPLIER')";
		echo '<br><br>Requete Frame: '. $queryFrame. '<br><br>';
		$InsertqueryFrame=mysqli_query($con,$queryFrame)	or die  ('I cannot insert query Frame in extra product orders: ' . mysqli_error($con));
	}//End IF InsererDansBD

	
	



//EXTRA IIMPACT
if ($IIMPACT=='iimpact'){

$ep_frame_a="";
$ep_frame_b="";
$ep_frame_ed="";
$ep_frame_dbl="";
$frame_type="";
$engraving="";
$tint="";
$tint_color="";
$from_perc="";
$to_perc="";
$job_type="";
$order_type="";
$supplier="";
$model="";
$color="";
$order_type="";
$temple="";
$order_num=-1;
$category="Iimpact";
$extra_amount = 10;
$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";


		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	{
			echo '<br><br>'. $query . '<br><br>';
			$result=mysqli_query($con,$query) or die ( "Insert IIMPACT query failed: " . mysqli_error($con) . "<br/>" . $query );
		}
}






//EXTRA CYL OVER RANGE (plus petit que -4)
if (($RE_CYL < -4) || ($LE_CYL < -4)){

$ep_frame_a="";
$ep_frame_b="";
$ep_frame_ed="";
$ep_frame_dbl="";
$frame_type="";
$engraving="";
$tint="";
$tint_color="";
$from_perc="";
$to_perc="";
$job_type="";
$order_type="";
$supplier="";
$model="";
$color="";
$order_type="";
$temple="";
$order_num=-1;
$category="Cylinder Over Range";
$extra_amount = 10;
$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$extra_amount')";


		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	{
			echo '<br><br>'. $query . '<br><br>';
			$result=mysqli_query($con,$query) or die ( "Insert Dust Bar query failed: " . mysqli_error($con) . "<br/>" . $query );
		}
}



	
				//ADD EXTRA PRODUCTS: TINT	
				if ((strtolower($TINT) == "solid")  || (strtolower($TINT) == "gradient")){
					//Inserer dans extra_product_order
					$frame_type		= "";
					$color			= "";
					$order_type		= "";
					$temple			= "";
					$order_num		= -1;
					$main_lab_id	= $LAB;
					$category		= "Tint";
					
					if($AjoutTeintePromo == 'non'){		  					
						switch(strtoupper($TINT)){
							case 'SOLID':    $price = 16.00; break;
							case 'GRADIENT': $price = 24.00; break;
						}//End Switch	
					}

					$ep_prod_id =$listItem[prod_id];
					
					
					if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue		
						$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES  ($order_id,'$order_num','$category','$engraving','$TINT','$TINT_COLOR','$FROM_PERC','$TO_PERC','$ep_prod_id', '$frame_type','$job_type', '$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b', '$ep_frame_ed', '$ep_frame_dbl', '$temple',     '$price')";
						echo '<br>Query Extra Prod: '. $query;
						$result=mysqli_query($con,$query)		or die ( "Insert Tint query failed 2: " . mysqli_error($con) . "<br/>" . $query );
					}//End if InsererDansBD
				
				}//End IF	
	
	
	
	//ADD EXTRA: MIRROR:
				if (strtolower($MIRROR) <> "") {
					//Inserer dans extra_product_order
					$frame_type		= "";
					$color			= "";
					$order_type		= "";
					$temple			= "";
					$order_num		= -1;
					$main_lab_id	= $LAB;
					$category		= "Mirror";
					$price 		    = 50.00;
					$ep_prod_id     = 23;
					$tint_color     = '';
					$tint           = '';
					$FROM_PERC      = '';
					$TO_PERC        = '';
 					
					
					switch ($MIRROR){//Si mirroir = Swiss, on change la collection pour Swiss.
					//Swiss
						case 'MIRA':  $tint_color  = 'Aston';   	 break;
						case 'MIRBB': $tint_color  = 'Balloon Blue'; break;
						case 'MIRC':  $tint_color  = 'Canyon';   	 break;
						case 'MIRD':  $tint_color  = 'Dona';   	     break;
						case 'MIRPS': $tint_color  = 'Pasha Silver'; break;
						case 'MIROF': $tint_color  = 'Ocean Flash';  break;
						case 'MIRPG': $tint_color  = 'Pine Green';   break;
						case 'MIRPP': $tint_color  = 'Pink Panther'; break;
						case 'MIRS':  $tint_color  = 'Sahara';   	 break;
						case 'MIRT':  $tint_color  = 'Tank';   	     break;
						//GKB
						case 'MIRRG': $tint_color  = 'Green';   	 break;
						case 'MIRRGO':$tint_color  = 'Gold';   	     break;
						case 'MIRROB':$tint_color  = 'Ocean Blue';   break;
						case 'MIRRR': $tint_color  = 'Red';   	     break;
						case 'MIRRS': $tint_color  = 'Silver';   	 break;
						case 'MIRRY': $tint_color  = 'Yellow';   	 break;
						
						//Swiss
						case 'MIRA':  		$tint_color  = 'Aston';   	 	break;
						case 'MIRBB': 		$tint_color  = 'Balloon Blue'; 	break;
						case 'MIRC':  		$tint_color  = 'Canyon';   		break;
						case 'MIRD':  		$tint_color  = 'Dona';   	    break;
						case 'MIRPS': 		$tint_color  = 'Pasha Silver'; 	break;
						case 'MIROF': 		$tint_color  = 'Ocean Flash';  	break;
						case 'MIRPG': 		$tint_color  = 'Pine Green';   	break;
						case 'MIRPP': 		$tint_color  = 'Pink Panther'; 	break;
						case 'MIRS':  		$tint_color  = 'Sahara';   	 	break;
						case 'MIRT':  		$tint_color  = 'Tank';   	    break;
							
						//GKB
						case 'MIRRG': 		$tint_color  = 'Green';   	 	break;
						case 'MIRRGO':		$tint_color  = 'Gold';   	    break;
						case 'MIRROB':		$tint_color  = 'Ocean Blue';   	break;
						case 'MIRRR': 		$tint_color  = 'Red';   	    break;
						case 'MIRRS': 		$tint_color  = 'Silver';   	 	break;
						case 'MIRRY': 		$tint_color  = 'Yellow';   		break;
						
						//KNR
						case 'KNR_M_AME':	$tint_color  ="Amethyst Mirror"; 		break;
						case 'KNR_M_BLA':	$tint_color  ="Black Diamond Mirror"; 	break;
						case 'KNR_M_DRK':	$tint_color  ="Dark Saphire Mirror"; 	break;
						case 'KNR_M_EME':	$tint_color  ="Emerald Mirror"; 		break;
						case 'KNR_M_FIRE':	$tint_color  ="Fireopal Mirror"; 		break;
						case 'KNR_M_GLD':	$tint_color  ="Gold Mirror"; 			break;
						case 'KNR_M_ROSE':	$tint_color  ="Rose Mirror"; 			break;
						case 'KNR_M_RUBY':	$tint_color  ="Ruby Mirror"; 			break;
						case 'KNR_M_SAP':	$tint_color  ="Sapphire Mirror"; 		break;
						default: 'Valeur du champ:'. $MIRROR;
					}	
	
					
					
										
					if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='') && ($tint_color<>'')){//Si oui,on continue		
						$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES  ($order_id,'$order_num','$category','$engraving','$TINT','$tint_color','$FROM_PERC','$TO_PERC','$ep_prod_id', '$frame_type','$job_type',                 '$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b', '$ep_frame_ed', '$ep_frame_dbl', '$temple',     '$price')";
						echo '<br>MIRROR: Query Extra Prod: '. $query;
						echo '<br>Tint Color: '. $tint_color;
						$result=mysqli_query($con,$query)		or die ( "Insert Tint query failed3 : " . mysqli_error($con) . "<br/>" . $query );
					}else{
					echo '<br>InsererdansDB:'. 	$InsererDansBD;
					echo '<br>ESTUNEREPRISE:'. 	$ESTUNEREPRISE;
					echo '<br>ESTENATTENTE:'. 	$ESTENATTENTE;
					echo '<br>tint_color:'. 	$tint_color;
					}//End if InsererDansBD
				
				}//End if Tint csv contains a mirror
		//END ADD EXTRA MIRROR
		
		
		
		//ADD EXTRA EDGE POLISH
	if (strtoupper($BISEAUX_POLIS) == 'BISEAUX POLIS'){//On ajoute l'extra pour cet item de 2$
		$ep_frame_a   = "";
		$ep_frame_b   = "";
		$ep_frame_ed  = "";
		$ep_frame_dbl = "";
		$frame_type   = "";
		$engraving    = "";
		$tint         = "";
		$tint_color   = "";
		$from_perc    = "";
		$to_perc      = "";
		$job_type     = "";
		$order_type   = "";
		$supplier     = "";
		$model        = "";
		$color        = "";
		$order_type   = "";
		$temple       = "";
		$order_num    = -1;
		$main_lab_id  = $LAB;
		$category     = "Edge Polish";
		$price        = 2;//2$ par paire
	
		$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";
		
		
		//echo $query;
	
		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	{
			$result=mysqli_query($con,$query)		or die ( "Insert Edge Polish query failed 4: " . mysqli_error($con) . "<br/>" . $query );
		}
	}//END IF
	//END EXTRA EDGE POLISH
	
	
	
	
	//Extra: side shields permanents	
if ($SIDE_SHIELD == 'Cotes protecteurs securites'){
	//Ajouter l'extra pour les side shields permanents
	$ep_frame_a   = "";
	$ep_frame_b   = "";
	$ep_frame_ed  = "";
	$ep_frame_dbl = "";
	$frame_type   = "";
	$engraving    = "";
	$tint         = "";
	$tint_color   = "";
	$from_perc    = "";
	$to_perc      = "";
	$job_type     = "";
	$order_type   = "";
	$supplier     = "";
	$model        = "";
	$color        = "";
	$order_type   = "";
	$temple       = "";
	$order_num    = -1;
	$main_lab_id  = $LAB;
	$category     = "Side Shield";
	$price        = 2.50;//2.50 pour 2 coussins permanents	
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	
		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	{
			$result=mysqli_query($coon, $query)		or die ( "Insert Side Shield query failed 5: " . mysqli_error($con) . "<br/>" . $query );
			echo '<br>'. $query . '<br>';
		}
}else{
 echo '<br> Contenu side shield: '. $SIDE_SHIELD;	
}//End if Side shields permanent


		
//Extra: removableside shields 	
if ($SIDE_SHIELD == 'Cotes protecteurs amovibles'){// Dans optipro, ca se nomme 'Removable Cushion'
	//Ajouter l'extra pour les side shields amovibles
	$ep_frame_a   = "";
	$ep_frame_b   = "";
	$ep_frame_ed  = "";
	$ep_frame_dbl = "";
	$frame_type   = "";
	$engraving    = "";
	$tint         = "";
	$tint_color   = "";
	$from_perc    = "";
	$to_perc      = "";
	$job_type     = "";
	$order_type   = "";
	$supplier     = "";
	$model        = "";
	$color        = "";
	$order_type   = "";
	$temple       = "";
	$order_num    = -1;
	$main_lab_id  = $LAB;
	$category     = "Removable Cushion";
	$price        = 2.50;//2.50 pour 2 coussins permanents	
	
	$query="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES ('$order_id','$order_num','$category','$engraving','$tint','$tint_color','$from_perc','$to_perc','$ep_prod_id','$frame_type','$job_type','$supplier','$model','$color','$order_type','$ep_frame_a','$ep_frame_b','$ep_frame_ed','$ep_frame_dbl','$temple','$price')";

	
		if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue	{
			$result=mysqli_query($con,$query)		or die ( "Insert Edge Polish query failed 6: " . mysqli_error($con) . "<br/>" . $query );
		}
}//End if Side shields permanent	
	
	
	
		//ADD EXTRA PRODUCTS PRISMS
		$ExtraPrism = 'no';
		if (strlen($RE_PR_AX > 0))
		$ExtraPrism = 'yes';
		if (strlen($RE_PR_AX2 > 0)) 
		$ExtraPrism = 'yes';
		if (strlen($LE_PR_AX > 0))  
		$ExtraPrism = 'yes';
		if (strlen($LE_PR_AX2 > 0))  
		$ExtraPrism = 'yes';	
		if ($ExtraPrism == 'yes'){
		
			//AT THIS STEP, WE NEED TO INSERT PRISM ITEM IN EXTRA PRODUCT ORDERS	
			$category		= "Prism";
			$order_num		= -1;
			$ep_product_id  = 11;
			$price          = 10;
			$main_lab_id	= $LAB;//	
			$TINT		    = '';
			$TINT_COLOR		= '';
			$SUPPLIER       = '';
			
			
			if (($InsererDansBD) && ($ESTUNEREPRISE =='') && ($ESTENATTENTE =='')){//Si oui,on continue		
				$queryPrism="INSERT into extra_product_orders (order_id,order_num,category,engraving,tint,tint_color,from_perc,to_perc,ep_prod_id,frame_type,job_type,supplier,model,color,order_type,ep_frame_a,ep_frame_b,ep_frame_ed,ep_frame_dbl,temple,price) VALUES  ($order_id,'$order_num','$category','$engraving','$TINT','$TINT_COLOR','$FROM_PERC','$TO_PERC','$ep_prod_id', '$frame_type','$job_type','$SUPPLIER','$model','$color','$order_type','$ep_frame_a','$ep_frame_b', '$ep_frame_ed', '$ep_frame_dbl', '$temple',     '$price')";
				echo '<br>Query  Prism: '. $queryPrism;
				$resultPrism=mysqli_query($con,$queryPrism)		or die ( "Insert Tint query failed 7: " . mysqli_error($con) . "<br/>" . $query );
			}//End IF InsererDansBD
		}//END if Prism
		

			echo '<br>Error Detail:'.  $ErrorDetail . '<br>';
			echo '<br>$INTERDICTIONDEFFACER:'. $INTERDICTIONDEFFACER ;
			echo '<br>$ImporterCetteCommande:'. $ImporterCetteCommande ;
			echo '<br>$ESTUNEREPRISE:'. $ESTUNEREPRISE ;
			echo '<br>$ESTENATTENTE:'. $ESTENATTENTE ;
			echo '<br>$ErrorDetail:'. $ErrorDetail ;
			
	
	if (($InsererDansBD)&&($ESTUNEREPRISE=='')&&($ESTENATTENTE=='')){//Si oui,on continue
		echo '<br>passe cas 1 <br>';
		//Copier le fichier dans le dossier de backup  PUIS L'EFFACER
		$file        = $local_file;
		$remote_file = $server_file;
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
		ftp_pasv($conn_id,true);
		ftp_chdir($conn_id,"Optipro");
		$directory=ftp_pwd($conn_id);
		ftp_chdir($conn_id,"Copie apres importation reussie");
		$directory=ftp_pwd($conn_id);
		EnvoyerEmailSucces($remote_file,$OrderNumberOptipro,$RxData,$ProdData,$USER_ID);
		// upload a file
		if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
			echo "<br>Fichier copier avec succes $file\n";
		} else {
			echo "<br>There was a problem while uploading $file\n";
		}
		// close the connection
		ftp_close($conn_id);	
		// set up basic connection
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
		ftp_pasv($conn_id,true);
		ftp_chdir($conn_id,"Optipro");
		$directory=ftp_pwd($conn_id);
		echo '<br>File Deleted.';
		if ($INTERDICTIONDEFFACER  <> 'oui'){
			ftp_delete($conn_id,$remote_file);
		}else{
			echo 'Interdiction d effacer le fichier';	
		}	
	}elseif(($ESTUNEREPRISE=='oui') || ($ESTENATTENTE=='oui')){
	//Signifie que c'est une reprise, on doit donc effacer le fichier 
		$remote_file = $server_file;
		// set up basic connection
		$conn_id = ftp_connect($ftp_server);
		$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
		ftp_pasv($conn_id,true);
		ftp_chdir($conn_id,"Optipro");
		$directory=ftp_pwd($conn_id);
		echo '<br>File Deleted.';
		if ($INTERDICTIONDEFFACER  <> 'oui'){
			ftp_delete($conn_id,$remote_file);
			echo 'REPRISE effacé';
		}else{
			echo 'Interdiction d effacer le fichier';	
		}		
	}

	
		if ($InsererDansBD<>true){
			echo '<br>passe cas 2<br>';
			echo '<br>Erreur survenu, on deplace le fichier dans le repertoire Errors ET on envoie un courriel pour informer de l\'erreur'; 
			//Copier le fichier dans le dossier d'erreurs puisqu'il y en a a eu  PUIS L'EFFACER du dossier From_DL
			$file        = $local_file;
			$remote_file = $server_file;
			$conn_id = ftp_connect($ftp_server);
			$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
			ftp_pasv($conn_id,true);
			ftp_chdir($conn_id,"Optipro");
			$directory=ftp_pwd($conn_id);
			ftp_chdir($conn_id,"Erreurs");
			$directory=ftp_pwd($conn_id);
			if (($ESTUNEREPRISE == '') &&($ESTENATTENTE == '') && ($ImporterCetteCommande <> 'non'))
			EnvoyerEmailErreur($remote_file,$ErrorDetail,$OrderNumberOptipro,$RxData,$ProdData,$OrderNumberOptipro); 
			$ErrorDetail = str_replace('<br>',' ',$ErrorDetail);	
			$ErrorDetail = str_replace('<b>','',$ErrorDetail);
			$ErrorDetail = str_replace('</b>','',$ErrorDetail);	
			$remote_file = str_replace('./','',$remote_file);
			$PatientFullName = $ORDER_PATIENT_FIRST . ' ' . $ORDER_PATIENT_LAST;
			$today 			 = date("Y-m-d");
			
			if ($INTERDICTIONDEFFACER <>'oui'){
				if (($DataProduct[primary_key] == '') && ($ImporterCetteCommande <> 'non') && ($ESTUNEREPRISE == '') && ($ESTENATTENTE == '') && ($PRODUCT_NAME_OPTIPRO<>''))
				$queryErreur = "INSERT INTO erreurs_optipro (date,user_id,detail,order_num_optipro,nom_fichier, produit_optipro,rx_re_sphere,rx_le_sphere,rx_re_cyl, rx_le_cyl, rx_re_axis, rx_le_axis, rx_re_add, rx_le_add,rx_frame_a,rx_frame_b,rx_frame_ed,rx_frame_dbl,rx_re_height,rx_le_height,rx_frame_type,rx_re_pd_near,rx_le_pd_near,rx_re_pd, rx_le_pd, rx_patient_full_name,rx_index_v, rx_coating,rx_frame_model,rx_frame_collection,rx_frame_color,rx_photo,rx_polar,nombre_notification_succursale,date_derniere_notification)	
				VALUES  ('$ORDER_DATE_PROCESSED','$USER_ID', '$ErrorDetail','$OrderNumberOptipro','$remote_file','$PRODUCT_NAME_OPTIPRO','$RE_SPHERE','$LE_SPHERE','$RE_CYL','$LE_CYL','$RE_AXIS','$LE_AXIS','$RE_ADD','$LE_ADD','$FRAME_A','$FRAME_B','$FRAME_ED','$FRAME_DBL','$RE_HEIGHT','$LE_HEIGHT','$FRAME_TYPE','$RE_PD_NEAR','$LE_PD_NEAR','$RE_PD','$LE_PD','$PatientFullName','$ORDER_PRODUCT_INDEX','$ORDER_PRODUCT_COATING','$FRAME_MODEL','$SUPPLIER','$COLOR','$ORDER_PRODUCT_PHOTO','$ORDER_PRODUCT_POLAR',1,'$today')";  
				elseif(($ImporterCetteCommande <> 'non') && ($ESTUNEREPRISE == '') && ($ESTENATTENTE == ''))
				$queryErreur = "INSERT INTO erreurs_optipro (date,user_id,detail,order_num_optipro,nom_fichier,cle_produit,produit_optipro,rx_re_sphere,rx_le_sphere,rx_re_cyl,rx_le_cyl,rx_re_axis,rx_le_axis, rx_re_add, rx_le_add,rx_frame_a,rx_frame_b,rx_frame_ed,rx_frame_dbl,rx_re_height,rx_le_height,rx_frame_type,rx_re_pd_near,rx_le_pd_near,rx_re_pd, rx_le_pd,rx_patient_full_name,rx_index_v, rx_coating,rx_frame_model,rx_frame_collection,rx_frame_color,rx_photo,rx_polar,nombre_notification_succursale,date_derniere_notification)	
				VALUES  ('$ORDER_DATE_PROCESSED','$USER_ID', '$ErrorDetail','$OrderNumberOptipro','$remote_file', $DataProduct[primary_key],'$PRODUCT_NAME_OPTIPRO','$RE_SPHERE','$LE_SPHERE','$RE_CYL','$LE_CYL',   '$RE_AXIS','$LE_AXIS','$RE_ADD','$LE_ADD','$FRAME_A','$FRAME_B','$FRAME_ED','$FRAME_DBL','$RE_HEIGHT','$LE_HEIGHT','$FRAME_TYPE','$RE_PD_NEAR','$LE_PD_NEAR','$RE_PD','$LE_PD','$PatientFullName','$ORDER_PRODUCT_INDEX','$ORDER_PRODUCT_COATING','$FRAME_MODEL','$SUPPLIER','$COLOR','$ORDER_PRODUCT_PHOTO','$ORDER_PRODUCT_POLAR',1,'$today')";
				
				echo '<br>' . $queryErreur . '<br>';
			}
			
			if(($ImporterCetteCommande <> 'non') && ($ErrorDetail <> '')  && ($ESTUNEREPRISE == '') && ($ESTENATTENTE == '')){
					$ResultErreur=mysqli_query($con,$queryErreur)			or die ( "Query failed 1: " . mysqli_error($con));	
					EnvoyerEmailErreurSuccursale($remote_file,$ErrorDetail,$OrderNumberOptipro,$RxData,$ProdData,$OrderNumberOptipro,$USER_ID); 
					echo '<br>ICIICIICI';
					echo '<br>USER ID:'. $USER_ID.'<br>';
			}
			
			// upload a file
			if (ftp_put($conn_id, $remote_file, $file, FTP_ASCII)) {
			 echo "<br>Fichier copier avec succes $file\n";
			} else {
			 echo "<br>There was a problem while uploading $file\n";
			}
			// close the connection
			ftp_close($conn_id);	
			// set up basic connection
			$conn_id = ftp_connect($ftp_server);
			$login_result = ftp_login($conn_id, $ftp_user, $ftp_pass);
			ftp_pasv($conn_id,true);
			ftp_chdir($conn_id,"Optipro");
			$directory=ftp_pwd($conn_id);
			echo '<br>File Deleted.';
			if ($INTERDICTIONDEFFACER  <> 'oui'){
				ftp_delete($conn_id,$remote_file);
			}else{
				echo 'Interdiction d effacer le fichier';	
			}
		}//End IF $InsererDansBD<>true
		
			

			// close the connection
			ftp_close($conn_id);

		
		echo '<br>----------------fin--------------------<br><br>';
		
	fclose($handle);
	

	

	
	
function EnvoyerEmailSucces($nomduFichierenReussi,$REFERENCE_EYELATION, $RxData, $ProdData,$USER_ID){
	$message='';
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
	font-size: 8pt;,
	font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.="<body><table width=\"950\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">La commande  <b>#$REFERENCE_EYELATION</b> a été importée avec succès.<br> File name: $nomduFichierenReussi<br></td>
	</tr>";
	
	
	
	$message.='</table>';
	
	$message .=$RxData;	
	$message .=$ProdData;	
	//Send EMAIL		
	$send_to_address = array('rapports@direct-lens.com');	
	echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "Optipro ". $USER_ID. ": succès: #$REFERENCE_EYELATION";
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	
}


function EnvoyerEmailErreur($nomduFichierenErreur,$ErrorDetail, $Eyelation_Order_Num,$RxData,$ProdData,$OrderNumberOptipro){
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
	$message.='<body><table width="950" cellpadding="2"  cellspacing="0" class="TextSize">';
	$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">An error occured during the importation process of an Optipro order: <b>$Eyelation_Order_Num</b>. Please check the Errors folder.<br> The filename that caused problem is: $nomduFichierenErreur<br>
	$ErrorDetail</td>
	</tr></table>";	
	
	$message .=$RxData;	
	$message .=$ProdData;	
	//Send EMAIL		
	$send_to_address = array('rapports@direct-lens.com');	
	echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "Erreur Optipro $OrderNumberOptipro --> $nomduFichierenErreur";
	if ($ImporterCetteCommande <> 'non'){
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
}





//Nouvelle fonction ci dessous [Avril 2019]
function EnvoyerEmailErreurSuccursale($nomduFichierenErreur,$ErrorDetail, $Eyelation_Order_Num,$RxData,$ProdData,$OrderNumberOptipro,$User_ID){
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
	
	
	
	
	
	
switch($User_ID){
		case '88403':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88403-Bloor St.";   	break;
		case '88408':  	$EmailSuccursale="dbeaulieu@direct-lens.com";	$DesignationSuccursale="#88408-Oshawa";    			break;
		case '88409':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88409-Eglinton";    		break;
		case '88414':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88414-Yorkdale";    		break;
		case '88416':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88416-Vancouver DTN";   	break;
		case '88430':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88430-St.Vital TEST";   break;//Utilisé pour des fins de test
		case '88431': 	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88431-Calgary DTN";    	break;
		case '88433': 	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88433-Polo Park";    		break;
		case '88434':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88434-Market Mall";    	break;
		case '88435':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88435-West Edmonton";  	break;
		case '88438':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88438-Metrotown";    		break;
		case '88439':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88439-Langley";    		break;
		case '88440':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88440-Rideau";    			break;
		case '88444':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; 	$DesignationSuccursale="#88444-Mayfair";    		break;
		//Griffé
		case '88666':  	$EmailSuccursale="trois-rivieres@griffelunetier.com"; $DesignationSuccursale="88666-Griffe";  		break;
		//USER ID VIDE
		case '':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; $DesignationSuccursale="Commande HBC sans USER ID!";break;
		case ' ':  	$EmailSuccursale="dbeaulieu@direct-lens.com"; $DesignationSuccursale="Commande HBC sans USER ID!";break;
		
	}		
	
if ($User_ID==''){
	$ErrorDetail.= '<br>Aucun nom d\'utilisateur..<br>';
	$InsererDansBD  = false;
}


	$message.='<body><table width="950" cellpadding="2"  cellspacing="0" class="TextSize">';
	$message.="<tr>
	<td align=\"center\"><p align=\"left\">Hi colleagues from HBC Store $DesignationSuccursale ! <br><br>
	There seems to be a problem with your Optipro Invoice #:<b>$Eyelation_Order_Num</b>.<br><br>
	The problem is the following: <b>$ErrorDetail</b><br><br>
	 Thanks to do the necessary updates, save and re-export the order.<br><br>
<b>Do not reply to this email directly(since it won't be received)</b><br>
 If you have question, create a new ticket.<br><br>
Have a nice day. 
	</p></td>
	</tr></table>";	
	
	
	
	//Config Courriel
	$from_address	='donotreply@entrepotdelalunette.com';
	$subject		= "Optipro Error #$OrderNumberOptipro HBC Store $DesignationSuccursale";
	$curTime		= date("m-d-Y");
	//Courriel a la succursale 
	$to_address=array("$EmailSuccursale");	
	if ($ImporterCetteCommande <> 'non'){
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
	
	//Copie par courriel pour Charles
	$theemail="dbeaulieu@direct-lens.com";
	$send_to_address=array("$theemail");	
	echo "<br>".$send_to_address;
	$to_address=$send_to_address;
	$subject		= "Optipro Error #$OrderNumberOptipro HBC Store $DesignationSuccursale (COPIE ADMIN)";
	if ($ImporterCetteCommande <> 'non'){
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
	

	
}//End Function


function EnvoyerEmailFramea0($OrderNumOptipro,$Compte,$queryPrice){
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
	$message.='<body><table width="950" cellpadding="2"  cellspacing="0" class="TextSize">';
	$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">$nomduFichierenErreur<br>Une commande de sécurité dont la monture a le champ frame_interco  a 0$. SVP corriger le montant et ajouter sa valeur dans la commande $OrderNumberOptipro.<br>
	$ErrorDetail</td>
	</tr></table>";	
	
	$message .=$RxData;	
	$message .=$ProdData;	
	$message .=$queryPrice;
	//Send EMAIL		
	$send_to_address = array('rapports@direct-lens.com');	
	echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "TRES IMPORTANT Optipro $OrderNumOptipro $Compte --> Frame à 0$: $nomduFichierenErreur";
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

function EnvoyerEmailFrameaCreer($OrderNumOptipro,$Compte,$queryPrice){
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
	$message.='<body><table width="950" cellpadding="2"  cellspacing="0" class="TextSize">';
	$message.="<tr bgcolor=\"CCCCCC\">
	<td align=\"center\">$nomduFichierenErreur<br> Une commande de sécurité dont la monture n'est pas créée à été transféré d'optipro. SVP créer la monture et ajouter sa valeur dans la commande $OrderNumberOptipro.<br>
	$ErrorDetail</td>
	</tr></table>";	
	
	$message .=$RxData;	
	$message .=$ProdData;	
	$message .=$queryPrice;
	//Send EMAIL		
	$send_to_address = array('rapports@direct-lens.com');	
	echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "TRES IMPORTANT Optipro $OrderNumOptipro $Compte --> Frame à creer: $nomduFichierenErreur";
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}


//Logger l'exécution du script
/*$time_end 		 = microtime(true);
$time 	  		 = $time_end - $time_start;
$today 			 = date("Y-m-d");
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Importation Optipro 2.0', '$time','$today','$heure_execution','script_importation_optipro.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));*/	
	
?>