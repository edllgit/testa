<?php
//TODO OPTIONNEL: AJOUTER DANS CE RAPPORT L'OPTION DE GÉNÉRER POUR TOUS LES MAGASINS D'UN CLIQUE, SANS DEVOIR REFAIRE LA SELECTION POUR CHAQUE SUCCURSALE
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');



//Définir les prix selon les zones couvertes

       
//ENVOI    DÉPART      --> DESTINATION       ZONE

//Chicoutimi
$ZoneICS[SiegeSocial][EDLL_Chicoutimi]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Chicoutimi]		= 103;//ICS
$ZoneICSRetourVersLabo[EDLL_Chicoutimi]  		= "C";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Chicoutimi]			= 509;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Chicoutimi]		= 510;//UPS

//Glasses Gallery Montreal
$ZoneICS[SiegeSocial][EDLL_Montreal]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Montreal]			= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Montreal]  			= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Montreal]			= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Montreal]			= 503;//UPS

//Trois-Rivieres
$ZoneICS[SiegeSocial][EDLL_TroisRivieres]		= 100;//ICS
$ZoneICS[LaboratoireSTC][EDLL_TroisRivieres]	= 103;//ICS
$ZoneICSRetourVersLabo[EDLL_TroisRivieres]  	= "C";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_TroisRivieres]		= 503;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_TroisRivieres]	= 503;//UPS

//Drummondville
$ZoneICS[SiegeSocial][EDLL_Drummondville]		= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Drummondville]	= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Drummondville]  	= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Drummondville]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Drummondville]	= 503;//UPS

//Granby
$ZoneICS[SiegeSocial][EDLL_Granby]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Granby]		= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Granby]  		= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Granby]			= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Granby]		= 503;//UPS

//Laval
$ZoneICS[SiegeSocial][EDLL_Laval]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Laval]		= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Laval]  		= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Laval]			= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Laval]		= 503;//UPS

//Lévis
$ZoneICS[SiegeSocial][EDLL_Levis]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Levis]		= 103;//ICS
$ZoneICSRetourVersLabo[EDLL_Levis]  		= "C";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Levis]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Levis]		= 504;//UPS

//Longueuil
$ZoneICS[SiegeSocial][EDLL_Longueuil]		= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Longueuil]	= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Longueuil]  	= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Longueuil]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Longueuil]	= 503;//UPS
 
//Québec
$ZoneICS[SiegeSocial][EDLL_Quebec]			= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Quebec]		= 103;//ICS
$ZoneICSRetourVersLabo[EDLL_Quebec]  		= "C";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Quebec]			= 501;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Quebec]		= 503;//UPS

//Sherbrooke
$ZoneICS[SiegeSocial][EDLL_Sherbrooke]		= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Sherbrooke]	= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Sherbrooke] 	= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Sherbrooke]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Sherbrooke]	= 503;//UPS

//Terrebonne
$ZoneICS[SiegeSocial][EDLL_Terrebonne]		= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Terrebonne]	= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Terrebonne] 	= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Terrebonne]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Terrebonne]	= 503;//UPS

//Halifax
$ZoneICS[SiegeSocial][OW_Halifax]			= 500;//ICS
$ZoneICS[LaboratoireSTC][OW_Halifax]		= 500;//ICS
$ZoneICSRetourVersLabo[OW_Halifax] 			= "E";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][OW_Halifax]			= 510;//UPS
$ZoneUPS[LaboratoireSTC][OW_Halifax]		= 509;//UPS

//Saint-Jérôme
$ZoneICS[SiegeSocial][EDLL_STJerome]		= 101;//ICS
$ZoneICS[LaboratoireSTC][EDLL_STJerome]		= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_STJerome] 		= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_STJerome]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_STJerome]		= 503;//UPS

//Gatineau
$ZoneICS[SiegeSocial][EDLL_Gatineau]		= 103;//ICS
$ZoneICS[LaboratoireSTC][EDLL_Gatineau]		= 102;//ICS
$ZoneICSRetourVersLabo[EDLL_Gatineau] 		= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][EDLL_Gatineau]		= 502;//UPS
$ZoneUPS[LaboratoireSTC][EDLL_Gatineau]		= 504;//UPS

//HBC #88403 Bloor
$ZoneICS[SiegeSocial][HBC_88403]			= 102;//ICS
$ZoneICS[LaboratoireSTC][HBC_88403]			= 101;//ICS
$ZoneICSRetourVersLabo[HBC_88403] 			= "A";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88403]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88403]			= 501;//UPS


//HBC #88408 Oshawa
$ZoneICS[SiegeSocial][HBC_88408]			= 102;//ICS
$ZoneICS[LaboratoireSTC][HBC_88408]			= 101;//ICS
$ZoneICSRetourVersLabo[HBC_88408] 			= "A";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88408]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88408]			= 501;//UPS

//HBC #88409 Eglinton
$ZoneICS[SiegeSocial][HBC_88409]			= 102;//ICS
$ZoneICS[LaboratoireSTC][HBC_88409]			= 101;//ICS
$ZoneICSRetourVersLabo[HBC_88409] 			= "A";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88409]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88409]			= 501;//UPS
 
//HBC #88411-Sherway
$ZoneICS[SiegeSocial][HBC_88411]			= 102;//ICS
$ZoneICS[LaboratoireSTC][HBC_88411]			= 101;//ICS
$ZoneICSRetourVersLabo[HBC_88411] 			= "A";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88411]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88411]			= 501;//UPS

//HBC #88414 Yorkdale
$ZoneICS[SiegeSocial][HBC_88414]			= 102;//ICS
$ZoneICS[LaboratoireSTC][HBC_88414]			= 101;//ICS
$ZoneICSRetourVersLabo[HBC_88414] 			= "A";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88414]			= 503;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88414]			= 501;//UPS

//HBC #88416-Vancouver
$ZoneICS[SiegeSocial][HBC_88416]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88416]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88416] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88416]			= 508;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88416]			= 507;//UPS

//HBC #88429-Saskatoon
$ZoneICS[SiegeSocial][HBC_88429]			= 600;//ICS
$ZoneICS[LaboratoireSTC][HBC_88429]			= 500;//ICS
$ZoneICSRetourVersLabo[HBC_88429] 			= "E";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88429]			= 505;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88429]			= 505;//UPS

//HBC #88431-Calgary DTN
$ZoneICS[SiegeSocial][HBC_88431]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88431]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88431] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88431]			= 507;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88431]			= 506;//UPS

//HBC #88433-Polo Park
$ZoneICS[SiegeSocial][HBC_88433]			= 500;//ICS
$ZoneICS[LaboratoireSTC][HBC_88433]			= 400;//ICS
$ZoneICSRetourVersLabo[HBC_88433] 			= "D";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88433]			= 505;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88433]			= 504;//UPS


//HBC #88434-Market Mall
$ZoneICS[SiegeSocial][HBC_88434]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88434]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88434] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88434]			= 507;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88434]			= 506;//UPS

//HBC #88435-West Edmonton
$ZoneICS[SiegeSocial][HBC_88435]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88435]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88435] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88435]			= 507;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88435]			= 506;//UPS

//HBC #88438-Metrotown
$ZoneICS[SiegeSocial][HBC_88438]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88438]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88438] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88438]			= 508;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88438]			= 507;//UPS

//HBC #88439-Langley
$ZoneICS[SiegeSocial][HBC_88439]			= 601;//ICS
$ZoneICS[LaboratoireSTC][HBC_88439]			= 601;//ICS
$ZoneICSRetourVersLabo[HBC_88439] 			= "F";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88439]			= 508;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88439]			= 507;//UPS

//HBC #88440-Rideau
$ZoneICS[SiegeSocial][HBC_88440]			= 103;//ICS
$ZoneICS[LaboratoireSTC][HBC_88440]			= 102;//ICS
$ZoneICSRetourVersLabo[HBC_88440] 			= "B";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88440]			= 502;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88440]			= 502;//UPS


//HBC #88444-Mayfair
$ZoneICS[SiegeSocial][HBC_88444]			= 602;//ICS
$ZoneICS[LaboratoireSTC][HBC_88444]			= 605;//ICS
$ZoneICSRetourVersLabo[HBC_88444] 			= "G";//Retour ICS en utilisant le bon pré-imprimé
$ZoneUPS[SiegeSocial][HBC_88444]			= 509;//UPS
$ZoneUPS[LaboratoireSTC][HBC_88444]			= 509;//UPS



//DÉFINITION DES ZONES ICS 

//Zone 100 (+0.30$) |  MAJ LE 20 Juillet 2020 (+0.31$)
$ICS_Zone_demi_kilo[100] 			= 4.57;
$ICS_Zone_kilo[100]	 				= 4.88;
$ICS_Zone_kilo_demi[100] 			= 5.19;
$ICS_Zone_deux_kilos[100]			= 5.50;
$ICS_Zone_deux_kilos_demi[100]		= 5.81;
$ICS_Zone_trois_kilos[100]			= 6.12;
$ICS_Zone_trois_kilos_demi[100]		= 6.43;
$ICS_Zone_quatre_kilos[100]			= 6.74;
$ICS_Zone_quatre_kilos_demi[100]	= 7.05;
$ICS_Zone_cinq_kilos[100]			= 7.36;
$ICS_Zone_cinq_kilos_demi[100]		= 7.67;
$ICS_Zone_six_kilos[100]			= 7.98;
$ICS_Zone_six_kilos_demi[100]		= 8.29;
$ICS_Zone_sept_kilos[100]			= 8.60;
$ICS_Zone_sept_kilos_demi[100]		= 8.91;
$ICS_Zone_huit_kilos[100]			= 9.22;
$ICS_Zone_huit_kilos_demi[100]		= 9.53;
$ICS_Zone_neuf_kilos[100]			= 9.84;
$ICS_Zone_neuf_kilos_demi[100]		= 10.15;
$ICS_Zone_dix_kilos[100]			= 10.46;
$ICS_Zone_dix_kilos_demi[100]		= 10.77;
$ICS_Zone_onze_kilos[100]			= 11.08;
$ICS_Zone_onze_kilos_demi[100]		= 11.39;
$ICS_Zone_douze_kilos[100]			= 11.70;
$ICS_Zone_douze_kilos_demi[100]		= 12.01;
$ICS_Zone_treize_kilos[100]			= 12.32;
$ICS_Zone_treize_kilos_demi[100]	= 12.63;
$ICS_Zone_quatorze_kilos[100]		= 12.94;
$ICS_Zone_quatorze_kilos_demi[100]	= 13.25;
$ICS_Zone_quinze_kilos[100]			= 13.56;
$ICS_Zone_quinze_kilos_demi[100]	= 13.87;
$ICS_Zone_seize_kilos[100]			= 14.18;
$ICS_Zone_seize_kilos_demi[100]		= 14.49;
$ICS_Zone_dixsept_kilos[100]		= 14.80;
$ICS_Zone_dixsept_kilos_demi[100]	= 15.11;
$ICS_Zone_dixhuit_kilos[100]		= 15.42;
$ICS_Zone_dixhuit_kilos_demi[100]	= 15.73;
$ICS_Zone_dixneuf_kilos[100]		= 16.04;
$ICS_Zone_dixneuf_kilos_demi[100]	= 16.35;
$ICS_Zone_vingt_kilos[100]			= 16.66;
	

//Zone 101 (+0.30$) |  MAJ LE 20 Juillet 2020 (+0.31$)
$ICS_Zone_demi_kilo[101] 			= 4.71;
$ICS_Zone_kilo[101] 				= 5.02;
$ICS_Zone_kilo_demi[101] 			= 5.33;
$ICS_Zone_deux_kilos[101]			= 5.64;
$ICS_Zone_deux_kilos_demi[101]		= 5.95;
$ICS_Zone_trois_kilos[101]			= 6.26;
$ICS_Zone_trois_kilos_demi[101]		= 6.57;
$ICS_Zone_quatre_kilos[101]			= 6.88;
$ICS_Zone_quatre_kilos_demi[101]	= 7.19;
$ICS_Zone_cinq_kilos[101]			= 7.50;
$ICS_Zone_cinq_kilos_demi[101]		= 7.81;
$ICS_Zone_six_kilos[101]			= 8.12;
$ICS_Zone_six_kilos_demi[101]		= 8.43;
$ICS_Zone_sept_kilos[101]			= 8.74;
$ICS_Zone_sept_kilos_demi[101]		= 9.05;
$ICS_Zone_huit_kilos[101]			= 9.36;
$ICS_Zone_huit_kilos_demi[101]		= 9.67;
$ICS_Zone_neuf_kilos[101]			= 9.98;
$ICS_Zone_neuf_kilos_demi[101]		= 10.29;
$ICS_Zone_dix_kilos[101]			= 10.60;
$ICS_Zone_dix_kilos_demi[101]		= 10.91;
$ICS_Zone_onze_kilos[101]			= 11.22;
$ICS_Zone_onze_kilos_demi[101]		= 11.53;
$ICS_Zone_douze_kilos[101]			= 11.84;
$ICS_Zone_douze_kilos_demi[101]		= 12.15;
$ICS_Zone_treize_kilos[101]			= 12.46;
$ICS_Zone_treize_kilos_demi[101]	= 12.77;
$ICS_Zone_quatorze_kilos[101]		= 13.08;
$ICS_Zone_quatorze_kilos_demi[101]	= 13.39;
$ICS_Zone_quinze_kilos[101]			= 13.70;
$ICS_Zone_quinze_kilos_demi[101]	= 14.01;
$ICS_Zone_seize_kilos[101]			= 14.32;
$ICS_Zone_seize_kilos_demi[101]		= 14.63;
$ICS_Zone_dixsept_kilos[101]		= 14.94;
$ICS_Zone_dixsept_kilos_demi[101]	= 15.25;
$ICS_Zone_dixhuit_kilos[101]		= 15.56;
$ICS_Zone_dixhuit_kilos_demi[101]	= 15.87;
$ICS_Zone_dixneuf_kilos[101]		= 16.18;
$ICS_Zone_dixneuf_kilos_demi[101]	= 16.49;
$ICS_Zone_vingt_kilos[101]			= 16.80;


//Zone 102 (+0.31$) |  MAJ LE 20 Juillet 2020 (+0.32$)
$ICS_Zone_demi_kilo[102]			= 4.86;
$ICS_Zone_kilo[102] 				= 5.18;
$ICS_Zone_kilo_demi[102] 			= 5.50;
$ICS_Zone_deux_kilos[102]			= 5.82;
$ICS_Zone_deux_kilos_demi[102]		= 6.14;
$ICS_Zone_trois_kilos[102]			= 6.46;
$ICS_Zone_trois_kilos_demi[102]		= 6.78;
$ICS_Zone_quatre_kilos[102]			= 7.10;
$ICS_Zone_quatre_kilos_demi[102]	= 7.42;
$ICS_Zone_cinq_kilos[102]			= 7.74;
$ICS_Zone_cinq_kilos_demi[102]		= 8.06;
$ICS_Zone_six_kilos[102]			= 8.38;
$ICS_Zone_six_kilos_demi[102]		= 8.70;
$ICS_Zone_sept_kilos[102]			= 9.02;
$ICS_Zone_sept_kilos_demi[102]		= 9.34;
$ICS_Zone_huit_kilos[102]			= 9.66;
$ICS_Zone_huit_kilos_demi[102]		= 9.98;
$ICS_Zone_neuf_kilos[102]			= 10.30;
$ICS_Zone_neuf_kilos_demi[102]		= 10.62;
$ICS_Zone_dix_kilos[102]			= 10.94;
$ICS_Zone_dix_kilos_demi[102]		= 11.26;
$ICS_Zone_onze_kilos[102]			= 11.58;
$ICS_Zone_onze_kilos_demi[102]		= 11.90;
$ICS_Zone_douze_kilos[102]			= 12.22;
$ICS_Zone_douze_kilos_demi[102]		= 12.54;
$ICS_Zone_treize_kilos[102]			= 12.86;
$ICS_Zone_treize_kilos_demi[102]	= 13.18;
$ICS_Zone_quatorze_kilos[102]		= 13.50;
$ICS_Zone_quatorze_kilos_demi[102]	= 13.82;
$ICS_Zone_quinze_kilos[102]			= 14.14;
$ICS_Zone_quinze_kilos_demi[102]	= 14.46;
$ICS_Zone_seize_kilos[102]			= 14.78;
$ICS_Zone_seize_kilos_demi[102]		= 15.10;
$ICS_Zone_dixsept_kilos[102]		= 15.42;
$ICS_Zone_dixsept_kilos_demi[102]	= 15.74;
$ICS_Zone_dixhuit_kilos[102]		= 16.06;
$ICS_Zone_dixhuit_kilos_demi[102]	= 16.38;
$ICS_Zone_dixneuf_kilos[102]		= 16.70;
$ICS_Zone_dixneuf_kilos_demi[102]	= 17.02;
$ICS_Zone_vingt_kilos[102]			= 17.34;




//Zone 103 (+0.31$)  |  MAJ LE 20 Juillet 2020 (+0.32$)
$ICS_Zone_demi_kilo[103] 			= 4.99;
$ICS_Zone_kilo[103] 				= 5.31;
$ICS_Zone_kilo_demi[103] 			= 5.63;
$ICS_Zone_deux_kilos[103]			= 5.95;
$ICS_Zone_deux_kilos_demi[103]		= 6.27;
$ICS_Zone_trois_kilos[103]			= 6.59;
$ICS_Zone_trois_kilos_demi[103]		= 6.91;
$ICS_Zone_quatre_kilos[103]			= 7.23;
$ICS_Zone_quatre_kilos_demi[103]	= 7.55;
$ICS_Zone_cinq_kilos[103]			= 7.87;
$ICS_Zone_cinq_kilos_demi[103]		= 8.19;
$ICS_Zone_six_kilos[103]			= 8.51;
$ICS_Zone_six_kilos_demi[103]		= 8.83;
$ICS_Zone_sept_kilos[103]			= 9.15;
$ICS_Zone_sept_kilos_demi[103]		= 9.47;
$ICS_Zone_huit_kilos[103]			= 9.79;
$ICS_Zone_huit_kilos_demi[103]		= 10.11;
$ICS_Zone_neuf_kilos[103]			= 10.43;
$ICS_Zone_neuf_kilos_demi[103]		= 10.75;
$ICS_Zone_dix_kilos[103]			= 11.07;
$ICS_Zone_dix_kilos_demi[103]		= 11.39;
$ICS_Zone_onze_kilos[103]			= 11.71;
$ICS_Zone_onze_kilos_demi[103]		= 12.03;
$ICS_Zone_douze_kilos[103]			= 12.35;
$ICS_Zone_douze_kilos_demi[103]		= 12.67;
$ICS_Zone_treize_kilos[103]			= 12.99;
$ICS_Zone_treize_kilos_demi[103]	= 13.31;
$ICS_Zone_quatorze_kilos[103]		= 13.63;
$ICS_Zone_quatorze_kilos_demi[103]	= 13.95;
$ICS_Zone_quinze_kilos[103]			= 14.27;
$ICS_Zone_quinze_kilos_demi[103]	= 14.59;
$ICS_Zone_seize_kilos[103]			= 14.91;
$ICS_Zone_seize_kilos_demi[103]		= 15.23;
$ICS_Zone_dixsept_kilos[103]		= 15.55;
$ICS_Zone_dixsept_kilos_demi[103]	= 15.87;
$ICS_Zone_dixhuit_kilos[103]		= 16.19;
$ICS_Zone_dixhuit_kilos_demi[103]	= 16.51;
$ICS_Zone_dixneuf_kilos[103]		= 16.83;
$ICS_Zone_dixneuf_kilos_demi[103]	= 17.15;
$ICS_Zone_vingt_kilos[103]			= 17.47;



//Zone 500 (+3.67$) |  MAJ LE 20 Juillet 2020 (+3.80$)
$ICS_Zone_demi_kilo[500] 			= 11.16;
$ICS_Zone_kilo[500] 				= 15.65;
$ICS_Zone_kilo_demi[500] 			= 19.45;
$ICS_Zone_deux_kilos[500] 			= 23.25;
$ICS_Zone_deux_kilos_demi[500]		= 27.05;
$ICS_Zone_trois_kilos[500]			= 30.85;
$ICS_Zone_trois_kilos_demi[500]		= 34.65;
$ICS_Zone_quatre_kilos[500]			= 38.45;
$ICS_Zone_quatre_kilos_demi[500]	= 42.25;
$ICS_Zone_cinq_kilos[500]			= 46.05;
$ICS_Zone_cinq_kilos_demi[500]		= 49.85;
$ICS_Zone_six_kilos[500]			= 53.65;
$ICS_Zone_six_kilos_demi[500]		= 57.45;
$ICS_Zone_sept_kilos[500]			= 61.25;
$ICS_Zone_sept_kilos_demi[500]		= 65.05;
$ICS_Zone_huit_kilos[500]			= 68.85;
$ICS_Zone_huit_kilos_demi[500]		= 72.65;
$ICS_Zone_neuf_kilos[500]			= 76.45;
$ICS_Zone_neuf_kilos_demi[500]		= 80.25;
$ICS_Zone_dix_kilos[500]			= 84.05;
$ICS_Zone_dix_kilos_demi[500]		= 87.85;
$ICS_Zone_onze_kilos[500]			= 91.65;
$ICS_Zone_onze_kilos_demi[500]		= 95.45;
$ICS_Zone_douze_kilos[500]			= 99.25;
$ICS_Zone_douze_kilos_demi[500]		= 103.05;
$ICS_Zone_treize_kilos[500]			= 106.85;
$ICS_Zone_treize_kilos_demi[500]	= 110.65;
$ICS_Zone_quatorze_kilos[500]		= 114.45;
$ICS_Zone_quatorze_kilos_demi[500]	= 118.25;
$ICS_Zone_quinze_kilos[500]			= 122.05;
$ICS_Zone_quinze_kilos_demi[500]	= 125.85;
$ICS_Zone_seize_kilos[500]			= 129.65;
$ICS_Zone_seize_kilos_demi[500]		= 133.45;
$ICS_Zone_dixsept_kilos[500]		= 137.25;
$ICS_Zone_dixsept_kilos_demi[500]	= 141.05;
$ICS_Zone_dixhuit_kilos[500]		= 144.85;
$ICS_Zone_dixhuit_kilos_demi[500]	= 148.65;
$ICS_Zone_dixneuf_kilos[500]		= 152.45;
$ICS_Zone_dixneuf_kilos_demi[500]	= 156.25;
$ICS_Zone_vingt_kilos[500]			= 160.05;



//Zone 600 (+3.69$)  |  MAJ LE 20 Juillet 2020 (+3.82$)
$ICS_Zone_demi_kilo[600] 			= 11.51;
$ICS_Zone_kilo[600] 				= 15.70;
$ICS_Zone_kilo_demi[600]			= 19.52;
$ICS_Zone_deux_kilos[600]			= 23.34;
$ICS_Zone_deux_kilos_demi[600]		= 27.16;
$ICS_Zone_trois_kilos[600]			= 30.98;
$ICS_Zone_trois_kilos_demi[600]		= 34.80;
$ICS_Zone_quatre_kilos[600]			= 38.62;
$ICS_Zone_quatre_kilos_demi[600]	= 42.44;
$ICS_Zone_cinq_kilos[600]			= 46.26;
$ICS_Zone_cinq_kilos_demi[600]		= 50.08;
$ICS_Zone_six_kilos[600]			= 53.90;
$ICS_Zone_six_kilos_demi[600]		= 57.72;
$ICS_Zone_sept_kilos[600]			= 61.54;
$ICS_Zone_sept_kilos_demi[600]		= 65.36;
$ICS_Zone_huit_kilos[600]			= 69.18;
$ICS_Zone_huit_kilos_demi[600]		= 73.00;
$ICS_Zone_neuf_kilos[600]			= 76.82;
$ICS_Zone_neuf_kilos_demi[600]		= 80.64;
$ICS_Zone_dix_kilos[600]			= 84.46;
$ICS_Zone_dix_kilos_demi[600]		= 88.28;
$ICS_Zone_onze_kilos[600]			= 92.10;
$ICS_Zone_onze_kilos_demi[600]		= 95.92;
$ICS_Zone_douze_kilos[600]			= 99.74;
$ICS_Zone_douze_kilos_demi[600]		= 103.56;
$ICS_Zone_treize_kilos[600]			= 107.38;
$ICS_Zone_treize_kilos_demi[600]	= 111.20;
$ICS_Zone_quatorze_kilos[600]		= 115.02;
$ICS_Zone_quatorze_kilos_demi[600]	= 118.84;
$ICS_Zone_quinze_kilos[600]			= 122.66;
$ICS_Zone_quinze_kilos_demi[600]	= 126.48;
$ICS_Zone_seize_kilos[600]			= 130.30;
$ICS_Zone_seize_kilos_demi[600]		= 134.12;
$ICS_Zone_dixsept_kilos[600]		= 137.94;
$ICS_Zone_dixsept_kilos_demi[600]	= 141.76;
$ICS_Zone_dixhuit_kilos[600]		= 145.58;
$ICS_Zone_dixhuit_kilos_demi[600]	= 149.40;
$ICS_Zone_dixneuf_kilos[600]		= 153.22;
$ICS_Zone_dixneuf_kilos_demi[600]	= 157.04;
$ICS_Zone_vingt_kilos[600]			= 160.86;


//Zone 601 (+3.80$)  |  MAJ LE 20 Juillet 2020 (+3.93$)
$ICS_Zone_demi_kilo[601] 			= 11.87;
$ICS_Zone_kilo[601] 				= 16.19;
$ICS_Zone_kilo_demi[601] 			= 20.10;
$ICS_Zone_deux_kilos[601]			= 24.01;
$ICS_Zone_deux_kilos_demi[601]		= 27.92;
$ICS_Zone_trois_kilos[601]			= 31.83;
$ICS_Zone_trois_kilos_demi[601]		= 35.74;
$ICS_Zone_quatre_kilos[601]			= 39.95;
$ICS_Zone_quatre_kilos_demi[601]	= 43.56;
$ICS_Zone_cinq_kilos[601]			= 47.47;
$ICS_Zone_cinq_kilos_demi[601]		= 51.38;
$ICS_Zone_six_kilos[601]			= 55.29;
$ICS_Zone_six_kilos_demi[601]		= 59.20;
$ICS_Zone_sept_kilos[601]			= 63.11;
$ICS_Zone_sept_kilos_demi[601]		= 67.02;
$ICS_Zone_huit_kilos[601]			= 70.93;
$ICS_Zone_huit_kilos_demi[601]		= 74.84;
$ICS_Zone_neuf_kilos[601]			= 78.75;
$ICS_Zone_neuf_kilos_demi[601]		= 82.66;
$ICS_Zone_dix_kilos[601]			= 86.57;
$ICS_Zone_dix_kilos_demi[601]		= 90.48;
$ICS_Zone_onze_kilos[601]			= 94.39;
$ICS_Zone_onze_kilos_demi[601]		= 98.30;
$ICS_Zone_douze_kilos[601]			= 102.21;
$ICS_Zone_douze_kilos_demi[601]		= 106.12;
$ICS_Zone_treize_kilos[601]			= 110.03;
$ICS_Zone_treize_kilos_demi[601]	= 113.94;
$ICS_Zone_quatorze_kilos[601]		= 117.85;
$ICS_Zone_quatorze_kilos_demi[601]	= 121.76;
$ICS_Zone_quinze_kilos[601]			= 125.67;
$ICS_Zone_quinze_kilos_demi[601]	= 129.58;
$ICS_Zone_seize_kilos[601]			= 133.49;
$ICS_Zone_seize_kilos_demi[601]		= 137.40;
$ICS_Zone_dixsept_kilos[601]		= 141.31;
$ICS_Zone_dixsept_kilos_demi[601]	= 145.31;
$ICS_Zone_dixhuit_kilos[601]		= 149.13;
$ICS_Zone_dixhuit_kilos_demi[601]	= 153.04;
$ICS_Zone_dixneuf_kilos[601]		= 156.95;
$ICS_Zone_dixneuf_kilos_demi[601]	= 160.86;
$ICS_Zone_vingt_kilos[601]			= 164.77;


//Zone 602 (+3.90$) |  MAJ LE 20 Juillet 2020 (+4.04$)
$ICS_Zone_demi_kilo[602] 			= 12.20;
$ICS_Zone_kilo[602]	 				= 16.64;
$ICS_Zone_kilo_demi[602] 			= 20.68;
$ICS_Zone_deux_kilos[602] 			= 24.72;
$ICS_Zone_deux_kilos_demi[602]		= 28.76;
$ICS_Zone_trois_kilos[602]			= 32.80;
$ICS_Zone_trois_kilos_demi[602]		= 36.84;
$ICS_Zone_quatre_kilos[602]			= 40.88;
$ICS_Zone_quatre_kilos_demi[602]	= 44.92;
$ICS_Zone_cinq_kilos[602]			= 48.96;
$ICS_Zone_cinq_kilos_demi[602]		= 53.00;
$ICS_Zone_six_kilos[602]			= 57.04;
$ICS_Zone_six_kilos_demi[602]		= 61.08;
$ICS_Zone_sept_kilos[602]			= 65.12;
$ICS_Zone_sept_kilos_demi[602]		= 69.16;
$ICS_Zone_huit_kilos[602]			= 73.20;
$ICS_Zone_huit_kilos_demi[602]		= 77.24;
$ICS_Zone_neuf_kilos[602]			= 81.28;
$ICS_Zone_neuf_kilos_demi[602]		= 85.32;
$ICS_Zone_dix_kilos[602]			= 89.36;
$ICS_Zone_dix_kilos_demi[602]		= 93.40;
$ICS_Zone_onze_kilos[602]			= 97.44;
$ICS_Zone_onze_kilos_demi[602]		= 101.48;
$ICS_Zone_douze_kilos[602]			= 105.52;
$ICS_Zone_douze_kilos_demi[602]		= 109.56;
$ICS_Zone_treize_kilos[602]			= 113.60;
$ICS_Zone_treize_kilos_demi[602]	= 117.64;
$ICS_Zone_quatorze_kilos[602]		= 121.68;
$ICS_Zone_quatorze_kilos_demi[602]	= 125.72;
$ICS_Zone_quinze_kilos[602]			= 129.76;
$ICS_Zone_quinze_kilos_demi[602]	= 133.80;
$ICS_Zone_seize_kilos[602]			= 137.84;
$ICS_Zone_seize_kilos_demi[602]		= 141.88;
$ICS_Zone_dixsept_kilos[602]		= 145.92;
$ICS_Zone_dixsept_kilos_demi[602]	= 149.96;
$ICS_Zone_dixhuit_kilos[602]		= 154.00;
$ICS_Zone_dixhuit_kilos_demi[602]	= 158.04;
$ICS_Zone_dixneuf_kilos[602]		= 162.08;
$ICS_Zone_dixneuf_kilos_demi[602]	= 166.12;
$ICS_Zone_vingt_kilos[602]			= 170.16;



//Zone 605 (+4.26$) |  MAJ LE 20 Juillet 2020 (+4.41$)
$ICS_Zone_demi_kilo[605]	 		= 13.34;
$ICS_Zone_kilo[605]	 				= 18.18;
$ICS_Zone_kilo_demi[605] 			= 22.59;
$ICS_Zone_deux_kilos[605] 			= 27.00;
$ICS_Zone_deux_kilos_demi[605]		= 31.41;
$ICS_Zone_trois_kilos[605]			= 35.82;
$ICS_Zone_trois_kilos_demi[605]		= 40.23;
$ICS_Zone_quatre_kilos[605]			= 44.64;
$ICS_Zone_quatre_kilos_demi[605]	= 49.05;
$ICS_Zone_cinq_kilos[605]			= 53.46;
$ICS_Zone_cinq_kilos_demi[605]		= 57.87;
$ICS_Zone_six_kilos[605]			= 62.28;
$ICS_Zone_six_kilos_demi[605]		= 66.69;
$ICS_Zone_sept_kilos[605]			= 71.10;
$ICS_Zone_sept_kilos_demi[605]		= 75.51;
$ICS_Zone_huit_kilos[605]			= 79.92;
$ICS_Zone_huit_kilos_demi[605]		= 84.33;
$ICS_Zone_neuf_kilos[605]			= 88.74;
$ICS_Zone_neuf_kilos_demi[605]		= 93.15;
$ICS_Zone_dix_kilos[605]			= 97.56;
$ICS_Zone_dix_kilos_demi[605]		= 101.97;
$ICS_Zone_onze_kilos[605]			= 106.38;
$ICS_Zone_onze_kilos_demi[605]		= 110.79;
$ICS_Zone_douze_kilos[605]			= 115.20;
$ICS_Zone_douze_kilos_demi[605]		= 119.61;
$ICS_Zone_treize_kilos[605]			= 124.02;
$ICS_Zone_treize_kilos_demi[605]	= 128.43;
$ICS_Zone_quatorze_kilos[605]		= 132.84;
$ICS_Zone_quatorze_kilos_demi[605]	= 137.25;
$ICS_Zone_quinze_kilos[605]			= 141.66;
$ICS_Zone_quinze_kilos_demi[605]	= 146.07;
$ICS_Zone_seize_kilos[605]			= 150.48;
$ICS_Zone_seize_kilos_demi[605]		= 154.89;
$ICS_Zone_dixsept_kilos[605]		= 159.30;
$ICS_Zone_dixsept_kilos_demi[605]	= 163.71;
$ICS_Zone_dixhuit_kilos[605]		= 168.12;
$ICS_Zone_dixhuit_kilos_demi[605]	= 172.53;
$ICS_Zone_dixneuf_kilos[605]		= 176.94;
$ICS_Zone_dixneuf_kilos_demi[605]	= 181.35;
$ICS_Zone_vingt_kilos[605]			= 185.76;


//Zone de retour ICS
//Zone 1000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
//(+ 0.48 sous par 500 grammes)
$ICS_Zone_demi_kilo[1000]	 		= 7.22;
$ICS_Zone_kilo[1000]	 			= 7.22;
$ICS_Zone_kilo_demi[1000]	 		= 7.70;
$ICS_Zone_deux_kilos[1000]	 		= 8.18;
$ICS_Zone_deux_kilos_demi[1000]	 	= 8.66;
$ICS_Zone_trois_kilos[1000]	 		= 9.14;
$ICS_Zone_trois_kilos_demi[1000]	= 9.62;
$ICS_Zone_quatre_kilos[1000]	 	= 10.10;
$ICS_Zone_quatre_kilos_demi[1000]	= 10.58;
$ICS_Zone_cinq_kilos[1000]	 		= 11.06;
$ICS_Zone_cinq_kilos_demi[1000]	 	= 11.54;
$ICS_Zone_six_kilos[1000]	 		= 12.02;
$ICS_Zone_six_kilos_demi[1000]	 	= 12.50;
$ICS_Zone_sept_kilos[1000]	 		= 12.98;
$ICS_Zone_sept_kilos_demi[1000]	 	= 13.46;
$ICS_Zone_huit_kilos[1000]	 		= 13.94;
$ICS_Zone_huit_kilos_demi[1000]	 	= 14.42;
$ICS_Zone_neuf_kilos[1000]	 		= 14.90;
$ICS_Zone_neuf_kilos_demi[1000]	 	= 15.38;
$ICS_Zone_dix_kilos[1000]	 		= 15.86;
$ICS_Zone_dix_kilos_demi[1000]	 	= 16.34;
$ICS_Zone_onze_kilos[1000]	 		= 16.82;
$ICS_Zone_onze_kilos_demi[1000]	 	= 17.30;
$ICS_Zone_douze_kilos[1000]	 		= 17.78;
$ICS_Zone_douze_kilos_demi[1000]	= 18.26;
$ICS_Zone_treize_kilos[1000]	 	= 18.74;
$ICS_Zone_treize_kilos_demi[1000]	= 19.22;
$ICS_Zone_quatorze_kilos[1000]	 	= 19.70;
$ICS_Zone_quatorze_kilos_demi[1000]	= 20.18;
$ICS_Zone_quinze_kilos[1000]	 	= 20.66;
$ICS_Zone_quinze_kilos_demi[1000]	= 21.14;
$ICS_Zone_seize_kilos[1000]	 		= 21.62;
$ICS_Zone_seize_kilos_demi[1000]	= 22.10;
$ICS_Zone_dixsept_kilos[1000]	 	= 22.58;
$ICS_Zone_dixsept_kilos_demi[1000]	= 23.06;
$ICS_Zone_dixhuit_kilos[1000]	 	= 23.54;
$ICS_Zone_dixhuit_kilos_demi[1000]	= 24.02;
$ICS_Zone_dixneuf_kilos[1000]	 	= 24.50;
$ICS_Zone_dixneuf_kilos_demi[1000]	= 24.98;
$ICS_Zone_vingt_kilos[1000]	 		= 25.46;


//Zone 1000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
//(+ 0.49 sous par 500 grammes)
$ICS_Zone_demi_kilo[2000]	 		= 7.44;
$ICS_Zone_kilo[2000]	 			= 7.44;
$ICS_Zone_kilo_demi[2000]	 		= 7.93;
$ICS_Zone_deux_kilos[2000]	 		= 8.42;
$ICS_Zone_deux_kilos_demi[2000]	 	= 8.91;
$ICS_Zone_trois_kilos[2000]	 		= 9.40;
$ICS_Zone_trois_kilos_demi[2000]	= 9.89;
$ICS_Zone_quatre_kilos[2000]	 	= 10.38;
$ICS_Zone_quatre_kilos_demi[2000]	= 10.87;
$ICS_Zone_cinq_kilos[2000]	 		= 11.36;
$ICS_Zone_cinq_kilos_demi[2000]	 	= 11.85;
$ICS_Zone_six_kilos[2000]	 		= 12.34;
$ICS_Zone_six_kilos_demi[2000]	 	= 12.83;
$ICS_Zone_sept_kilos[2000]	 		= 13.32;
$ICS_Zone_sept_kilos_demi[2000]	 	= 13.81;
$ICS_Zone_huit_kilos[2000]	 		= 14.30;
$ICS_Zone_huit_kilos_demi[2000]	 	= 14.79;
$ICS_Zone_neuf_kilos[2000]	 		= 15.28;
$ICS_Zone_neuf_kilos_demi[2000]	 	= 15.77;
$ICS_Zone_dix_kilos[2000]	 		= 16.26;
$ICS_Zone_dix_kilos_demi[2000]	 	= 16.75;
$ICS_Zone_onze_kilos[2000]	 		= 17.24;
$ICS_Zone_onze_kilos_demi[2000]	 	= 17.73;
$ICS_Zone_douze_kilos[2000]	 		= 18.22;
$ICS_Zone_douze_kilos_demi[2000]	= 18.71;
$ICS_Zone_treize_kilos[2000]	 	= 19.20;
$ICS_Zone_treize_kilos_demi[2000]	= 19.69;
$ICS_Zone_quatorze_kilos[2000]	 	= 20.18;
$ICS_Zone_quatorze_kilos_demi[2000]	= 20.67;
$ICS_Zone_quinze_kilos[2000]	 	= 21.16;
$ICS_Zone_quinze_kilos_demi[2000]	= 21.65;
$ICS_Zone_seize_kilos[2000]	 		= 22.14;
$ICS_Zone_seize_kilos_demi[2000]	= 22.63;
$ICS_Zone_dixsept_kilos[2000]	 	= 23.12;
$ICS_Zone_dixsept_kilos_demi[2000]	= 23.61;
$ICS_Zone_dixhuit_kilos[2000]	 	= 24.10;
$ICS_Zone_dixhuit_kilos_demi[2000]	= 24.59;
$ICS_Zone_dixneuf_kilos[2000]	 	= 25.08;
$ICS_Zone_dixneuf_kilos_demi[2000]	= 25.57;
$ICS_Zone_vingt_kilos[2000]	 		= 26.06;


//Zone 3000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
//Zone C (+ 0.49 sous par 500 grammes)
$ICS_Zone_demi_kilo[3000]	 		= 7.64;
$ICS_Zone_kilo[3000]	 			= 7.64;
$ICS_Zone_kilo_demi[3000]	 		= 8.13;
$ICS_Zone_deux_kilos[3000]	 		= 8.62;
$ICS_Zone_deux_kilos_demi[3000]	 	= 9.11;
$ICS_Zone_trois_kilos[3000]	 		= 9.60;
$ICS_Zone_trois_kilos_demi[3000]	= 10.09;
$ICS_Zone_quatre_kilos[3000]	 	= 10.58;
$ICS_Zone_quatre_kilos_demi[3000]	= 11.07;
$ICS_Zone_cinq_kilos[3000]	 		= 11.56;
$ICS_Zone_cinq_kilos_demi[3000]	 	= 12.05;
$ICS_Zone_six_kilos[3000]	 		= 12.54;
$ICS_Zone_six_kilos_demi[3000]	 	= 13.03;
$ICS_Zone_sept_kilos[3000]	 		= 13.52;
$ICS_Zone_sept_kilos_demi[3000]	 	= 14.01;
$ICS_Zone_huit_kilos[3000]	 		= 14.50;
$ICS_Zone_huit_kilos_demi[3000]	 	= 14.99;
$ICS_Zone_neuf_kilos[3000]	 		= 15.48;
$ICS_Zone_neuf_kilos_demi[3000]	 	= 15.97;
$ICS_Zone_dix_kilos[3000]	 		= 16.46;
$ICS_Zone_dix_kilos_demi[3000]	 	= 16.95;
$ICS_Zone_onze_kilos[3000]	 		= 17.44;
$ICS_Zone_onze_kilos_demi[3000]	 	= 17.93;
$ICS_Zone_douze_kilos[3000]	 		= 18.42;
$ICS_Zone_douze_kilos_demi[3000]	= 18.91;
$ICS_Zone_treize_kilos[3000]	 	= 19.40;
$ICS_Zone_treize_kilos_demi[3000]	= 19.89;
$ICS_Zone_quatorze_kilos[3000]	 	= 20.38;
$ICS_Zone_quatorze_kilos_demi[3000]	= 20.87;
$ICS_Zone_quinze_kilos[3000]	 	= 21.36;
$ICS_Zone_quinze_kilos_demi[3000]	= 21.85;
$ICS_Zone_seize_kilos[3000]	 		= 22.34;
$ICS_Zone_seize_kilos_demi[3000]	= 22.83;
$ICS_Zone_dixsept_kilos[3000]	 	= 23.32;
$ICS_Zone_dixsept_kilos_demi[3000]	= 23.81;
$ICS_Zone_dixhuit_kilos[3000]	 	= 24.30;
$ICS_Zone_dixhuit_kilos_demi[3000]	= 24.79;
$ICS_Zone_dixneuf_kilos[3000]	 	= 25.28;
$ICS_Zone_dixneuf_kilos_demi[3000]	= 25.77;
$ICS_Zone_vingt_kilos[3000]	 		= 26.26;


//Zone 4000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
//(+ 3.74  par 500 grammes)
$ICS_Zone_demi_kilo[4000]	 		= 10.94;
$ICS_Zone_kilo[4000]	 			= 15.35;
$ICS_Zone_kilo_demi[4000]	 		= 19.09;
$ICS_Zone_deux_kilos[4000]	 		= 22.83;
$ICS_Zone_deux_kilos_demi[4000]	 	= 26.57;
$ICS_Zone_trois_kilos[4000]	 		= 30.31;
$ICS_Zone_trois_kilos_demi[4000]	= 34.05;
$ICS_Zone_quatre_kilos[4000]	 	= 37.79;
$ICS_Zone_quatre_kilos_demi[4000]	= 41.53;
$ICS_Zone_cinq_kilos[4000]	 		= 45.27;
$ICS_Zone_cinq_kilos_demi[4000]	 	= 49.01;
$ICS_Zone_six_kilos[4000]	 		= 52.75;
$ICS_Zone_six_kilos_demi[4000]	 	= 56.49;
$ICS_Zone_sept_kilos[4000]	 		= 60.23;
$ICS_Zone_sept_kilos_demi[4000]	 	= 63.97;
$ICS_Zone_huit_kilos[4000]	 		= 67.71;
$ICS_Zone_huit_kilos_demi[4000]	 	= 71.45;
$ICS_Zone_neuf_kilos[4000]	 		= 75.19;
$ICS_Zone_neuf_kilos_demi[4000]	 	= 78.93;
$ICS_Zone_dix_kilos[4000]	 		= 82.67;
$ICS_Zone_dix_kilos_demi[4000]	 	= 86.41;
$ICS_Zone_onze_kilos[4000]	 		= 90.15;
$ICS_Zone_onze_kilos_demi[4000]	 	= 93.89;
$ICS_Zone_douze_kilos[4000]	 		= 97.63;
$ICS_Zone_douze_kilos_demi[4000]	= 101.37;
$ICS_Zone_treize_kilos[4000]	 	= 105.11;
$ICS_Zone_treize_kilos_demi[4000]	= 108.85;
$ICS_Zone_quatorze_kilos[4000]	 	= 112.59;
$ICS_Zone_quatorze_kilos_demi[4000]	= 116.33;
$ICS_Zone_quinze_kilos[4000]	 	= 120.07;
$ICS_Zone_quinze_kilos_demi[4000]	= 123.81;
$ICS_Zone_seize_kilos[4000]	 		= 127.55;
$ICS_Zone_seize_kilos_demi[4000]	= 131.29;
$ICS_Zone_dixsept_kilos[4000]	 	= 135.03;
$ICS_Zone_dixsept_kilos_demi[4000]	= 138.77;
$ICS_Zone_dixhuit_kilos[4000]	 	= 142.51;
$ICS_Zone_dixhuit_kilos_demi[4000]	= 146.25;
$ICS_Zone_dixneuf_kilos[4000]	 	= 149.99;
$ICS_Zone_dixneuf_kilos_demi[4000]	= 153.73;
$ICS_Zone_vingt_kilos[4000]	 		= 157.47;


//Zone 5000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
// (+ 3.76  par 500 grammes)
$ICS_Zone_demi_kilo[5000]	 		= 11.06;
$ICS_Zone_kilo[5000]	 			= 15.51;
$ICS_Zone_kilo_demi[5000]	 		= 19.27;
$ICS_Zone_deux_kilos[5000]	 		= 23.03;
$ICS_Zone_deux_kilos_demi[5000]	 	= 26.79;
$ICS_Zone_trois_kilos[5000]	 		= 30.55;
$ICS_Zone_trois_kilos_demi[5000]	= 34.31;
$ICS_Zone_quatre_kilos[5000]	 	= 38.07;
$ICS_Zone_quatre_kilos_demi[5000]	= 41.83;
$ICS_Zone_cinq_kilos[500]	 		= 45.59;
$ICS_Zone_cinq_kilos_demi[5000]	 	= 49.35;
$ICS_Zone_six_kilos[5000]	 		= 53.11;
$ICS_Zone_six_kilos_demi[5000]	 	= 56.87;
$ICS_Zone_sept_kilos[5000]	 		= 60.63;
$ICS_Zone_sept_kilos_demi[5000]	 	= 64.39;
$ICS_Zone_huit_kilos[5000]	 		= 68.15;
$ICS_Zone_huit_kilos_demi[5000]	 	= 71.91;
$ICS_Zone_neuf_kilos[5000]	 		= 75.67;
$ICS_Zone_neuf_kilos_demi[5000]	 	= 79.43;
$ICS_Zone_dix_kilos[5000]	 		= 83.19;
$ICS_Zone_dix_kilos_demi[5000]	 	= 86.95;
$ICS_Zone_onze_kilos[5000]	 		= 90.71;
$ICS_Zone_onze_kilos_demi[5000]	 	= 94.47;
$ICS_Zone_douze_kilos[5000]	 		= 98.23;
$ICS_Zone_douze_kilos_demi[5000]	= 101.99;
$ICS_Zone_treize_kilos[5000]	 	= 105.75;
$ICS_Zone_treize_kilos_demi[5000]	= 109.51;
$ICS_Zone_quatorze_kilos[5000]	 	= 113.27;
$ICS_Zone_quatorze_kilos_demi[5000]	= 117.03;
$ICS_Zone_quinze_kilos[5000]	 	= 120.79;
$ICS_Zone_quinze_kilos_demi[5000]	= 124.55;
$ICS_Zone_seize_kilos[5000]	 		= 128.31;
$ICS_Zone_seize_kilos_demi[5000]	= 132.07;
$ICS_Zone_dixsept_kilos[5000]	 	= 135.83;
$ICS_Zone_dixsept_kilos_demi[5000]	= 139.59;
$ICS_Zone_dixhuit_kilos[5000]	 	= 143.35;
$ICS_Zone_dixhuit_kilos_demi[5000]	= 147.11;
$ICS_Zone_dixneuf_kilos[5000]	 	= 150.87;
$ICS_Zone_dixneuf_kilos_demi[5000]	= 154.63;
$ICS_Zone_vingt_kilos[5000]	 		= 158.39;

//Zone 6000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
// (+3.90$ par 500 grammes)
$ICS_Zone_demi_kilo[6000]	 		= 11.77;
$ICS_Zone_kilo[6000]	 			= 16.04;
$ICS_Zone_kilo_demi[6000]	 		= 19.94;
$ICS_Zone_deux_kilos[6000]	 		= 23.84;
$ICS_Zone_deux_kilos_demi[6000]	 	= 27.74;
$ICS_Zone_trois_kilos[6000]	 		= 31.64;
$ICS_Zone_trois_kilos_demi[6000]	= 35.54;
$ICS_Zone_quatre_kilos[6000]	 	= 39.44;
$ICS_Zone_quatre_kilos_demi[6000]	= 43.34;
$ICS_Zone_cinq_kilos[6000]	 		= 47.24;
$ICS_Zone_cinq_kilos_demi[6000]	 	= 51.14;
$ICS_Zone_six_kilos[6000]	 		= 55.04;
$ICS_Zone_six_kilos_demi[6000]	 	= 58.94;
$ICS_Zone_sept_kilos[6000]	 		= 62.84;
$ICS_Zone_sept_kilos_demi[6000]	 	= 66.74;
$ICS_Zone_huit_kilos[6000]	 		= 70.64;
$ICS_Zone_huit_kilos_demi[6000]	 	= 74.54;
$ICS_Zone_neuf_kilos[6000]	 		= 78.44;
$ICS_Zone_neuf_kilos_demi[6000]	 	= 82.34;
$ICS_Zone_dix_kilos[6000]	 		= 86.24;
$ICS_Zone_dix_kilos_demi[6000]	 	= 90.14;
$ICS_Zone_onze_kilos[6000]	 		= 94.04;
$ICS_Zone_onze_kilos_demi[6000]	 	= 97.94;
$ICS_Zone_douze_kilos[6000]	 		= 101.84;
$ICS_Zone_douze_kilos_demi[6000] 	= 105.74;
$ICS_Zone_treize_kilos[6000]	 	= 109.64;
$ICS_Zone_treize_kilos_demi[6000]	= 113.54;
$ICS_Zone_quatorze_kilos[6000]	 	= 117.44;
$ICS_Zone_quatorze_kilos_demi[6000]	= 121.34;
$ICS_Zone_quinze_kilos[6000]	 	= 125.24;
$ICS_Zone_quinze_kilos_demi[6000]	= 129.14;
$ICS_Zone_seize_kilos[6000]	 		= 133.04;
$ICS_Zone_seize_kilos_demi[6000]	= 136.94;
$ICS_Zone_dixsept_kilos[6000]	 	= 140.84;
$ICS_Zone_dixsept_kilos_demi[6000]	= 144.74;
$ICS_Zone_dixhuit_kilos[6000]	 	= 148.64;
$ICS_Zone_dixhuit_kilos_demi[6000]	= 152.54;
$ICS_Zone_dixneuf_kilos[6000]	 	= 156.44;
$ICS_Zone_dixneuf_kilos_demi[6000]	= 160.34;
$ICS_Zone_vingt_kilos[6000]	 		= 164.24;


//Zone 7000 [Créé par Charles pour les zones de 'Retour' vers le laboratoire de STC] 
// (+4.37$ par 500 grammes)
$ICS_Zone_demi_kilo[7000]	 		= 13.22;
$ICS_Zone_kilo[7000]	 			= 18.02;
$ICS_Zone_kilo_demi[7000]	 		= 22.39;
$ICS_Zone_deux_kilos[7000]	 		= 26.76;
$ICS_Zone_deux_kilos_demi[7000]	 	= 31.13;
$ICS_Zone_trois_kilos[7000]	 		= 35.50;
$ICS_Zone_trois_kilos_demi[7000]	= 39.87;
$ICS_Zone_quatre_kilos[7000]	 	= 44.24;
$ICS_Zone_quatre_kilos_demi[7000]	= 48.61;
$ICS_Zone_cinq_kilos[7000]	 		= 52.98;
$ICS_Zone_cinq_kilos_demi[7000]	 	= 57.35;
$ICS_Zone_six_kilos[7000]	 		= 61.72;
$ICS_Zone_six_kilos_demi[7000]	 	= 66.09;
$ICS_Zone_sept_kilos_demi[7000]	 	= 70.46;
$ICS_Zone_huit_kilos[7000]	 		= 74.83;
$ICS_Zone_huit_kilos_demi[7000]	 	= 79.20;
$ICS_Zone_neuf_kilos[7000]	 		= 83.57;
$ICS_Zone_neuf_kilos_demi[7000]	 	= 87.94;
$ICS_Zone_dix_kilos[7000]	 		= 92.31;
$ICS_Zone_dix_kilos_demi[7000]	 	= 96.68;
$ICS_Zone_onze_kilos[7000]	 		= 101.05;
$ICS_Zone_onze_kilos_demi[7000]	 	= 105.42;
$ICS_Zone_douze_kilos[7000]	 		= 109.79;
$ICS_Zone_douze_kilos_demi[7000] 	= 114.16;
$ICS_Zone_treize_kilos[7000]	 	= 118.53;
$ICS_Zone_treize_kilos_demi[7000]	= 122.90;
$ICS_Zone_quatorze_kilos[7000]	 	= 127.27;
$ICS_Zone_quatorze_kilos_demi[7000]	= 131.64;
$ICS_Zone_quinze_kilos[7000]	 	= 136.01;
$ICS_Zone_quinze_kilos_demi[7000]	= 140.38;
$ICS_Zone_seize_kilos[7000]	 		= 144.75;
$ICS_Zone_seize_kilos_demi[7000]	= 149.12;
$ICS_Zone_dixsept_kilos[7000]	 	= 153.49;
$ICS_Zone_dixsept_kilos_demi[7000]	= 157.86;
$ICS_Zone_dixhuit_kilos[700]	 	= 162.23;
$ICS_Zone_dixhuit_kilos_demi[7000]	= 166.60;
$ICS_Zone_dixneuf_kilos[7000]	 	= 170.97;
$ICS_Zone_dixneuf_kilos_demi[7000]	= 175.34;
$ICS_Zone_vingt_kilos[7000]	 		= 179.71;


//DÉFINITION DES ZONES UPS 

//Zone 501
$UPS_Zone_demi_kilo[501] 			= 10.80;
$UPS_Zone_kilo[501] 				= 11.08;
$UPS_Zone_kilo_demi[501] 			= 11.18;
$UPS_Zone_deux_kilos[501] 			= 11.48;
$UPS_Zone_deux_kilos_demi[501]		= 11.74;
$UPS_Zone_trois_kilos[501]			= 12.04;
$UPS_Zone_trois_kilos_demi[501]		= 12.39;
$UPS_Zone_quatre_kilos[501]			= 12.81;
$UPS_Zone_quatre_kilos_demi[501]	= 13.58;
$UPS_Zone_cinq_kilos[501]			= 13.86;
$UPS_Zone_cinq_kilos_demi[501]		= 14.21;
$UPS_Zone_six_kilos[501]			= 14.61;
$UPS_Zone_six_kilos_demi[501]		= 14.96;
$UPS_Zone_sept_kilos[501]			= 15.33;
$UPS_Zone_sept_kilos_demi[501]		= 15.75;
$UPS_Zone_huit_kilos[501]			= 16.05;
$UPS_Zone_huit_kilos_demi[501]		= 16.36;
$UPS_Zone_neuf_kilos[501]			= 17.19;
$UPS_Zone_neuf_kilos_demi[501]		= 17.83;
$UPS_Zone_dix_kilos[501]			= 18.20;
//Nouveaux ajouts QUE JE DOIS  pricer pour UPS
$UPS_Zone_dix_kilos_demi[501]		= 18.52;
$UPS_Zone_onze_kilos[501]			= 18.85;
$UPS_Zone_onze_kilos_demi[501]		= 19.20;
$UPS_Zone_douze_kilos[501]			= 19.50;
$UPS_Zone_douze_kilos_demi[501]		= 19.81;
$UPS_Zone_treize_kilos[501]			= 20.14;
$UPS_Zone_treize_kilos_demi[501]	= 20.48;
$UPS_Zone_quatorze_kilos[501]		= 20.88;
$UPS_Zone_quatorze_kilos_demi[501]	= 21.33;
$UPS_Zone_quinze_kilos[501]			= 21.40;
$UPS_Zone_quinze_kilos_demi[501]	= 21.79;
$UPS_Zone_seize_kilos[501]			= 22.07;
$UPS_Zone_seize_kilos_demi[501]		= 22.42;
$UPS_Zone_dixsept_kilos[501]		= 22.80;
$UPS_Zone_dixsept_kilos_demi[501]	= 23.21;
$UPS_Zone_dixhuit_kilos[501]		= 23.54;
$UPS_Zone_dixhuit_kilos_demi[501]	= 23.89;
$UPS_Zone_dixneuf_kilos[501]		= 23.92;
$UPS_Zone_dixneuf_kilos_demi[501]	= 25.41;
$UPS_Zone_vingt_kilos[501]			= 25.59;


//Zone 502 
$UPS_Zone_demi_kilo[502] 			= 10.82;
$UPS_Zone_kilo[502] 				= 11.10;
$UPS_Zone_kilo_demi[502] 			= 11.29;
$UPS_Zone_deux_kilos[502] 			= 11.50;
$UPS_Zone_deux_kilos_demi[502]		= 11.80;
$UPS_Zone_trois_kilos[502]			= 12.13;
$UPS_Zone_trois_kilos_demi[502]		= 12.48;
$UPS_Zone_quatre_kilos[502]			= 12.88;
$UPS_Zone_quatre_kilos_demi[502]	= 13.62;
$UPS_Zone_cinq_kilos[502]			= 14.23;
$UPS_Zone_cinq_kilos_demi[502]		= 14.63;
$UPS_Zone_six_kilos[502]			= 15.07;
$UPS_Zone_six_kilos_demi[502]		= 15.44;
$UPS_Zone_sept_kilos[502]			= 15.82;
$UPS_Zone_sept_kilos_demi[502]		= 16.22;
$UPS_Zone_huit_kilos[502]			= 16.54;
$UPS_Zone_huit_kilos_demi[502]		= 16.87;
$UPS_Zone_neuf_kilos[502]			= 17.20;
$UPS_Zone_neuf_kilos_demi[502]		= 17.85;
$UPS_Zone_dix_kilos[502]			= 18.24;
$UPS_Zone_dix_kilos_demi[502]		= 18.53;
$UPS_Zone_onze_kilos[502]			= 18.87;
$UPS_Zone_onze_kilos_demi[502]		= 19.23;
$UPS_Zone_douze_kilos[502]			= 19.57;
$UPS_Zone_douze_kilos_demi[502]		= 19.88;
$UPS_Zone_treize_kilos[502]			= 20.16;
$UPS_Zone_treize_kilos_demi[502]	= 20.51;
$UPS_Zone_quatorze_kilos[502]		= 20.91;
$UPS_Zone_quatorze_kilos_demi[502]	= 21.54;
$UPS_Zone_quinze_kilos[502]			= 21.88;
$UPS_Zone_quinze_kilos_demi[502]	= 22.30;
$UPS_Zone_seize_kilos[502]			= 22.58;
$UPS_Zone_seize_kilos_demi[502]		= 22.91;
$UPS_Zone_dixsept_kilos[502]		= 23.28;
$UPS_Zone_dixsept_kilos_demi[502]	= 23.61;
$UPS_Zone_dixhuit_kilos[502]		= 23.94;
$UPS_Zone_dixhuit_kilos_demi[502]	= 24.31;
$UPS_Zone_dixneuf_kilos[502]		= 24.99;
$UPS_Zone_dixneuf_kilos_demi[502]	= 25.76;
$UPS_Zone_vingt_kilos[502]			= 26.11;


//Zone 503
$UPS_Zone_demi_kilo[503] 			= 11.74;
$UPS_Zone_kilo[503] 				= 12.01;
$UPS_Zone_kilo_demi[503] 			= 12.13;
$UPS_Zone_deux_kilos[503] 			= 12.20;
$UPS_Zone_deux_kilos_demi[503]		= 12.25; 
$UPS_Zone_trois_kilos[503]			= 12.69;
$UPS_Zone_trois_kilos_demi[503]		= 13.14;
$UPS_Zone_quatre_kilos[503]			= 13.46;
$UPS_Zone_quatre_kilos_demi[503]	= 14.02;
$UPS_Zone_cinq_kilos[503]			= 14.61;
$UPS_Zone_cinq_kilos_demi[503]		= 14.96; 
$UPS_Zone_six_kilos[503]			= 15.26; 
$UPS_Zone_six_kilos_demi[503]		= 15.59;
$UPS_Zone_sept_kilos[503]			= 15.89;
$UPS_Zone_sept_kilos_demi[503]		= 16.36;
$UPS_Zone_huit_kilos[503]			= 16.68;
$UPS_Zone_huit_kilos_demi[503]		= 17.06;
$UPS_Zone_neuf_kilos[503]			= 17.64;
$UPS_Zone_neuf_kilos_demi[503]		= 18.69;
$UPS_Zone_dix_kilos[503]			= 18.87;
$UPS_Zone_dix_kilos_demi[503]		= 19.25;
$UPS_Zone_onze_kilos[503]			= 19.50;
$UPS_Zone_onze_kilos_demi[503]		= 19.93;
$UPS_Zone_douze_kilos[503]			= 20.41;
$UPS_Zone_douze_kilos_demi[503]		= 20.67;
$UPS_Zone_treize_kilos[503]			= 21.09;
$UPS_Zone_treize_kilos_demi[503]	= 21.42;
$UPS_Zone_quatorze_kilos[503]		= 21.81;
$UPS_Zone_quatorze_kilos_demi[503]	= 22.54;
$UPS_Zone_quinze_kilos[503]			= 22.91;
$UPS_Zone_quinze_kilos_demi[503]	= 23.35;
$UPS_Zone_seize_kilos[503]			= 23.84;
$UPS_Zone_seize_kilos_demi[503]		= 23.92;
$UPS_Zone_dixsept_kilos[503]		= 24.20;
$UPS_Zone_dixsept_kilos_demi[503]	= 24.71;
$UPS_Zone_dixhuit_kilos[503]		= 24.87;
$UPS_Zone_dixhuit_kilos_demi[503]	= 24.97;
$UPS_Zone_dixneuf_kilos[503]		= 25.74;
$UPS_Zone_dixneuf_kilos_demi[503]	= 26.50;
$UPS_Zone_vingt_kilos[503]			= 26.78;

//Zone 504
$UPS_Zone_demi_kilo[504] 			= 13.72;
$UPS_Zone_kilo[504] 				= 15.10;
$UPS_Zone_kilo_demi[504] 			= 16.50;
$UPS_Zone_deux_kilos[504] 			= 17.85;
$UPS_Zone_deux_kilos_demi[504]		= 19.08;
$UPS_Zone_trois_kilos[504]			= 20.25; 
$UPS_Zone_trois_kilos_demi[504]		= 21.42; 
$UPS_Zone_quatre_kilos[504]			= 22.75; 
$UPS_Zone_quatre_kilos_demi[504]	= 25.31;
$UPS_Zone_cinq_kilos[504]			= 26.83;
$UPS_Zone_cinq_kilos_demi[504]		= 28.21;
$UPS_Zone_six_kilos[504]			= 29.51;
$UPS_Zone_six_kilos_demi[504]		= 30.85;
$UPS_Zone_sept_kilos[504]			= 32.22;
$UPS_Zone_sept_kilos_demi[504]		= 33.51;
$UPS_Zone_huit_kilos[504]			= 34.91;
$UPS_Zone_huit_kilos_demi[504]		= 36.23;
$UPS_Zone_neuf_kilos[504]			= 37.61;
$UPS_Zone_neuf_kilos_demi[504]		= 40.34;
$UPS_Zone_dix_kilos[504]			= 41.60;
$UPS_Zone_dix_kilos_demi[504]		= 42.89;
$UPS_Zone_onze_kilos[504]			= 44.24;
$UPS_Zone_onze_kilos_demi[504]		= 45.59;
$UPS_Zone_douze_kilos[504]			= 46.97;
$UPS_Zone_douze_kilos_demi[504]		= 48.37;
$UPS_Zone_treize_kilos[504]			= 49.68;
$UPS_Zone_treize_kilos_demi[504]	= 51.07;
$UPS_Zone_quatorze_kilos[504]		= 52.40;
$UPS_Zone_quatorze_kilos_demi[504]	= 55.23;
$UPS_Zone_quinze_kilos[504]			= 56.65;
$UPS_Zone_quinze_kilos_demi[504]	= 58.12;
$UPS_Zone_seize_kilos[504]			= 59.57;
$UPS_Zone_seize_kilos_demi[504]		= 61.02;
$UPS_Zone_dixsept_kilos[504]		= 62.51;
$UPS_Zone_dixsept_kilos_demi[504]	= 63.98;
$UPS_Zone_dixhuit_kilos[504]		= 65.40;
$UPS_Zone_dixhuit_kilos_demi[504]	= 66.83;
$UPS_Zone_dixneuf_kilos[504]		= 68.29;
$UPS_Zone_dixneuf_kilos_demi[504]	= 71.23;
$UPS_Zone_vingt_kilos[504]			= 72.77;

//Zone 505 
$UPS_Zone_demi_kilo[505] 			= 14.07;
$UPS_Zone_kilo[505] 				= 15.30;
$UPS_Zone_kilo_demi[505] 			= 16.56;
$UPS_Zone_deux_kilos[505] 			= 17.87;
$UPS_Zone_deux_kilos_demi[505]		= 19.25;
$UPS_Zone_trois_kilos[505]			= 20.69;
$UPS_Zone_trois_kilos_demi[505]		= 22.00;
$UPS_Zone_quatre_kilos[505]			= 23.38;
$UPS_Zone_quatre_kilos_demi[505]	= 26.06;
$UPS_Zone_cinq_kilos[505]			= 27.56;
$UPS_Zone_cinq_kilos_demi[505]		= 28.91;
$UPS_Zone_six_kilos[505]			= 30.26;
$UPS_Zone_six_kilos_demi[505]		= 31.68;
$UPS_Zone_sept_kilos[505]			= 33.01;
$UPS_Zone_sept_kilos_demi[505]		= 34.42;
$UPS_Zone_huit_kilos[505]			= 35.74;
$UPS_Zone_huit_kilos_demi[505]		= 37.05;
$UPS_Zone_neuf_kilos[505]			= 38.47;
$UPS_Zone_neuf_kilos_demi[505]		= 41.18;
$UPS_Zone_dix_kilos[505]			= 42.58;
$UPS_Zone_dix_kilos_demi[505]		= 43.94;
$UPS_Zone_onze_kilos[505]			= 45.40;
$UPS_Zone_onze_kilos_demi[505]		= 46.69;
$UPS_Zone_douze_kilos[505]			= 48.11;
$UPS_Zone_douze_kilos_demi[505]		= 49.56;
$UPS_Zone_treize_kilos[505]			= 50.84;
$UPS_Zone_treize_kilos_demi[505]	= 52.22;
$UPS_Zone_quatorze_kilos[505]		= 53.69;
$UPS_Zone_quatorze_kilos_demi[505]	= 56.58;
$UPS_Zone_quinze_kilos[505]			= 58.05;
$UPS_Zone_quinze_kilos_demi[505]	= 59.50;
$UPS_Zone_seize_kilos[505]			= 60.99;
$UPS_Zone_seize_kilos_demi[505]		= 61.99;
$UPS_Zone_dixsept_kilos[505]		= 63.42;
$UPS_Zone_dixsept_kilos_demi[505]	= 64.80;
$UPS_Zone_dixhuit_kilos[505]		= 66.31;
$UPS_Zone_dixhuit_kilos_demi[505]	= 67.69;
$UPS_Zone_dixneuf_kilos[505]		= 69.13;
$UPS_Zone_dixneuf_kilos_demi[505]	= 72.03;
$UPS_Zone_vingt_kilos[505]			= 73.48;

//Zone 506
$UPS_Zone_demi_kilo[506] 			= 14.09;
$UPS_Zone_kilo[506] 				= 15.31;
$UPS_Zone_kilo_demi[506] 			= 16.57;
$UPS_Zone_deux_kilos[506] 			= 17.90;
$UPS_Zone_deux_kilos_demi[506]		= 19.27;
$UPS_Zone_trois_kilos[506]			= 20.74;
$UPS_Zone_trois_kilos_demi[506]		= 22.05;
$UPS_Zone_quatre_kilos[506]			= 23.42;
$UPS_Zone_quatre_kilos_demi[506]	= 26.11;
$UPS_Zone_cinq_kilos[506]			= 27.62;
$UPS_Zone_cinq_kilos_demi[506]		= 28.96;
$UPS_Zone_six_kilos[506]			= 30.33;
$UPS_Zone_six_kilos_demi[506]		= 31.71;
$UPS_Zone_sept_kilos[506]			= 33.04;
$UPS_Zone_sept_kilos_demi[506]		= 34.46;
$UPS_Zone_huit_kilos[506]			= 35.81;
$UPS_Zone_huit_kilos_demi[506]		= 37.14;
$UPS_Zone_neuf_kilos[506]			= 38.54;
$UPS_Zone_neuf_kilos_demi[506]		= 41.27;
$UPS_Zone_dix_kilos[506]			= 42.67;
$UPS_Zone_dix_kilos_demi[506]		= 44.03;
$UPS_Zone_onze_kilos[506]			= 45.47;
$UPS_Zone_onze_kilos_demi[506]		= 46.87;
$UPS_Zone_douze_kilos[506]			= 48.21;
$UPS_Zone_douze_kilos_demi[506]		= 49.63;
$UPS_Zone_treize_kilos[506]			= 50.98;
$UPS_Zone_treize_kilos_demi[506]	= 52.40;
$UPS_Zone_quatorze_kilos[506]		= 53.76;
$UPS_Zone_quatorze_kilos_demi[506]	= 56.65;
$UPS_Zone_quinze_kilos[506]			= 58.14;
$UPS_Zone_quinze_kilos_demi[506]	= 59.62;
$UPS_Zone_seize_kilos[506]			= 61.09;
$UPS_Zone_seize_kilos_demi[506]		= 62.30;
$UPS_Zone_dixsept_kilos[506]		= 63.82;
$UPS_Zone_dixsept_kilos_demi[506]	= 65.35;
$UPS_Zone_dixhuit_kilos[506]		= 66.80;
$UPS_Zone_dixhuit_kilos_demi[506]	= 68.29;
$UPS_Zone_dixneuf_kilos[506]		= 69.67;
$UPS_Zone_dixneuf_kilos_demi[506]	= 72.52;
$UPS_Zone_vingt_kilos[506]			= 74.03;

//Zone 507
$UPS_Zone_demi_kilo[507] 			= 14.56;
$UPS_Zone_kilo[507] 				= 15.80;
$UPS_Zone_kilo_demi[507] 			= 17.10;
$UPS_Zone_deux_kilos[507] 			= 18.52;
$UPS_Zone_deux_kilos_demi[507]		= 19.83;
$UPS_Zone_trois_kilos[507]			= 21.26;
$UPS_Zone_trois_kilos_demi[507]		= 22.47;
$UPS_Zone_quatre_kilos[507]			= 23.68;
$UPS_Zone_quatre_kilos_demi[507]	= 26.15;
$UPS_Zone_cinq_kilos[507]			= 27.70;
$UPS_Zone_cinq_kilos_demi[507]		= 29.07;
$UPS_Zone_six_kilos[507]			= 30.40;
$UPS_Zone_six_kilos_demi[507]		= 31.82;
$UPS_Zone_sept_kilos[507]			= 33.11;
$UPS_Zone_sept_kilos_demi[507]		= 34.53;
$UPS_Zone_huit_kilos[507]			= 35.89;
$UPS_Zone_huit_kilos_demi[507]		= 37.24;
$UPS_Zone_neuf_kilos[507]			= 38.62;
$UPS_Zone_neuf_kilos_demi[507]		= 41.41;
$UPS_Zone_dix_kilos[507]			= 42.82;
$UPS_Zone_dix_kilos_demi[507]		= 44.17;
$UPS_Zone_onze_kilos[507]			= 45.61;
$UPS_Zone_onze_kilos_demi[507]		= 46.99;
$UPS_Zone_douze_kilos[507]			= 48.35;
$UPS_Zone_douze_kilos_demi[507]		= 49.75;
$UPS_Zone_treize_kilos[507]			= 51.12;
$UPS_Zone_treize_kilos_demi[507]	= 52.52;
$UPS_Zone_quatorze_kilos[507]		= 53.90;
$UPS_Zone_quatorze_kilos_demi[507]	= 56.89;
$UPS_Zone_quinze_kilos[507]			= 58.35;
$UPS_Zone_quinze_kilos_demi[507]	= 59.78;
$UPS_Zone_seize_kilos[507]			= 61.27;
$UPS_Zone_seize_kilos_demi[507]		= 62.76;
$UPS_Zone_dixsept_kilos[507]		= 64.23;
$UPS_Zone_dixsept_kilos_demi[507]	= 65.73;
$UPS_Zone_dixhuit_kilos[507]		= 67.15;
$UPS_Zone_dixhuit_kilos_demi[507]	= 68.67;
$UPS_Zone_dixneuf_kilos[507]		= 70.12;
$UPS_Zone_dixneuf_kilos_demi[507]	= 73.08;
$UPS_Zone_vingt_kilos[507]			= 74.59;



//Zone 508 
$UPS_Zone_demi_kilo[508] 			= 15.26;
$UPS_Zone_kilo[508] 				= 16.47;
$UPS_Zone_kilo_demi[508] 			= 18.03;
$UPS_Zone_deux_kilos[508] 			= 19.25;
$UPS_Zone_deux_kilos_demi[508]		= 20.56;
$UPS_Zone_trois_kilos[508]			= 21.72;
$UPS_Zone_trois_kilos_demi[508]		= 23.00;
$UPS_Zone_quatre_kilos[508]			= 24.20;
$UPS_Zone_quatre_kilos_demi[508]	= 26.62;
$UPS_Zone_cinq_kilos[508]			= 28.14;
$UPS_Zone_cinq_kilos_demi[508]		= 29.49;
$UPS_Zone_six_kilos[508]			= 30.80;
$UPS_Zone_six_kilos_demi[508]		= 32.11;
$UPS_Zone_sept_kilos[508]			= 33.43;
$UPS_Zone_sept_kilos_demi[508]		= 34.70;
$UPS_Zone_huit_kilos[508]			= 36.10;
$UPS_Zone_huit_kilos_demi[508]		= 37.42;
$UPS_Zone_neuf_kilos[508]			= 38.80;
$UPS_Zone_neuf_kilos_demi[508]		= 41.58;
$UPS_Zone_dix_kilos[508]			= 42.98;
$UPS_Zone_dix_kilos_demi[508]		= 44.38;
$UPS_Zone_onze_kilos[508]			= 45.80;
$UPS_Zone_onze_kilos_demi[508]		= 47.16;
$UPS_Zone_douze_kilos[508]			= 48.55;
$UPS_Zone_douze_kilos_demi[508]		= 50.02;
$UPS_Zone_treize_kilos[508]			= 51.42;
$UPS_Zone_treize_kilos_demi[508]	= 52.82;
$UPS_Zone_quatorze_kilos[508]		= 54.22;
$UPS_Zone_quatorze_kilos_demi[508]	= 57.10;
$UPS_Zone_quinze_kilos[508]			= 58.66;
$UPS_Zone_quinze_kilos_demi[508]	= 60.11;
$UPS_Zone_seize_kilos[508]			= 61.62;
$UPS_Zone_seize_kilos_demi[508]		= 63.09;
$UPS_Zone_dixsept_kilos[508]		= 64.59;
$UPS_Zone_dixsept_kilos_demi[508]	= 66.03;
$UPS_Zone_dixhuit_kilos[508]		= 67.52;
$UPS_Zone_dixhuit_kilos_demi[508]	= 68.99;
$UPS_Zone_dixneuf_kilos[508]		= 70.77;
$UPS_Zone_dixneuf_kilos_demi[508]	= 73.75;
$UPS_Zone_vingt_kilos[508]			= 75.25;

//Zone 509
$UPS_Zone_demi_kilo[509] 			= 20.13;
$UPS_Zone_kilo[509] 				= 20.41;
$UPS_Zone_kilo_demi[509] 			= 21.11;
$UPS_Zone_deux_kilos[509] 			= 22.37;
$UPS_Zone_deux_kilos_demi[509]		= 23.68;
$UPS_Zone_trois_kilos[509]			= 24.73;
$UPS_Zone_trois_kilos_demi[509]		= 26.08;
$UPS_Zone_quatre_kilos[509]			= 27.39;
$UPS_Zone_quatre_kilos_demi[509]	= 29.63;
$UPS_Zone_cinq_kilos[509]			= 31.92;
$UPS_Zone_cinq_kilos_demi[509]		= 33.37;
$UPS_Zone_six_kilos[509]			= 34.84;
$UPS_Zone_six_kilos_demi[509]		= 36.35;
$UPS_Zone_sept_kilos[509]			= 37.84;
$UPS_Zone_sept_kilos_demi[509]		= 39.31;
$UPS_Zone_huit_kilos[509]			= 40.81;
$UPS_Zone_huit_kilos_demi[509]		= 42.32;
$UPS_Zone_neuf_kilos[509]			= 43.80;
$UPS_Zone_neuf_kilos_demi[509]		= 46.97;
$UPS_Zone_dix_kilos[509]			= 48.48;
$UPS_Zone_dix_kilos_demi[509]		= 50.00;
$UPS_Zone_onze_kilos[509]			= 51.56;
$UPS_Zone_onze_kilos_demi[509]		= 53.06;
$UPS_Zone_douze_kilos[509]			= 54.58;
$UPS_Zone_douze_kilos_demi[509]		= 56.05;
$UPS_Zone_treize_kilos[509]			= 57.63;
$UPS_Zone_treize_kilos_demi[509]	= 59.13;
$UPS_Zone_quatorze_kilos[509]		= 60.71;
$UPS_Zone_quatorze_kilos_demi[509]	= 63.93;
$UPS_Zone_quinze_kilos[509]			= 65.52;
$UPS_Zone_quinze_kilos_demi[509]	= 67.18;
$UPS_Zone_seize_kilos[509]			= 68.83;
$UPS_Zone_seize_kilos_demi[509]		= 70.40;
$UPS_Zone_dixsept_kilos[509]		= 72.05;
$UPS_Zone_dixsept_kilos_demi[509]	= 73.61;
$UPS_Zone_dixhuit_kilos[509]		= 75.23;
$UPS_Zone_dixhuit_kilos_demi[509]	= 76.84;
$UPS_Zone_dixneuf_kilos[509]		= 79.35;
$UPS_Zone_dixneuf_kilos_demi[509]	= 82.71;
$UPS_Zone_vingt_kilos[509]			= 84.42;

//Zone 510
$UPS_Zone_demi_kilo[510] 			= 20.53;
$UPS_Zone_kilo[510] 				= 21.00;
$UPS_Zone_kilo_demi[510] 			= 22.38;
$UPS_Zone_deux_kilos[510] 			= 23.78;
$UPS_Zone_deux_kilos_demi[510]		= 25.11;
$UPS_Zone_trois_kilos[510]			= 25.55;
$UPS_Zone_trois_kilos_demi[510]		= 26.83;
$UPS_Zone_quatre_kilos[510]			= 27.76;
$UPS_Zone_quatre_kilos_demi[510]	= 30.17;
$UPS_Zone_cinq_kilos[510]			= 32.03;
$UPS_Zone_cinq_kilos_demi[510]		= 34.25;
$UPS_Zone_six_kilos[510]			= 35.86;
$UPS_Zone_six_kilos_demi[510]		= 37.43;
$UPS_Zone_sept_kilos[510]			= 39.15;
$UPS_Zone_sept_kilos_demi[510]		= 40.69;
$UPS_Zone_huit_kilos[510]			= 42.33;
$UPS_Zone_huit_kilos_demi[510]		= 43.16;
$UPS_Zone_neuf_kilos[510]			= 45.12;
$UPS_Zone_neuf_kilos_demi[510]		= 48.41;
$UPS_Zone_dix_kilos[510]			= 49.35;
$UPS_Zone_dix_kilos_demi[510]		= 50.98;
$UPS_Zone_onze_kilos[510]			= 52.43;
$UPS_Zone_onze_kilos_demi[510]		= 54.01;
$UPS_Zone_douze_kilos[510]			= 55.58;
$UPS_Zone_douze_kilos_demi[510]		= 57.00;
$UPS_Zone_treize_kilos[510]			= 58.52;
$UPS_Zone_treize_kilos_demi[510]	= 60.13;
$UPS_Zone_quatorze_kilos[510]		= 61.76;
$UPS_Zone_quatorze_kilos_demi[510]	= 64.91;
$UPS_Zone_quinze_kilos[510]			= 66.47;
$UPS_Zone_quinze_kilos_demi[510]	= 68.11;
$UPS_Zone_seize_kilos[510]			= 69.72;
$UPS_Zone_seize_kilos_demi[510]		= 71.31;
$UPS_Zone_dixsept_kilos[510]		= 72.96;
$UPS_Zone_dixsept_kilos_demi[510]	= 74.46;
$UPS_Zone_dixhuit_kilos[510]		= 76.18;
$UPS_Zone_dixhuit_kilos_demi[510]	= 77.68;
$UPS_Zone_dixneuf_kilos[510]		= 79.40;
$UPS_Zone_dixneuf_kilos_demi[510]	= 82.76;
$UPS_Zone_vingt_kilos[510]			= 84.44;


if ($_POST['NouvelleDemande']=='NouvelleDemande')
{
	//Une nouvelle demande a été soumise. 
	//Nous devons valider les données fournies.
	//echo '<br><b>Validation en cours..</b><br>';
		
	$lieu_depart 				= 	$_POST[lieu_depart];	
	$lieu_destination 			= 	$_POST[lieu_destination];	
	$contenu_paquet 			= 	$_POST[contenu_paquet];	
	$contenu_paquet_detail 		= 	$_POST[contenu_paquet_detail];	
	$nombre_item_paquet 		= 	$_POST[nombre_item_paquet];	
	$type_de_boite				=	$_POST[type_de_boite];
	$auteur						=	$_POST[auteur];
	$poids_paquet				=	$_POST[poids_paquet];
	$poids_reel_ou_estimation 	= 	$_POST[poids_reel_ou_estimation];
	$longueur_paquet			= 	$_POST[longueur_paquet];
	$largeur_paquet				= 	$_POST[largeur_paquet];
	$hauteur_paquet				= 	$_POST[hauteur_paquet];
	$bon_de_retour				= 	$_POST[bon_de_retour];
	//echo'<br>Compagnie:<b>'. 	'</b><br>Départ:<b>'	. $lieu_depart .	'</b><br>Destination:<b>'	. $lieu_destination.	'</b><br>Contenu:<b>'	. $contenu_paquet.
	//'</b><br>Detail contenu (si autre):<b>'	. $contenu_paquet_detail. ' </b><br>Nombre item dans le paquet:<b>'	. $nombre_item_paquet.'</b><br><br><br><hr>';

	$DetailErreur='';//Initialisation

	//Faire le lien entre le lieu de départ, la destination et les coûts proposés par nos transporteurs
	switch($lieu_depart){
		case 'Siege Social TR':
		$Zone_ICS= $ZoneICS[SiegeSocial][$lieu_destination];
		//echo '<br>Zone ICS:'. $ZoneICS[SiegeSocial][$lieu_destination];
		$Zone_UPS= $ZoneUPS[SiegeSocial][$lieu_destination];
		//echo '<br>Zone UPS:'. $ZoneUPS[SiegeSocial][$lieu_destination];
		break;	
			
		case 'Laboratoire STC':
		$Zone_ICS= $ZoneICS[LaboratoireSTC][$lieu_destination];
		//echo '<br>Zone ICS:'. $ZoneICS[LaboratoireSTC][$lieu_destination];
		$Zone_UPS= $ZoneUPS[LaboratoireSTC][$lieu_destination];
		//echo '<br>Zone UPS:'. $ZoneUPS[LaboratoireSTC][$lieu_destination];
		break;	
		
		//CAS PAR DÉFAULT	
		/*default: if ($lieu_destination=='Laboratoire STC'){
			
			//$ICS_Zone_demi_kilo[2000]
			
		}//END IF
		*/
			
	}//End Switch
	
//Aller chercher les prix des zones ICS et UPS identifiées

	
//Trouver le nom des prix selon la zone
//PARTIE ICS
$ICS_demi_kilo 			= $ICS_Zone_demi_kilo[$Zone_ICS];
$ICS_demi_kilo			= number_format($ICS_demi_kilo,2,'.',' ');	
$ICS_kilo 				= $ICS_Zone_kilo[$Zone_ICS];
$ICS_kilo				= number_format($ICS_kilo,2,'.',' ');
$ICS_kilo_demi  		= $ICS_Zone_kilo_demi[$Zone_ICS];
$ICS_kilo_demi			= number_format($ICS_kilo_demi,2,'.',' ');
$ICS_deux_kilos 		= $ICS_Zone_deux_kilos[$Zone_ICS];
$ICS_deux_kilos			= number_format($ICS_deux_kilos,2,'.',' ');
$ICS_deux_kilos_demi  	= $ICS_Zone_deux_kilos_demi[$Zone_ICS];	
$ICS_deux_kilos_demi	= number_format($ICS_deux_kilos_demi,2,'.',' ');
$ICS_trois_kilos 		= $ICS_Zone_trois_kilos[$Zone_ICS];	
$ICS_trois_kilos		= number_format($ICS_trois_kilos,2,'.',' ');
$ICS_trois_kilos_demi  	= $ICS_Zone_trois_kilos_demi[$Zone_ICS];	
$ICS_trois_kilos_demi	= number_format($ICS_trois_kilos_demi,2,'.',' ');
$ICS_quatre_kilos 		= $ICS_Zone_quatre_kilos[$Zone_ICS];
$ICS_quatre_kilos		= number_format($ICS_quatre_kilos,2,'.',' ');	
$ICS_quatre_kilos_demi 	= $ICS_Zone_quatre_kilos_demi[$Zone_ICS];
$ICS_quatre_kilos_demi	= number_format($ICS_quatre_kilos_demi,2,'.',' ');	
$ICS_cinq_kilos 		= $ICS_Zone_cinq_kilos[$Zone_ICS];
$ICS_cinq_kilos			= number_format($ICS_cinq_kilos,2,'.',' ');		
$ICS_cinq_kilos_demi 	= $ICS_Zone_cinq_kilos_demi[$Zone_ICS];
$ICS_cinq_kilos_demi	= number_format($ICS_cinq_kilos_demi,2,'.',' ');	
$ICS_six_kilos 			= $ICS_Zone_six_kilos[$Zone_ICS];
$ICS_six_kilos			= number_format($ICS_six_kilos,2,'.',' ');		
$ICS_six_kilos_demi 	= $ICS_Zone_six_kilos_demi[$Zone_ICS];
$ICS_six_kilos_demi		= number_format($ICS_six_kilos_demi,2,'.',' ');			
$ICS_sept_kilos 		= $ICS_Zone_sept_kilos[$Zone_ICS];
$ICS_sept_kilos			= number_format($ICS_sept_kilos,2,'.',' ');		
$ICS_sept_kilos_demi 	= $ICS_Zone_sept_kilos_demi[$Zone_ICS];
$ICS_sept_kilos_demi	= number_format($ICS_sept_kilos_demi,2,'.',' ');		
$ICS_huit_kilos 		= $ICS_Zone_huit_kilos[$Zone_ICS];
$ICS_huit_kilos			= number_format($ICS_huit_kilos,2,'.',' ');		
$ICS_huit_kilos_demi 	= $ICS_Zone_huit_kilos_demi[$Zone_ICS];
$ICS_huit_kilos_demi	= number_format($ICS_huit_kilos_demi,2,'.',' ');	
$ICS_neuf_kilos 		= $ICS_Zone_neuf_kilos[$Zone_ICS];
$ICS_neuf_kilos			= number_format($ICS_neuf_kilos,2,'.',' ');	
$ICS_neuf_kilos_demi 	= $ICS_Zone_neuf_kilos_demi[$Zone_ICS];
$ICS_neuf_kilos_demi	= number_format($ICS_neuf_kilos_demi,2,'.',' ');			
$ICS_dix_kilos 			= $ICS_Zone_dix_kilos[$Zone_ICS];
$ICS_dix_kilos			= number_format($ICS_dix_kilos,2,'.',' ');	
$ICS_dix_kilos_demi 	= $ICS_Zone_dix_kilos_demi[$Zone_ICS];
$ICS_dix_kilos_demi		= number_format($ICS_dix_kilos_demi,2,'.',' ');	
$ICS_onze_kilos	 		= $ICS_Zone_onze_kilos[$Zone_ICS];
$ICS_onze_kilos			= number_format($ICS_onze_kilos,2,'.',' ');	
$ICS_onze_kilos_demi	= $ICS_Zone_onze_kilos_demi[$Zone_ICS];
$ICS_onze_kilos_demi	= number_format($ICS_onze_kilos_demi,2,'.',' ');	
$ICS_douze_kilos	 	= $ICS_Zone_douze_kilos[$Zone_ICS];
$ICS_douze_kilos		= number_format($ICS_douze_kilos,2,'.',' ');	
$ICS_douze_kilos_demi	= $ICS_Zone_douze_kilos_demi[$Zone_ICS];
$ICS_douze_kilos_demi	= number_format($ICS_douze_kilos_demi,2,'.',' ');	
$ICS_treize_kilos	 	= $ICS_Zone_treize_kilos[$Zone_ICS];
$ICS_treize_kilos		= number_format($ICS_treize_kilos,2,'.',' ');	
$ICS_treize_kilos_demi	= $ICS_Zone_treize_kilos_demi[$Zone_ICS];
$ICS_treize_kilos_demi	= number_format($ICS_treize_kilos_demi,2,'.',' ');	
$ICS_quatorze_kilos	 	= $ICS_Zone_quatorze_kilos[$Zone_ICS];
$ICS_quatorze_kilos		= number_format($ICS_quatorze_kilos,2,'.',' ');		
$ICS_quatorze_kilos_demi= $ICS_Zone_quatorze_kilos_demi[$Zone_ICS];
$ICS_quatorze_kilos_demi= number_format($ICS_quatorze_kilos_demi,2,'.',' ');		
$ICS_quinze_kilos		= $ICS_Zone_quinze_kilos[$Zone_ICS];		
$ICS_quinze_kilos		= number_format($ICS_quinze_kilos,2,'.',' ');	
$ICS_quinze_kilos_demi	= $ICS_Zone_quinze_kilos_demi[$Zone_ICS];
$ICS_quinze_kilos_demi	= number_format($ICS_quinze_kilos_demi,2,'.',' ');	
$ICS_seize_kilos		= $ICS_Zone_seize_kilos[$Zone_ICS];		
$ICS_seize_kilos		= number_format($ICS_seize_kilos,2,'.',' ');	
$ICS_seize_kilos_demi	= $ICS_Zone_seize_kilos_demi[$Zone_ICS];
$ICS_seize_kilos_demi	= number_format($ICS_seize_kilos_demi,2,'.',' ');	
$ICS_dixsept_kilos		= $ICS_Zone_dixsept_kilos[$Zone_ICS];		
$ICS_dixsept_kilos		= number_format($ICS_dixsept_kilos,2,'.',' ');		
$ICS_dixsept_kilos_demi	= $ICS_Zone_dixsept_kilos_demi[$Zone_ICS];	
$ICS_dixsept_kilos_demi	= number_format($ICS_dixsept_kilos_demi,2,'.',' ');	
$ICS_dixhuit_kilos		= $ICS_Zone_dixhuit_kilos[$Zone_ICS];	
$ICS_dixhuit_kilos		= number_format($ICS_dixhuit_kilos,2,'.',' ');	
$ICS_dixhuit_kilos_demi	= $ICS_Zone_dixhuit_kilos_demi[$Zone_ICS];	
$ICS_dixhuit_kilos_demi	= number_format($ICS_dixhuit_kilos_demi,2,'.',' ');		
$ICS_dixneuf_kilos		= $ICS_Zone_dixneuf_kilos[$Zone_ICS];		
$ICS_dixneuf_kilos		= number_format($ICS_dixneuf_kilos,2,'.',' ');	
$ICS_dixneuf_kilos_demi	= $ICS_Zone_dixneuf_kilos_demi[$Zone_ICS];
$ICS_dixneuf_kilos_demi	= number_format($ICS_dixneuf_kilos_demi,2,'.',' ');	
$ICS_vingt_kilos		= $ICS_Zone_vingt_kilos[$Zone_ICS];													
$ICS_vingt_kilos		= number_format($ICS_vingt_kilos,2,'.',' ');	
//PARTIE UPS
$UPS_demi_kilo 			= $UPS_Zone_demi_kilo[$Zone_UPS];
$UPS_demi_kilo			= number_format($UPS_demi_kilo,2,'.',' ');	
$UPS_kilo 				= $UPS_Zone_kilo[$Zone_UPS];
$UPS_kilo				= number_format($UPS_kilo,2,'.',' ');	
$UPS_kilo_demi  		= $UPS_Zone_kilo_demi[$Zone_UPS];	
$UPS_kilo_demi			= number_format($UPS_kilo_demi,2,'.',' ');	
$UPS_deux_kilos 		= $UPS_Zone_deux_kilos[$Zone_UPS];
$UPS_deux_kilos			= number_format($UPS_deux_kilos,2,'.',' ');		
$UPS_deux_kilos_demi  	= $UPS_Zone_deux_kilos_demi[$Zone_UPS];	
$UPS_deux_kilos_demi	= number_format($UPS_deux_kilos_demi,2,'.',' ');	
$UPS_trois_kilos 		= $UPS_Zone_trois_kilos[$Zone_UPS];	
$UPS_trois_kilos		= number_format($UPS_trois_kilos,2,'.',' ');		
$UPS_trois_kilos_demi  	= $UPS_Zone_trois_kilos_demi[$Zone_UPS];
$UPS_trois_kilos_demi	= number_format($UPS_trois_kilos_demi,2,'.',' ');
$UPS_quatre_kilos 		= $UPS_Zone_quatre_kilos[$Zone_UPS];
$UPS_quatre_kilos		= number_format($UPS_quatre_kilos,2,'.',' ');
$UPS_quatre_kilos_demi 	= $UPS_Zone_quatre_kilos_demi[$Zone_UPS];
$UPS_quatre_kilos_demi	= number_format($UPS_quatre_kilos_demi,2,'.',' ');		
$UPS_cinq_kilos 		= $UPS_Zone_cinq_kilos[$Zone_UPS];
$UPS_cinq_kilos			= number_format($UPS_cinq_kilos,2,'.',' ');	
$UPS_cinq_kilos_demi 	= $UPS_Zone_cinq_kilos_demi[$Zone_UPS];
$UPS_cinq_kilos_demi	= number_format($UPS_cinq_kilos_demi,2,'.',' ');
$UPS_six_kilos 			= $UPS_Zone_six_kilos[$Zone_UPS];
$UPS_six_kilos			= number_format($UPS_six_kilos,2,'.',' ');	
$UPS_six_kilos_demi 	= $UPS_Zone_six_kilos_demi[$Zone_UPS];
$UPS_six_kilos_demi		= number_format($UPS_six_kilos_demi,2,'.',' ');	
$UPS_sept_kilos 		= $UPS_Zone_sept_kilos[$Zone_UPS];
$UPS_sept_kilos			= number_format($UPS_sept_kilos,2,'.',' ');	
$UPS_sept_kilos_demi 	= $UPS_Zone_sept_kilos_demi[$Zone_UPS];
$UPS_sept_kilos_demi	= number_format($UPS_sept_kilos_demi,2,'.',' ');	
$UPS_huit_kilos 		= $UPS_Zone_huit_kilos[$Zone_UPS];	
$UPS_huit_kilos			= number_format($UPS_huit_kilos,2,'.',' ');	
$UPS_huit_kilos_demi 	= $UPS_Zone_huit_kilos_demi[$Zone_UPS];
$UPS_huit_kilos_demi	= number_format($UPS_huit_kilos_demi,2,'.',' ');		
$UPS_neuf_kilos 		= $UPS_Zone_neuf_kilos[$Zone_UPS];
$UPS_neuf_kilos			= number_format($UPS_neuf_kilos,2,'.',' ');	
$UPS_neuf_kilos_demi 	= $UPS_Zone_neuf_kilos_demi[$Zone_UPS];
$UPS_neuf_kilos_demi	= number_format($UPS_neuf_kilos_demi,2,'.',' ');	
$UPS_dix_kilos 			= $UPS_Zone_dix_kilos[$Zone_UPS];
$UPS_dix_kilos			= number_format($UPS_dix_kilos,2,'.',' ');	
$UPS_dix_kilos_demi 	= $UPS_Zone_dix_kilos_demi[$Zone_UPS];
$UPS_dix_kilos_demi		= number_format($UPS_dix_kilos_demi,2,'.',' ');		
$UPS_onze_kilos	 		= $UPS_Zone_onze_kilos[$Zone_UPS];
$UPS_onze_kilos			= number_format($UPS_onze_kilos,2,'.',' ');	
$UPS_onze_kilos_demi	= $UPS_Zone_onze_kilos_demi[$Zone_UPS];
$UPS_onze_kilos_demi	= number_format($UPS_onze_kilos_demi,2,'.',' ');		
$UPS_douze_kilos	 	= $UPS_Zone_douze_kilos[$Zone_UPS];
$UPS_douze_kilos		= number_format($UPS_douze_kilos,2,'.',' ');	
$UPS_douze_kilos_demi	= $UPS_Zone_douze_kilos_demi[$Zone_UPS];
$UPS_douze_kilos_demi	= number_format($UPS_douze_kilos_demi,2,'.',' ');		
$UPS_treize_kilos	 	= $UPS_Zone_treize_kilos[$Zone_UPS];
$UPS_treize_kilos		= number_format($UPS_treize_kilos,2,'.',' ');		
$UPS_treize_kilos_demi	= $UPS_Zone_treize_kilos_demi[$Zone_UPS];
$UPS_treize_kilos_demi	= number_format($UPS_treize_kilos_demi,2,'.',' ');		
$UPS_quatorze_kilos	 	= $UPS_Zone_quatorze_kilos[$Zone_UPS];
$UPS_quatorze_kilos		= number_format($UPS_quatorze_kilos,2,'.',' ');		
$UPS_quatorze_kilos_demi= $UPS_Zone_quatorze_kilos_demi[$Zone_UPS];
$UPS_quatorze_kilos_demi= number_format($UPS_quatorze_kilos_demi,2,'.',' ');		
$UPS_quinze_kilos		= $UPS_Zone_quinze_kilos[$Zone_UPS];	
$UPS_quinze_kilos		= number_format($UPS_quinze_kilos,2,'.',' ');		
$UPS_quinze_kilos_demi	= $UPS_Zone_quinze_kilos_demi[$Zone_UPS];	
$UPS_quinze_kilos_demi	= number_format($UPS_quinze_kilos_demi,2,'.',' ');		
$UPS_seize_kilos		= $UPS_Zone_seize_kilos[$Zone_UPS];		
$UPS_seize_kilos		= number_format($UPS_seize_kilos,2,'.',' ');		
$UPS_seize_kilos_demi	= $UPS_Zone_seize_kilos_demi[$Zone_UPS];
$UPS_seize_kilos_demi	= number_format($UPS_seize_kilos_demi,2,'.',' ');		
$UPS_dixsept_kilos		= $UPS_Zone_dixsept_kilos[$Zone_UPS];
$UPS_dixsept_kilos		= number_format($UPS_dixsept_kilos,2,'.',' ');	
$UPS_dixsept_kilos_demi	= $UPS_Zone_dixsept_kilos_demi[$Zone_UPS];	
$UPS_dixsept_kilos_demi	= number_format($UPS_dixsept_kilos_demi,2,'.',' ');		
$UPS_dixhuit_kilos		= $UPS_Zone_dixhuit_kilos[$Zone_UPS];		
$UPS_dixhuit_kilos		= number_format($UPS_dixhuit_kilos,2,'.',' ');			
$UPS_dixhuit_kilos_demi	= $UPS_Zone_dixhuit_kilos_demi[$Zone_UPS];	
$UPS_dixhuit_kilos_demi	= number_format($UPS_dixhuit_kilos_demi,2,'.',' ');	
$UPS_dixneuf_kilos		= $UPS_Zone_dixneuf_kilos[$Zone_UPS];			
$UPS_dixneuf_kilos		= number_format($UPS_dixneuf_kilos,2,'.',' ');			
$UPS_dixneuf_kilos_demi	= $UPS_Zone_dixneuf_kilos_demi[$Zone_UPS];	
$UPS_dixneuf_kilos_demi	= number_format($UPS_dixneuf_kilos_demi,2,'.',' ');		
$UPS_vingt_kilos		= $UPS_Zone_vingt_kilos[$Zone_UPS];													
$UPS_vingt_kilos		= number_format($UPS_vingt_kilos,2,'.',' ');		

	//VALIDATIONS
	
	//VALIDER QUE LES CHAMPS SONT RENSEIGNÉS
	
	//Sélection du lieu de départ du paquet
	if ($lieu_depart==''){
		$ErreurFrancais.="<br><b>Vous devez choisir le lieu de départ de votre paquet.</b>";
		$ErreurAnglais.="<br><b>You need to select the where the package will be leaving from.</b>";
	}//END IF
	
	//Sélection de la destination du paquet
	if ($lieu_destination==''){
		$ErreurFrancais.="<br><b>Vous devez choisir le lieu de destination de votre paquet.</b>";
		$ErreurAnglais.="<br><b>You need to select the destination of your package.</b>";
	}//END IF
	
	//Vérifier si l'info du bon de retour doit être répondue
	if (($lieu_depart<>'Laboratoire STC')&&($lieu_depart<>'Siege Social TR')&&($bon_de_retour=='')){
		//La question 2b  est maintenant obligatoire
		$ErreurFrancais.="<br><b>Vous devez répondre à la question 2B.</b>";
		$ErreurAnglais.="<br><b>The question 2B is mandatory.</b>";
	}//END IF
	
	//Vérifier si le client a un bon pré-imprimé de retour, qui est obligatoire à ce moment-ci. 
	if (($lieu_depart<>'Laboratoire STC')&&($lieu_depart<>'Siege Social TR')&&($bon_de_retour=='non')){
		//La question 2b  est maintenant obligatoire
		$ErreurFrancais.="<br><b>Le bon de retour pré-imprimé est obligatoire pour envoyer un paquet vers le laboratoire.</b>";
		$ErreurAnglais.="<br><b>The pre-printed slip is mandatory to send a package to the lab.</b>";
	}//END IF
	
	//Sélection de ce que contient le paquet
	if ($contenu_paquet==''){
		$ErreurFrancais.="<br><b>Vous devez choisir le contenu de votre paquet.</b>";
		$ErreurAnglais.="<br><b>You need to select the content of your package.</b>";
	}//END IF
	
	//Si  le contenu du paquet est 'AUTRE', Valider que le détail a été fournis.
	if ($contenu_paquet=="autre"){
		if ($contenu_paquet_detail==''){
		$ErreurFrancais.="<br><b>Puisque vous avez choisir 'Autre' dans la question #3, Vous devez spécifier le contenu de votre paquet en 3B.</b>";
		$ErreurAnglais.="<br><b>Since you selected 'Other' on question #3, You need to specify the content of your package in 3B.</b>";	
		}//END IF
	}//END IF
	
	
	//Sélection de ce que contient le paquet
	if ($nombre_item_paquet==''){
		$ErreurFrancais.="<br><b>Vous devez choisir le nombre d'items dans votre paquet.</b>";
		$ErreurAnglais.="<br><b>You need to select the number of items in your package.</b>";
	}//END IF
	
	
	//Sélection le type de boîte
	if ($type_de_boite==''){
		$ErreurFrancais.="<br><b>Vous devez choisir le type de boîte.</b>";
		$ErreurAnglais.="<br><b>You need to select the type of box.</b>";
	}//END IF
	
	
	//Poids du paquet
	if ($poids_paquet==''){
		$ErreurFrancais.="<br><b>Vous devez enter le poids du paquet, si vous ne pouvez pas le pesez, faites une estimation.</b>";
		$ErreurAnglais.="<br><b>You need to type the weight of the package, If you can't weigh it, type an estimate.</b>";
	}//END IF
	
	//Initiales de l'auteur
	if ($auteur==''){
		$ErreurFrancais.="<br><b>Vous devez enter vos initiales.</b>";
		$ErreurAnglais.="<br><b>You need to type your initials.</b>";
	}//END IF
	
	
	
	//VALIDER QUE LA LOGIQUE SE TIENT
	
	//Validation que le départ et la destination ne sont pas le même endroit
	if ($lieu_depart == $lieu_destination){
		$ErreurFrancais.="<br><b>Le lieu de départ et de destination ne peuvent pas être les même.</b>";
		$ErreurAnglais.="<br><b>The place where the package is leaving from and its destination can't be the same.</b>";
	}//END IF
	
	//Validation: que le champ détail autre est vide si le 'champ de la question 4 n'est pas à autre
	if (($contenu_paquet<>'autre')&&($contenu_paquet_detail<>'')){
		$ErreurFrancais.="<br><b>Le champ 3B doit être vide sauf si la réponse de la question 4 est 'autre'</b>";
		$ErreurAnglais.="<br><b>The fiels 3B should be empty unless the answer to question 4 is 'other'</b>";	
	}//END IF
	
	//Validation: que le champ 'avez vous pesez le paquet' est répondu
	if ($poids_reel_ou_estimation==''){
		$ErreurFrancais.="<br><b>Le champ 6B est obligatoire</b>";
		$ErreurAnglais.="<br><b>The fiels 6B  is mandatory</b>";	
	}//END IF
	
	//Validation:Empêcher les envoies d'une succursale vers un autre succursale
	if (($lieu_depart<>'Laboratoire STC')&&($lieu_depart<>'Siege Social TR')&&($lieu_destination<>'Laboratoire STC')&&($lieu_destination<>'Siege Social TR')){
		$ErreurFrancais.="<br><b>Les envois entre deux magasins sont interdis</b>";
		$ErreurAnglais.="<br><b>Shipments between 2 stores are prohibited</b>";
	} //END IF    
	
	
	//Bloquer les commandes de montures qui contiennent moins de X montures
	
	
	//Afficher les erreurs, s'il y en a 
	if ($ErreurFrancais<>''){
		echo  '<p  style="color:crimson";><b>ERREUR:</b>'. $ErreurFrancais.'<br><br></p>';	
		echo  '<p  style="color:crimson";><b>ERROR:</b>'. $ErreurAnglais.'</p>';	
	}else{
	//echo 'Passe la validation';	
		
	//GÉNÉRER LE TOKEN
	$ArraydeAaF=['A','B','C','D','E','F'];
	$ArrayPosition1a6=[0,1,2,3,4,5];
	$ArrayPosition1a9=[0,1,2,3,4,5,6,7,8];
	shuffle($ArraydeAaF);
	shuffle($ArrayPosition1a6);
	$TokenUnique=$ArraydeAaF[$ArrayPosition1a6[0]];	
	shuffle($ArraydeAaF);
	shuffle($ArrayPosition1a6);	
	$TokenUnique.=$ArraydeAaF[$ArrayPosition1a6[0]];	
	shuffle($ArraydeAaF);
	shuffle($ArrayPosition1a6);	
	$TokenUnique.=$ArraydeAaF[$ArrayPosition1a6[0]];	
	shuffle($ArrayPosition1a9);	
	$TokenUnique.=$ArrayPosition1a9[0];	
	shuffle($ArrayPosition1a9);	
	$TokenUnique.=$ArrayPosition1a9[0];	
	shuffle($ArrayPosition1a9);	
	$TokenUnique.=$ArrayPosition1a9[0];	
	//echo 	'<br>TokenUnique:<b>'. $TokenUnique.'</b><br>';	
	//Vérifier que le token n'existe pas dans la DB.
	$queryVerifierToken="SELECT * FROM shipping WHERE token='$TokenUnique'";
	//echo '<br>'. $queryVerifierToken;
	$resultVerifierToken= mysqli_query($con,$queryVerifierToken);
	$NombredeTokenMatch=mysqli_num_rows($resultVerifierToken);	
	if ($NombredeTokenMatch>0){
		//Ce token est déja utilisé, on doit en générer un différent
		shuffle($ArraydeAaF);
		shuffle($ArrayPosition1a6);
		$TokenUnique=$ArraydeAaF[$ArrayPosition1a6[1]];	
		shuffle($ArraydeAaF);
		shuffle($ArrayPosition1a6);	
		$TokenUnique.=$ArraydeAaF[$ArrayPosition1a6[2]];	
		shuffle($ArraydeAaF);
		shuffle($ArrayPosition1a6);	
		$TokenUnique.=$ArraydeAaF[$ArrayPosition1a6[3]];	
		shuffle($ArrayPosition1a9);	
		$TokenUnique.=$ArrayPosition1a9[1];	
		shuffle($ArrayPosition1a9);	
		$TokenUnique.=$ArrayPosition1a9[2];	
		shuffle($ArrayPosition1a9);	
		$TokenUnique.=$ArrayPosition1a9[3];	
	}//END IF
		
	//Analyse de la compagnie de transport qui sera suggéré	
	$Compagnie_a_utiliser="";

		
	//Calcul du poids volumétrique
	switch($type_de_boite){
		case "UPS(9x4x4)": 
		$PoidsVolumetrique = (9*8*4)/139;		
		break; 		//UPS (9"x4"x4")</option>

		case "UPS(9x8x4)": 		
		$PoidsVolumetrique = (9*8*4)/139;		
		break;

		case "Autre":
		//Utiliser les variables hauteur, largeur et longueur
		$PoidsVolumetrique = ($longueur_paquet * $largeur_paquet * $hauteur_paquet)/139;
		break;
	}//END SWITCH
		
	$PoidsVolumetrique = number_format($PoidsVolumetrique,2,'.',' ');		
	//echo '<br>Poids Volumétrique:' . $PoidsVolumetrique;
		
	$Poids_A_Utiliser="Poids Paquet";
	if ($PoidsVolumetrique>$poids_paquet){
		$Poids_A_Utiliser="Poids Volumetrique";		
	}	
	
		
	if ($Poids_A_Utiliser=="Poids Volumetrique")
		$poids_en_kg = $PoidsVolumetrique/2.2;	
	elseif ($Poids_A_Utiliser=="Poids Paquet")
		$poids_en_kg = $poids_paquet/2.2;	
		$poids_en_kg= number_format($poids_en_kg,2,'.',' ');
	
	//Calcul de la différence entre le poids Volumétrique et le poids Réel
	$Difference_Volumetrique_Reel = $PoidsVolumetrique -$poids_paquet;
	//echo '<br>Différence Entre Volumetrique  et  reel:'. 	$Difference_Volumetrique_Reel . ' lbs';
	$Pourcentage_Difference =($Difference_Volumetrique_Reel/$PoidsVolumetrique) * 100;
	$Pourcentage_Difference = number_format($Pourcentage_Difference,2,'.',' ');	
	//echo '<br>Pourcentage de différence: ' .	$Pourcentage_Difference .'%';
	
	//Établir le pourcentage au dela duquel on recommandera de prendre une boite plus petite, qui sera plus adaptée.
	$Pourcentage_Difference_Limite =35;//20% pour mes tests, donc si la différence est de plus de 20%, on avise l'utilisateur. //modifié a 30% le 7 Juillet 2020 // modifié a 35% le 9 Juillet par Émilie
	
	if ($Pourcentage_Difference>$Pourcentage_Difference_Limite){
	//Le poids volumétrique dépasse le poids réel du paquet au dela de la limite tolérée (20%)
	//On doit donc aviser l'utilisateur, afin qu'il puisse changer de boîte pour une plus petite, qui sera plus appropriée (SI disponible)
	$ErreurFrancais.=" Le poids volumétrique de votre paquet <b>($PoidsVolumetrique lbs)</b> excède le poids réel <b>($poids_paquet lbs)</b> de <b>$Pourcentage_Difference%</b>. <br>Cela signifie que la boite que vous avez choisis est trop grosse pour ce quelle contient. <u>Vous devez utiliser une boite plus petite, et re-générer ce formulaire</u><br>";
	$ErreurAnglais.=" The dimensional weight of your package <b>($PoidsVolumetrique lbs)</b> exceeds the real  weight <b>($poids_paquet lbs)</b> of <b>$Pourcentage_Difference%</b> . <br> This means that the box you selected is too big for its content. <u>You must use a smaller box and re-generate this form.</u> ";
		//$Message_Utilisateur_VolumetriqueEN = "The dimensional weight of your package <b>($PoidsVolumetrique lbs)</b> exceeds the real  weight <b>($poids_paquet lbs)</b> of <b>$Pourcentage_Difference%</b> . <br> This means that the box you selected is too big for its content. <u>If you can, please use a smaller box and re-generate a token.</u> ";
	//$Message_Utilisateur_VolumetriqueFR = "Le poids volumétrique de votre paquet <b>($PoidsVolumetrique lbs)</b> excède le poids réel <b>($poids_paquet lbs)</b> de <b>$Pourcentage_Difference%</b>. <br>Cela signifie que la boite que vous avez choisis est trop grosse pour ce quelle contient. <u>Si vous pouvez utiliser une boite plus petite, svp faites-le et re-générer un token</u><br>";
	}
		
	//RÉ-Afficher les erreurs, s'il y en a,  [car celle sur le poids volumétrique aurait pu s'ajouter] 
	if ($ErreurFrancais<>''){
		echo  '<p  style="color:crimson";><b>ERREUR:</b>'. $ErreurFrancais.'<br><br></p>';	
		echo  '<p  style="color:crimson";><b>ERROR:</b>'. $ErreurAnglais.'</p>';	
	}
		
		
		
	//Identifier la zone qui correspond au prix offert par chaque transporteur
	if(($poids_en_kg>0)&& ($poids_en_kg<0.51)){		
		$ZonedePoidsaUtiliser="demi kilo";
	}
		
	if(($poids_en_kg>0.50)&& ($poids_en_kg<1.01)){		
		$ZonedePoidsaUtiliser="kilo";
	}
		
	if(($poids_en_kg>1.00)&& ($poids_en_kg<1.51)){		
		$ZonedePoidsaUtiliser="kilo demi";
	}
		
	if(($poids_en_kg>1.50)&& ($poids_en_kg<2.01)){		
		$ZonedePoidsaUtiliser="deux kilos";
	}

	if(($poids_en_kg>2.00)&& ($poids_en_kg<2.51)){		
		$ZonedePoidsaUtiliser="deux kilos demi";
	}
			
	if(($poids_en_kg>2.50)&& ($poids_en_kg<3.01)){		
		$ZonedePoidsaUtiliser="trois kilos";
	}

	if(($poids_en_kg>3.00)&& ($poids_en_kg<3.51)){		
		$ZonedePoidsaUtiliser="trois kilos demi";
	}

	if(($poids_en_kg>3.50)&& ($poids_en_kg<4.01)){		
		$ZonedePoidsaUtiliser="quatre kilos";
	}
	
	if(($poids_en_kg>4)&& ($poids_en_kg<4.51)){		
		$ZonedePoidsaUtiliser="quatre kilos demi";
	}
		
	if(($poids_en_kg>4.50)&& ($poids_en_kg<5.01)){		
		$ZonedePoidsaUtiliser="cinq kilos";
	}
		
	if(($poids_en_kg>5.00)&& ($poids_en_kg<5.51)){		
		$ZonedePoidsaUtiliser="cinq kilos demi";
	}

	if(($poids_en_kg>5.50)&& ($poids_en_kg<6.01)){		
		$ZonedePoidsaUtiliser="six kilos";
	}
	
	if(($poids_en_kg>6)&& ($poids_en_kg<6.51)){		
		$ZonedePoidsaUtiliser="six kilos demi";
	}
				
	if(($poids_en_kg>6.50)&& ($poids_en_kg<7.01)){		
		$ZonedePoidsaUtiliser="sept kilos";
	}
		
	if(($poids_en_kg>7)&& ($poids_en_kg<7.51)){		
		$ZonedePoidsaUtiliser="sept kilos demi";
	}
		
	if(($poids_en_kg>7.50)&& ($poids_en_kg<8.01)){		
		$ZonedePoidsaUtiliser="huit kilos";
	}
		
	if(($poids_en_kg>8)&& ($poids_en_kg<8.51)){		
		$ZonedePoidsaUtiliser="huit kilos demi";
	}
		
	if(($poids_en_kg>8.50)&& ($poids_en_kg<9.01)){		
		$ZonedePoidsaUtiliser="neuf kilos";	
	}
		
	if(($poids_en_kg>9)&& ($poids_en_kg<9.51)){		
		$ZonedePoidsaUtiliser="neuf kilos demi";
	}
		
	if(($poids_en_kg>9.5)&& ($poids_en_kg<10.01)){		
		$ZonedePoidsaUtiliser="dix kilos";
	}
		
	if(($poids_en_kg>10)&& ($poids_en_kg<10.51)){		
		$ZonedePoidsaUtiliser="dix kilos demi";
	}
		
	if(($poids_en_kg>10.5)&& ($poids_en_kg<11.01)){		
		$ZonedePoidsaUtiliser="onze kilos";
	}	
		
	if(($poids_en_kg>11)&& ($poids_en_kg<11.51)){		
		$ZonedePoidsaUtiliser="onze kilos demi";
	}	
		
	if(($poids_en_kg>11.5)&& ($poids_en_kg<12.01)){		
		$ZonedePoidsaUtiliser="douze kilos";
	}
		
	if(($poids_en_kg>12)&& ($poids_en_kg<12.5)){		
		$ZonedePoidsaUtiliser="douze kilos demi";
	}
		
	if(($poids_en_kg>12.5)&& ($poids_en_kg<13.01)){		
		$ZonedePoidsaUtiliser="treize kilos";
	}
		
	if(($poids_en_kg>13)&& ($poids_en_kg<13.51)){		
		$ZonedePoidsaUtiliser="treize kilos demi";
	}
		
	if(($poids_en_kg>13.5)&& ($poids_en_kg<14.01)){		
		$ZonedePoidsaUtiliser="quatorze kilos";
	}
		
	if(($poids_en_kg>14)&& ($poids_en_kg<14.51)){		
		$ZonedePoidsaUtiliser="quatorze kilos demi";
	}
		
	if(($poids_en_kg>14.5)&& ($poids_en_kg<15.01)){		
		$ZonedePoidsaUtiliser="quinze kilos";
	}
		
	if(($poids_en_kg>15)&& ($poids_en_kg<15.51)){		
		$ZonedePoidsaUtiliser="quinze kilos demi";
	}
		
	if(($poids_en_kg>15.5)&& ($poids_en_kg<16.01)){		
		$ZonedePoidsaUtiliser="seize kilos";
	}
		
	if(($poids_en_kg>16)&& ($poids_en_kg<16.51)){		
		$ZonedePoidsaUtiliser="seize kilos demi";
	}
		
	if(($poids_en_kg>16.5)&& ($poids_en_kg<17.01)){		
		$ZonedePoidsaUtiliser="dixsept kilos";
	}
		
	if(($poids_en_kg>17)&& ($poids_en_kg<17.51)){		
		$ZonedePoidsaUtiliser="dixsept kilos demi";
	}
	
	if(($poids_en_kg>17.5)&& ($poids_en_kg<18.01)){		
		$ZonedePoidsaUtiliser="dixhuit kilos";
	}
		
	if(($poids_en_kg>18)&& ($poids_en_kg<18.51)){		
		$ZonedePoidsaUtiliser="dixhuit kilos demi";
	}
		
	if(($poids_en_kg>18.5)&& ($poids_en_kg<19.01)){		
		$ZonedePoidsaUtiliser="dixneuf kilos";
	}
		
	if(($poids_en_kg>19)&& ($poids_en_kg<19.51)){		
		$ZonedePoidsaUtiliser="dixneuf kilos demi";
	}	
		
	if(($poids_en_kg>19.5)&& ($poids_en_kg<20.01)){		
		$ZonedePoidsaUtiliser="vingt kilos";
	}	
		
	//Envoi des informations par courriel
	$message="";	
	$message="
	<html>
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
	</head>
	<body>
	<table class=\"table\" border=\"1\">
	<tr>
		<td colspan=\"2\" align=\"center\"><b>Départ</b></td>
		<td colspan=\"2\"align=\"center\"><b>Destination</b></td>
		<td colspan=\"2\"align=\"center\"><b>Bon de retour pré-imprimé</b></td>
		
	</tr>
	
	<tr align=\"center\">
		<td colspan=\"2\" align=\"center\">$lieu_depart</td>
		<td colspan=\"2\" align=\"center\">$lieu_destination</td>
		<td colspan=\"2\" align=\"center\">$bon_de_retour</td>
		
	</tr>
	
	<tr><td colspan=\"5\">&nbsp;</td></tr>
	<tr>
		<td align=\"center\"><b>Auteur de la demande</b></td>
		<td align=\"center\"><b>Type de boîte</b></td>
		<td align=\"center\"><b>Nombre d'item</b></td>
		<td align=\"center\"><b>Contenu</b></td>
		<td align=\"center\"><b>Detail du contenu</b></td>
	</tr>
	
	<tr align=\"center\">
		<td align=\"center\">$auteur</td>
		<td align=\"center\">$type_de_boite</td>
		<td align=\"center\">$nombre_item_paquet</td>
		<td align=\"center\">$contenu_paquet</td>
		<td align=\"center\">$contenu_paquet_detail&nbsp;</td>
	</tr>
	
	<tr><td colspan=\"5\">&nbsp;</td></tr>
	
	<tr>
		<td align=\"center\"><b>Poids réel ou estimation</b></td>
		<td align=\"center\"><b>Poids du paquet (Lbs)</b></td>
		<td align=\"center\"><b>Poids volumétrique</b></td>
		<td align=\"center\"><b>Poids utilisé pour calcul</b></td>
		<td align=\"center\"><b>Poids du paquet (Kg)</b></td>
	</tr>
	
	<tr align=\"center\">
		<td align=\"center\">$poids_reel_ou_estimation</td>
		<td align=\"center\">$poids_paquet<b> Lbs</b></td>
		<td align=\"center\">$PoidsVolumetrique Lbs</td>
		<td align=\"center\">$Poids_A_Utiliser ---></td>
		<td align=\"center\">$poids_en_kg<b> Kg</b></td>
	</tr>
	</table>
	
	<br>
	<table class=\"table\" border=\"1\">";
	
	
	
		
		
	switch($ZonedePoidsaUtiliser){
		case 'demi kilo': 
			if ($UPS_demi_kilo<$ICS_demi_kilo){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_demi_kilo;
				$Offre_UPS = $UPS_demi_kilo;
				$Offre_ICS = $ICS_demi_kilo;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_demi_kilo;
				$Offre_UPS = $UPS_demi_kilo;
				$Offre_ICS = $ICS_demi_kilo;
			}//END IF	
		break;
		
		case 'kilo':
			if ($UPS_kilo<$ICS_kilo){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_kilo;
				$Offre_UPS = $UPS_kilo;
				$Offre_ICS = $ICS_kilo;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_kilo;
				$Offre_UPS = $UPS_kilo;
				$Offre_ICS = $ICS_kilo;
			}//END IF
		break;
			
		case 'kilo demi': 	
			if ($UPS_kilo_demi<$ICS_kilo_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_kilo_demi;
				$Offre_UPS = $UPS_kilo_demi;
				$Offre_ICS = $ICS_kilo_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_kilo_demi;
				$Offre_UPS = $UPS_kilo_demi;
				$Offre_ICS = $ICS_kilo_demi;
			}//END IF
		break;
			
		case 'deux kilos': 	
			if ($UPS_deux_kilos<$ICS_deux_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_deux_kilos;
				$Offre_UPS = $UPS_deux_kilos;
				$Offre_ICS = $ICS_deux_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_deux_kilos;
				$Offre_UPS = $UPS_deux_kilos;
				$Offre_ICS = $ICS_deux_kilos;
			}//END IF
		break;	
			
		case 'deux kilos demi': 	
			if ($UPS_deux_kilos_demi<$ICS_deux_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_deux_kilos_demi;
				$Offre_UPS = $UPS_deux_kilos_demi;
				$Offre_ICS = $ICS_deux_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_deux_kilos_demi;
				$Offre_UPS = $UPS_deux_kilos_demi;
				$Offre_ICS = $ICS_deux_kilos_demi;
			}//END IF
		break;	
			
		case 'trois kilos': 	
			if ($UPS_trois_kilos<$ICS_trois_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_trois_kilos;
				$Offre_UPS = $UPS_trois_kilos;
				$Offre_ICS = $ICS_trois_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_trois_kilos;
				$Offre_UPS = $UPS_trois_kilos;
				$Offre_ICS = $ICS_trois_kilos;
			}//END IF
		break;	
			
			
		case 'trois kilos demi': 	
			if ($UPS_trois_kilos_demi<$ICS_trois_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_trois_kilos_demi;
				$Offre_UPS = $UPS_trois_kilos_demi;
				$Offre_ICS = $ICS_trois_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_trois_kilos_demi;
				$Offre_UPS = $UPS_trois_kilos_demi;
				$Offre_ICS = $ICS_trois_kilos_demi;
			}//END IF
		break;	
			
		case 'quatre kilos': 	
			if ($UPS_quatre_kilos<$ICS_quatre_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quatre_kilos;
				$Offre_UPS = $UPS_quatre_kilos;
				$Offre_ICS = $ICS_quatre_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quatre_kilos;
				$Offre_UPS = $UPS_quatre_kilos;
				$Offre_ICS = $ICS_quatre_kilos;
			}//END IF
		break;
			
		case 'quatre kilos demi': 	
			if ($UPS_quatre_kilos_demi<$ICS_quatre_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quatre_kilos_demi;
				$Offre_UPS = $UPS_quatre_kilos_demi;
				$Offre_ICS = $ICS_quatre_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quatre_kilos_demi;
				$Offre_UPS = $UPS_quatre_kilos_demi;
				$Offre_ICS = $ICS_quatre_kilos_demi;
			}//END IF
		break;
			
			
		case 'cinq kilos': 	
			if ($UPS_cinq_kilos<$ICS_cinq_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_cinq_kilos;
				$Offre_UPS = $UPS_cinq_kilos;
				$Offre_ICS = $ICS_cinq_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_cinq_kilos;
				$Offre_UPS = $UPS_cinq_kilos;
				$Offre_ICS = $ICS_cinq_kilos;
			}//END IF
		break;
			
			
		case 'cinq kilos demi': 	
			if ($UPS_cinq_kilos_demi<$ICS_cinq_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_cinq_kilos;
				$Offre_UPS = $UPS_cinq_kilos_demi;
				$Offre_ICS = $ICS_cinq_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_cinq_kilos_demi;
				$Offre_UPS = $UPS_cinq_kilos_demi;
				$Offre_ICS = $ICS_cinq_kilos_demi;
			}//END IF
		break;
			
			
		case 'cinq kilos demi': 	
			if ($UPS_cinq_kilos_demi<$ICS_cinq_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_cinq_kilos_demi;
				$Offre_UPS = $UPS_cinq_kilos_demi;
				$Offre_ICS = $ICS_cinq_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_cinq_kilos_demi;
				$Offre_UPS = $UPS_cinq_kilos_demi;
				$Offre_ICS = $ICS_cinq_kilos_demi;
			}//END IF
		break;		
		
			
		case 'six kilos': 	
			if ($UPS_six_kilos<$ICS_six_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_six_kilos;
				$Offre_UPS = $UPS_six_kilos;
				$Offre_ICS = $ICS_six_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_six_kilos;
				$Offre_UPS = $UPS_six_kilos;
				$Offre_ICS = $ICS_six_kilos;
			}//END IF
		break;		
			
		case 'six kilos demi': 	
			if ($UPS_six_kilos_demi<$ICS_six_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_six_kilos_demi;
				$Offre_UPS = $UPS_six_kilos_demi;
				$Offre_ICS = $ICS_six_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_six_kilos_demi;
				$Offre_UPS = $UPS_six_kilos_demi;
				$Offre_ICS = $ICS_six_kilos_demi;
			}//END IF
		break;	
			
		case 'sept kilos': 	
			if ($UPS_sept_kilos<$ICS_sept_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_sept_kilos;
				$Offre_UPS = $UPS_sept_kilos;
				$Offre_ICS = $ICS_sept_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_sept_kilos;
				$Offre_UPS = $UPS_sept_kilos;
				$Offre_ICS = $ICS_sept_kilos;
			}//END IF
		break;	
			
		case 'sept kilos demi': 	
			if ($UPS_sept_kilos_demi<$ICS_sept_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_sept_kilos_demi;
				$Offre_UPS = $UPS_sept_kilos_demi;
				$Offre_ICS = $ICS_sept_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_sept_kilos_demi;
				$Offre_UPS = $UPS_sept_kilos_demi;
				$Offre_ICS = $ICS_sept_kilos_demi;
			}//END IF
		break;	
		
			
		case 'huit kilos': 	
			if ($UPS_huit_kilos<$ICS_huit_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_huit_kilos;
				$Offre_UPS = $UPS_huit_kilos;
				$Offre_ICS = $ICS_huit_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_huit_kilos;
				$Offre_UPS = $UPS_huit_kilos;
				$Offre_ICS = $ICS_huit_kilos;
			}//END IF
		break;
			
		case 'huit kilos demi': 	
			if ($UPS_huit_kilos_demi<$ICS_huit_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_huit_kilos_demi;
				$Offre_UPS = $UPS_huit_kilos_demi;
				$Offre_ICS = $ICS_huit_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_huit_kilos_demi;
				$Offre_UPS = $UPS_huit_kilos_demi;
				$Offre_ICS = $ICS_huit_kilos_demi;
			}//END IF
		break;	
			
		case 'neuf kilos': 	
			if ($UPS_neuf_kilos<$ICS_neuf_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_neuf_kilos;
				$Offre_UPS = $UPS_neuf_kilos;
				$Offre_ICS = $ICS_neuf_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_neuf_kilos;
				$Offre_UPS = $UPS_neuf_kilos;
				$Offre_ICS = $ICS_neuf_kilos;
			}//END IF
		break;		
					
		case 'neuf kilos demi': 	
			if ($UPS_neuf_kilos_demi<$ICS_neuf_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_neuf_kilos_demi;
				$Offre_UPS = $UPS_neuf_kilos_demi;
				$Offre_ICS = $ICS_neuf_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_neuf_kilos_demi;
				$Offre_UPS = $UPS_neuf_kilos_demi;
				$Offre_ICS = $ICS_neuf_kilos_demi;
			}//END IF
		break;				
				
		case 'dix kilos': 	
			if ($UPS_dix_kilos<$ICS_dix_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dix_kilos;
				$Offre_UPS = $UPS_dix_kilos;
				$Offre_ICS = $ICS_dix_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dix_kilos;
				$Offre_UPS = $UPS_dix_kilos;
				$Offre_ICS = $ICS_dix_kilos;
			}//END IF
		break;	
			
		case 'dix kilos demi': 	
			if ($UPS_dix_kilos_demi<$ICS_dix_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dix_kilos_demi;
				$Offre_UPS = $UPS_dix_kilos_demi;
				$Offre_ICS = $ICS_dix_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dix_kilos_demi;
				$Offre_UPS = $UPS_dix_kilos_demi;
				$Offre_ICS = $ICS_dix_kilos_demi;
			}//END IF
		break;	
			
		case 'onze kilos': 	
			if ($UPS_onze_kilos<$ICS_onze_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_onze_kilos;
				$Offre_UPS = $UPS_onze_kilos;
				$Offre_ICS = $ICS_onze_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_onze_kilos;
				$Offre_UPS = $UPS_onze_kilos;
				$Offre_ICS = $ICS_onze_kilos;
			}//END IF
		break;
			
			
		case 'onze kilos demi': 	
			if ($UPS_onze_kilos_demi<$ICS_onze_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_onze_kilos_demi;
				$Offre_UPS = $UPS_onze_kilos_demi;
				$Offre_ICS = $ICS_onze_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_onze_kilos_demi;
				$Offre_UPS = $UPS_onze_kilos_demi;
				$Offre_ICS = $ICS_onze_kilos_demi;
			}//END IF
		break;
			
		case 'douze kilos': 	
			if ($UPS_douze_kilos<$ICS_douze_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_douze_kilos;
				$Offre_UPS = $UPS_douze_kilos;
				$Offre_ICS = $ICS_douze_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_douze_kilos;
				$Offre_UPS = $UPS_douze_kilos;
				$Offre_ICS = $ICS_douze_kilos;
			}//END IF
		break;	
			
		case 'douze kilos demi': 	
			if ($UPS_douze_kilos_demi<$ICS_douze_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_douze_kilos_demi;
				$Offre_UPS = $UPS_douze_kilos_demi;
				$Offre_ICS = $ICS_douze_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_douze_kilos_demi;
				$Offre_UPS = $UPS_douze_kilos_demi;
				$Offre_ICS = $ICS_douze_kilos_demi;
			}//END IF
		break;		
			
		case 'treize kilos': 	
			if ($UPS_treize_kilos<$ICS_treize_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_treize_kilos;
				$Offre_UPS = $UPS_treize_kilos;
				$Offre_ICS = $ICS_treize_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_treize_kilos;
				$Offre_UPS = $UPS_treize_kilos;
				$Offre_ICS = $ICS_treize_kilos;
			}//END IF
		break;	
			
			
		case 'treize kilos demi': 	
			if ($UPS_treize_kilos_demi<$ICS_treize_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_treize_kilos_demi;
				$Offre_UPS = $UPS_treize_kilos_demi;
				$Offre_ICS = $ICS_treize_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_treize_kilos_demi;
				$Offre_UPS = $UPS_treize_kilos_demi;
				$Offre_ICS = $ICS_treize_kilos_demi;
			}//END IF
		break;	
			
			
		case 'quatorze kilos': 	
			if ($UPS_quatorze_kilos<$ICS_quatorze_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quatorze_kilos;
				$Offre_UPS = $UPS_quatorze_kilos;
				$Offre_ICS = $ICS_quatorze_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quatorze_kilos;
				$Offre_UPS = $UPS_quatorze_kilos;
				$Offre_ICS = $ICS_quatorze_kilos;
			}//END IF
		break;	
		
			
		case 'quatorze kilos demi': 	
			if ($UPS_quatorze_kilos_demi<$ICS_quatorze_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quatorze_kilos_demi;
				$Offre_UPS = $UPS_quatorze_kilos_demi;
				$Offre_ICS = $ICS_quatorze_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quatorze_kilos_demi;
				$Offre_UPS = $UPS_quatorze_kilos_demi;
				$Offre_ICS = $ICS_quatorze_kilos_demi;
			}//END IF
		break;	
			
			
		case 'quinze kilos': 	
			if ($UPS_quinze_kilos<$ICS_quinze_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quinze_kilos;
				$Offre_UPS = $UPS_quinze_kilos;
				$Offre_ICS = $ICS_quinze_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quinze_kilos;
				$Offre_UPS = $UPS_quinze_kilos;
				$Offre_ICS = $ICS_quinze_kilos;
			}//END IF
		break;	
			
			
		case 'quinze kilos demi': 	
			if ($UPS_quinze_kilos_demi<$ICS_quinze_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_quinze_kilos_demi;
				$Offre_UPS = $UPS_quinze_kilos_demi;
				$Offre_ICS = $ICS_quinze_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_quinze_kilos_demi;
				$Offre_UPS = $UPS_quinze_kilos_demi;
				$Offre_ICS = $ICS_quinze_kilos_demi;
			}//END IF
		break;	
		
			
		case 'seize kilos': 	
			if ($UPS_seize_kilos<$ICS_seize_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_seize_kilos;
				$Offre_UPS = $UPS_seize_kilos;
				$Offre_ICS = $ICS_seize_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_seize_kilos;
				$Offre_UPS = $UPS_seize_kilos;
				$Offre_ICS = $ICS_seize_kilos;
			}//END IF
		break;	
			
			
		case 'seize kilos demi': 	
			if ($UPS_seize_kilos_demi<$ICS_seize_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_seize_kilos_demi;
				$Offre_UPS = $UPS_seize_kilos_demi;
				$Offre_ICS = $ICS_seize_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_seize_kilos_demi;
				$Offre_UPS = $UPS_seize_kilos_demi;
				$Offre_ICS = $ICS_seize_kilos_demi;
			}//END IF
		break;
			
		case 'dixsept kilos': 	
			if ($UPS_dixsept_kilos<$ICS_dixsept_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixsept_kilos;
				$Offre_UPS = $UPS_dixsept_kilos;
				$Offre_ICS = $ICS_dixsept_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixsept_kilos;
				$Offre_UPS = $UPS_dixsept_kilos;
				$Offre_ICS = $ICS_dixsept_kilos;
			}//END IF
		break;	
			
			
		case 'dixsept kilos demi': 	
			if ($UPS_dixsept_kilos_demi<$ICS_dixsept_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixsept_kilos_demi;
				$Offre_UPS = $UPS_dixsept_kilos_demi;
				$Offre_ICS = $ICS_dixsept_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixsept_kilos_demi;
				$Offre_UPS = $UPS_dixsept_kilos_demi;
				$Offre_ICS = $ICS_dixsept_kilos_demi;
			}//END IF
		break;
			
			
		case 'dixhuit kilos': 	
			if ($UPS_dixhuit_kilos<$ICS_dixhuit_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixhuit_kilos;
				$Offre_UPS = $UPS_dixhuit_kilos;
				$Offre_ICS = $ICS_dixhuit_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixhuit_kilos;
				$Offre_UPS = $UPS_dixhuit_kilos;
				$Offre_ICS = $ICS_dixhuit_kilos;
			}//END IF
		break;
			
			
		case 'dixhuit kilos demi': 	
			if ($UPS_dixhuit_kilos_demi<$ICS_dixhuit_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixhuit_kilos_demi;
				$Offre_UPS = $UPS_dixhuit_kilos_demi;
				$Offre_ICS = $ICS_dixhuit_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixhuit_kilos_demi;
				$Offre_UPS = $UPS_dixhuit_kilos_demi;
				$Offre_ICS = $ICS_dixhuit_kilos_demi;
			}//END IF
		break;
			
		case 'dixneuf kilos': 	
			if ($UPS_dixneuf_kilos<$ICS_dixneuf_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixneuf_kilos;
				$Offre_UPS = $UPS_dixneuf_kilos;
				$Offre_ICS = $ICS_dixneuf_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixneuf_kilos;
				$Offre_UPS = $UPS_dixneuf_kilos;
				$Offre_ICS = $ICS_dixneuf_kilos;
			}//END IF
		break;
			
		case 'dixneuf kilos demi': 	
			if ($UPS_dixneuf_kilos_demi<$ICS_dixneuf_kilos_demi){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_dixneuf_kilos_demi;
				$Offre_UPS = $UPS_dixneuf_kilos_demi;
				$Offre_ICS = $ICS_dixneuf_kilos_demi;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_dixneuf_kilos_demi;
				$Offre_UPS = $UPS_dixneuf_kilos_demi;
				$Offre_ICS = $ICS_dixneuf_kilos_demi;
			}//END IF
		break;
			
			
		case 'vingt kilos': 	
			if ($UPS_vingt_kilos<$ICS_vingt_kilos){
				$CompagnieOffrantLeMeilleurPrix	= "UPS";
				$PrixLePlusBasOffert			= $UPS_vingt_kilos;
				$Offre_UPS = $UPS_vingt_kilos;
				$Offre_ICS = $ICS_vingt_kilos;
			}else{
				$CompagnieOffrantLeMeilleurPrix	= "ICS";
				$PrixLePlusBasOffert			= $ICS_vingt_kilos;
				$Offre_UPS = $UPS_vingt_kilos;
				$Offre_ICS = $ICS_vingt_kilos;
			}//END IF
		break;
	
					
			
	}//End SWITCH

	
	$nombre_de_collone_de_prix=20;

	switch($ZonedePoidsaUtiliser){
		case 'demi kilo': 
		$message.="
		<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
		<tr>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr><tr align=\"center\">";
			
		if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_demi_kilo$</b></td>";
		else	
		$message.="<td  align=\"center\">$ICS_demi_kilo$</td>";	
			
		$message.="	
		<td align=\"center\">$ICS_kilo$</td>
		<td align=\"center\">$ICS_kilo_demi$</td>
		<td align=\"center\">$ICS_deux_kilos$</td>	
		<td align=\"center\">$ICS_deux_kilos_demi$</td>
		<td align=\"center\">$ICS_trois_kilos$</td>
		<td align=\"center\">$ICS_trois_kilos_demi$</td>
		<td align=\"center\">$ICS_quatre_kilos$</td>
		<td align=\"center\">$ICS_quatre_kilos_demi$</td>
		<td align=\"center\">$ICS_cinq_kilos$</td>
		<td align=\"center\">$ICS_cinq_kilos_demi$</td>
		<td align=\"center\">$ICS_six_kilos$</td>
		<td align=\"center\">$ICS_six_kilos_demi$</td>
		<td align=\"center\">$ICS_sept_kilos$</td>
		<td align=\"center\">$ICS_sept_kilos_demi$</td>
		<td align=\"center\">$ICS_huit_kilos$</td>
		<td align=\"center\">$ICS_huit_kilos_demi$</td>
		<td align=\"center\">$ICS_neuf_kilos$</td>
		<td align=\"center\">$ICS_neuf_kilos_demi$</td>
		<td align=\"center\">$ICS_dix_kilos$</td>
		</tr>

<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
		
		
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
		<tr>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
	</tr>
	<tr align=\"center\">";
		
		
		if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_demi_kilo$</b></td>";
		else
		$message.="<td  align=\"center\">$UPS_demi_kilo$</td>";
		
		$message.="	
		<td align=\"center\">$UPS_kilo$</td>
		<td align=\"center\">$UPS_kilo_demi$</td>
		<td align=\"center\">$UPS_deux_kilos$</td>	
		<td align=\"center\">$UPS_deux_kilos_demi$</td>	
		<td align=\"center\">$UPS_trois_kilos$</td>	
		<td align=\"center\">$UPS_trois_kilos_demi$</td>	
		<td align=\"center\">$UPS_quatre_kilos$</td>
		<td align=\"center\">$UPS_quatre_kilos_demi$</td>
		<td align=\"center\">$UPS_cinq_kilos$</td>
		<td align=\"center\">$UPS_cinq_kilos_demi$</td>
		<td align=\"center\">$UPS_six_kilos$</td>
		<td align=\"center\">$UPS_six_kilos_demi$</td>
		<td align=\"center\">$UPS_sept_kilos$</td>
		<td align=\"center\">$UPS_sept_kilos_demi$</td>
		<td align=\"center\">$UPS_huit_kilos$</td>
		<td align=\"center\">$UPS_huit_kilos_demi$</td>
		<td align=\"center\">$UPS_neuf_kilos$</td>
		<td align=\"center\">$UPS_neuf_kilos_demi$</td>
		<td align=\"center\">$UPS_dix_kilos$</td>
		</tr>
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
		break;
			
			
		case 'kilo': 
		$message.="
		<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">";
		
		$message.="<td align=\"center\">$ICS_demi_kilo$</td>";
	
		if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_kilo$</b></td>";
		else	
		$message.="<td  align=\"center\">$ICS_kilo$</td>";	
	
		$message.="<td align=\"center\">$ICS_kilo_demi$</td>
		<td align=\"center\">$ICS_deux_kilos$</td>	
		<td align=\"center\">$ICS_deux_kilos_demi$</td>
		<td align=\"center\">$ICS_trois_kilos$</td>
		<td align=\"center\">$ICS_trois_kilos_demi$</td>
		<td align=\"center\">$ICS_quatre_kilos$</td>
		<td align=\"center\">$ICS_quatre_kilos_demi$</td>
		<td align=\"center\">$ICS_cinq_kilos$</td>
		<td align=\"center\">$ICS_cinq_kilos_demi$</td>
		<td align=\"center\">$ICS_six_kilos$</td>
		<td align=\"center\">$ICS_six_kilos_demi$</td>
		<td align=\"center\">$ICS_sept_kilos$</td>
		<td align=\"center\">$ICS_sept_kilos_demi$</td>
		<td align=\"center\">$ICS_huit_kilos$</td>
		<td align=\"center\">$ICS_huit_kilos_demi$</td>
		<td align=\"center\">$ICS_neuf_kilos$</td>
		<td align=\"center\">$ICS_neuf_kilos_demi$</td>
		<td align=\"center\">$ICS_dix_kilos$</td>
		</tr>
		
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
		
		<tr><td colspan=\"5\">&nbsp;</td></tr>
		<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
	</tr>
	<tr align=\"center\">
		<td align=\"center\">$UPS_demi_kilo$</td>";
		
		if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_kilo$</b></td>";
		else
		$message.="<td  align=\"center\">$UPS_kilo$</td>";
			
		$message.="
		<td align=\"center\">$UPS_kilo_demi$</td>
		<td align=\"center\">$UPS_deux_kilos$</td>	
		<td align=\"center\">$UPS_deux_kilos_demi$</td>	
		<td align=\"center\">$UPS_trois_kilos$</td>	
		<td align=\"center\">$UPS_trois_kilos_demi$</td>	
		<td align=\"center\">$UPS_quatre_kilos$</td>	
		<td align=\"center\">$UPS_quatre_kilos_demi$</td>
		<td align=\"center\">$UPS_cinq_kilos$</td>
		<td align=\"center\">$UPS_cinq_kilos_demi$</td>
		<td align=\"center\">$UPS_six_kilos$</td>
		<td align=\"center\">$UPS_six_kilos_demi$</td>
		<td align=\"center\">$UPS_sept_kilos$</td>
		<td align=\"center\">$UPS_sept_kilos_demi$</td>
		<td align=\"center\">$UPS_huit_kilos$</td>
		<td align=\"center\">$UPS_huit_kilos_demi$</td>
		<td align=\"center\">$UPS_neuf_kilos$</td>
		<td align=\"center\">$UPS_neuf_kilos_demi$</td>
		<td align=\"center\">$UPS_dix_kilos$</td>
	</tr>
	
	<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
		break;
			
		case 'kilo demi': 
		$message.="
		<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
		<td align=\"center\">$ICS_demi_kilo$</td>
		<td align=\"center\">$ICS_kilo$</td>";
		
		if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_kilo_demi$</b></td>";
		else	
		$message.="<td  align=\"center\">$ICS_kilo_demi$</td>";		

		$message.="
		<td align=\"center\">$ICS_deux_kilos$</td>	
		<td align=\"center\">$ICS_deux_kilos_demi</td>	
		<td align=\"center\">$ICS_trois_kilos$</td>
		<td align=\"center\">$ICS_trois_kilos_demi$</td>
		<td align=\"center\">$ICS_quatre_kilos$</td>
		<td align=\"center\">$ICS_quatre_kilos_demi$</td>
		<td align=\"center\">$ICS_cinq_kilos$</td>
		<td align=\"center\">$ICS_cinq_kilos_demi$</td>
		<td align=\"center\">$ICS_six_kilos$</td>
		<td align=\"center\">$ICS_six_kilos_demi$</td>
		<td align=\"center\">$ICS_sept_kilos$</td>
		<td align=\"center\">$ICS_sept_kilos_demi$</td>
		<td align=\"center\">$ICS_huit_kilos$</td>
		<td align=\"center\">$ICS_huit_kilos_demi$</td>
		<td align=\"center\">$ICS_neuf_kilos$</td>
		<td align=\"center\">$ICS_neuf_kilos_demi$</td>
		<td align=\"center\">$ICS_dix_kilos$</td>
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
		
		</tr>
		<tr><td colspan=\"5\">&nbsp;</td></tr>
		<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>";
			
		if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_kilo_demi$</b></td>";
		else
		$message.="<td  align=\"center\">$UPS_kilo_demi$</td>";
		
		$message.="<td align=\"center\">$UPS_deux_kilos$</td>
		<td align=\"center\">$UPS_deux_kilos_demi$</td>	
		<td align=\"center\">$UPS_trois_kilos$</td>	
		<td align=\"center\">$UPS_trois_kilos_demi$</td>	
		<td align=\"center\">$UPS_quatre_kilos$</td>	
		<td align=\"center\">$UPS_quatre_kilos_demi$</td>
		<td align=\"center\">$UPS_cinq_kilos$</td>
		<td align=\"center\">$UPS_cinq_kilos_demi$</td>
		<td align=\"center\">$UPS_six_kilos$</td>
		<td align=\"center\">$UPS_six_kilos_demi$</td>
		<td align=\"center\">$UPS_sept_kilos$</td>
		<td align=\"center\">$UPS_sept_kilos_demi$</td>
		<td align=\"center\">$UPS_huit_kilos$</td>
		<td align=\"center\">$UPS_huit_kilos_demi$</td>
		<td align=\"center\">$UPS_neuf_kilos$</td>
		<td align=\"center\">$UPS_neuf_kilos_demi$</td>
		<td align=\"center\">$UPS_dix_kilos$</td>
		</tr>
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
		break;
			
			
		case 'deux kilos': 
		$message.="
		<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
		<td align=\"center\">$ICS_demi_kilo$</td>
		<td align=\"center\">$ICS_kilo$</td>
		<td align=\"center\">$ICS_kilo_demi$</td>";
		
		if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_deux_kilos$</b></td>";
		else	
		$message.="<td  align=\"center\">$ICS_deux_kilos$</td>";	
			
		$message.="<td  align=\"center\">$ICS_deux_kilos_demi$</td>
		<td align=\"center\">$ICS_trois_kilos$</td>
		<td align=\"center\">$ICS_trois_kilos_demi$</td>
		<td align=\"center\">$ICS_quatre_kilos$</td>
		<td align=\"center\">$ICS_quatre_kilos_demi$</td>
		<td align=\"center\">$ICS_cinq_kilos$</td>
		<td align=\"center\">$ICS_cinq_kilos_demi$</td>
		<td align=\"center\">$ICS_six_kilos$</td>
		<td align=\"center\">$ICS_six_kilos_demi$</td>
		<td align=\"center\">$ICS_sept_kilos$</td>
		<td align=\"center\">$ICS_sept_kilos_demi$</td>
		<td align=\"center\">$ICS_huit_kilos$</td>
		<td align=\"center\">$ICS_huit_kilos_demi$</td>
		<td align=\"center\">$ICS_neuf_kilos$</td>
		<td align=\"center\">$ICS_neuf_kilos_demi$</td>
		<td align=\"center\">$ICS_dix_kilos$</td>
		</tr>
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>

		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		

		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>2 Kg</b></td>
		<td align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
		<td align=\"center\">$UPS_demi_kilo$</td>
		<td align=\"center\">$UPS_kilo$</td>
		<td align=\"center\">$UPS_kilo_demi$</td>";
		
		if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_deux_kilos$</b></td>";
		else
		$message.="<td  align=\"center\">$UPS_deux_kilos$</td>";
			
		$message.="
		<td align=\"center\">$UPS_deux_kilos_demi$</td>	
		<td align=\"center\">$UPS_trois_kilos$</td>	
		<td align=\"center\">$UPS_trois_kilos_demi$</td>	
		<td align=\"center\">$UPS_quatre_kilos$</td>	
		<td align=\"center\">$UPS_quatre_kilos_demi$</td>
		<td align=\"center\">$UPS_cinq_kilos$</td>
		<td align=\"center\">$UPS_cinq_kilos_demi$</td>
		<td align=\"center\">$UPS_six_kilos$</td>
		<td align=\"center\">$UPS_six_kilos_demi$</td>
		<td align=\"center\">$UPS_sept_kilos$</td>
		<td align=\"center\">$UPS_sept_kilos_demi$</td>
		<td align=\"center\">$UPS_huit_kilos$</td>
		<td align=\"center\">$UPS_huit_kilos_demi$</td>
		<td align=\"center\">$UPS_neuf_kilos$</td>
		<td align=\"center\">$UPS_neuf_kilos_demi$</td>
		<td align=\"center\">$UPS_dix_kilos$</td>
		</tr>
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
		break;	
			
			
		case 'deux kilos demi': 
		$message.="
		<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2.0 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
		<td align=\"center\">$ICS_demi_kilo$</td>
		<td align=\"center\">$ICS_kilo$</td>
		<td align=\"center\">$ICS_kilo_demi$</td>";
		$message.="<td  align=\"center\">$ICS_deux_kilos$</td>";
			
		if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_deux_kilos_demi$</b></td>";
		else	
		$message.="<td  align=\"center\">$ICS_deux_kilos_demi$</td>";	
			
		$message.="
		<td align=\"center\">$ICS_trois_kilos$</td>
		<td align=\"center\">$ICS_trois_kilos_demi$</td>
		<td align=\"center\">$ICS_quatre_kilos$</td>
		<td align=\"center\">$ICS_quatre_kilos_demi$</td>
		<td align=\"center\">$ICS_cinq_kilos$</td>
		<td align=\"center\">$ICS_cinq_kilos_demi$</td>
		<td align=\"center\">$ICS_six_kilos$</td>
		<td align=\"center\">$ICS_six_kilos_demi$</td>
		<td align=\"center\">$ICS_sept_kilos$</td>
		<td align=\"center\">$ICS_sept_kilos_demi$</td>
		<td align=\"center\">$ICS_huit_kilos$</td>
		<td align=\"center\">$ICS_huit_kilos_demi$</td>
		<td align=\"center\">$ICS_neuf_kilos$</td>
		<td align=\"center\">$ICS_neuf_kilos_demi$</td>
		<td align=\"center\">$ICS_dix_kilos$</td>
		</tr>
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
		
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
		<tr>
		<td align=\"center\"><b>0.5 Kg</b></td>
		<td align=\"center\"><b>1 Kg</b></td>
		<td align=\"center\"><b>1.5 Kg</b></td>
		<td align=\"center\"><b>2 Kg</b></td>
		<td bgcolor=\"#EFA3A5\" align=\"center\"><b>2.5 Kg</b></td>
		<td align=\"center\"><b>3 Kg</b></td>
		<td align=\"center\"><b>3.5 Kg</b></td>
		<td align=\"center\"><b>4 Kg</b></td>
		<td align=\"center\"><b>4.5 Kg</b></td>
		<td align=\"center\"><b>5 Kg</b></td>
		<td align=\"center\"><b>5.5 Kg</b></td>
		<td align=\"center\"><b>6 Kg</b></td>
		<td align=\"center\"><b>6.5 Kg</b></td>
		<td align=\"center\"><b>7 Kg</b></td>
		<td align=\"center\"><b>7.5 Kg</b></td>
		<td align=\"center\"><b>8 Kg</b></td>
		<td align=\"center\"><b>8.5 Kg</b></td>
		<td align=\"center\"><b>9 Kg</b></td>
		<td align=\"center\"><b>9.5 Kg</b></td>
		<td align=\"center\"><b>10 Kg</b></td>
		</tr>
		<tr align=\"center\">
		<td align=\"center\">$UPS_demi_kilo$</td>
		<td align=\"center\">$UPS_kilo$</td>
		<td align=\"center\">$UPS_kilo_demi$</td>
		<td align=\"center\">$UPS_deux_kilos$</td>";
		
		if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
		$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_deux_kilos_demi$</b></td>";
		else
		$message.="<td  align=\"center\">$UPS_deux_kilos_demi$</td>";
			
		$message.="
		<td align=\"center\">$UPS_trois_kilos$</td>	
		<td align=\"center\">$UPS_trois_kilos_demi$</td>	
		<td align=\"center\">$UPS_quatre_kilos$</td>
		<td align=\"center\">$UPS_quatre_kilos_demi$</td>
		<td align=\"center\">$UPS_cinq_kilos$</td>
		<td align=\"center\">$UPS_cinq_kilos_demi$</td>
		<td align=\"center\">$UPS_six_kilos$</td>
		<td align=\"center\">$UPS_six_kilos_demi$</td>
		<td align=\"center\">$UPS_sept_kilos$</td>
		<td align=\"center\">$UPS_sept_kilos_demi$</td>
		<td align=\"center\">$UPS_huit_kilos$</td>
		<td align=\"center\">$UPS_huit_kilos_demi$</td>
		<td align=\"center\">$UPS_neuf_kilos$</td>
		<td align=\"center\">$UPS_neuf_kilos_demi$</td>
		<td align=\"center\">$UPS_dix_kilos$</td>
		</tr>
		<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>
		";	
		break;	
			
		
			
			
		case 'trois kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>";
			$message.="<td  align=\"center\">$ICS_deux_kilos$</td>";
			$message.="<td  align=\"center\">$ICS_deux_kilos_demi$</td>";	

			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_trois_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_trois_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		<tr>
		<td align=\"center\">$ICS_dix_kilos_demi$</td>
		<td align=\"center\">$ICS_onze_kilos$</td>
		<td align=\"center\">$ICS_onze_kilos_demi$</td>	
		<td align=\"center\">$ICS_douze_kilos$</td>
		<td align=\"center\">$ICS_douze_kilos_demi$</td>
		<td align=\"center\">$ICS_treize_kilos$</td>
		<td align=\"center\">$ICS_treize_kilos_demi$</td>
		<td align=\"center\">$ICS_quatorze_kilos$</td>
		<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
		<td align=\"center\">$ICS_quinze_kilos$</td>
		<td align=\"center\">$ICS_quinze_kilos$_demi</td>
		<td align=\"center\">$ICS_seize_kilos$</td>
		<td align=\"center\">$ICS_seize_kilos_demi$</td>
		<td align=\"center\">$ICS_dixsept_kilos$</td>
		<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
		<td align=\"center\">$ICS_dixhuit_kilos$</td>
		<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$ICS_dixneuf_kilos$</td>
		<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$ICS_vingt_kilos$</td>
		</tr>
			
			

			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_trois_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_trois_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_trois_kilos_demi$</td>	
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
		break;	//FIN TROIS KILOS
						
			
			
			
		case 'trois kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_trois_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_trois_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_trois_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_trois_kilos_demi$</td>";

			$message.="	
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>
			";	
		break;	
		//FIN TROIS KILOS DEMI		
		
			
			
		case 'quatre kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quatre_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_quatre_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quatre_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_quatre_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
			break;	
		//FIN QUATRE KILOS
			
			
			
		case 'quatre kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quatre_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_quatre_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quatre_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_quatre_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN QUATRE KILOS DEMI
			

		case 'cinq kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_cinq_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_cinq_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_cinq_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_cinq_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN CINQ KILOS
			
			
		case 'cinq kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_cinq_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_cinq_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_cinq_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_cinq_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN CINQ KILOS DEMI
			
		
			
		case 'six kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_six_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_six_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_six_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_six_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN SIX KILOS	
			
			
			
			
			
			
		case 'six kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_six_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_six_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_six_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_six_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
			break;	
		//FIN SIX KILOS DEMI
			
		
			
			
		case 'sept kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_sept_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_sept_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_sept_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_sept_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN SEPT KILOS 	
		
			
			
		case 'sept kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_sept_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_sept_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_sept_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_sept_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN SEPT KILOS DEMI	
			
			
		case 'huit kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_huit_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_huit_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>

			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_huit_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_huit_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN HUIT KILOS	
			
			
			
			
			
			case 'huit kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_huit_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_huit_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_huit_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_huit_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN HUIT KILOS DEMI	
			
			
			
		case 'neuf kilos': 
		
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_neuf_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_neuf_kilos$</td>";	

			$message.="
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td  align=\"center\">$UPS_huit_kilos_demi$</td>";

			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_neuf_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_neuf_kilos$</td>";

			$message.="
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN NEUF KILOS	
			
			
			
			
		case 'neuf kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_neuf_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_neuf_kilos_demi$</td>";	

			$message.="
			<td align=\"center\">$ICS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>";

			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_neuf_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_neuf_kilos_demi$</td>";

			$message.="
			<td align=\"center\">$UPS_dix_kilos$</td>
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td align=\"center\"><b>10.5 Kg</b></td>
		<td align=\"center\"><b>11 Kg</b></td>
		<td align=\"center\"><b>11.5 Kg</b></td>
		<td align=\"center\"><b>12 Kg</b></td>
		<td align=\"center\"><b>12.5 Kg</b></td>
		<td align=\"center\"><b>13 Kg</b></td>
		<td align=\"center\"><b>13.5 Kg</b></td>
		<td align=\"center\"><b>14 Kg</b></td>
		<td align=\"center\"><b>14.5 Kg</b></td>
		<td align=\"center\"><b>15 Kg</b></td>
		<td align=\"center\"><b>15.5 Kg</b></td>
		<td align=\"center\"><b>16 Kg</b></td>
		<td align=\"center\"><b>16.5 Kg</b></td>
		<td align=\"center\"><b>17 Kg</b></td>
		<td align=\"center\"><b>17.5 Kg</b></td>
		<td align=\"center\"><b>18 Kg</b></td>
		<td align=\"center\"><b>18.5 Kg</b></td>
		<td align=\"center\"><b>19 Kg</b></td>
		<td align=\"center\"><b>19.5 Kg</b></td>
		<td align=\"center\"><b>20 Kg</b></td>
		</tr>
		
		<tr>
		<td align=\"center\">$UPS_dix_kilos_demi$</td>
		<td align=\"center\">$UPS_onze_kilos$</td>
		<td align=\"center\">$UPS_onze_kilos_demi$</td>	
		<td align=\"center\">$UPS_douze_kilos$</td>
		<td align=\"center\">$UPS_douze_kilos_demi$</td>
		<td align=\"center\">$UPS_treize_kilos$</td>
		<td align=\"center\">$UPS_treize_kilos_demi$</td>
		<td align=\"center\">$UPS_quatorze_kilos$</td>
		<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
		<td align=\"center\">$UPS_quinze_kilos$</td>
		<td align=\"center\">$UPS_quinze_kilos_demi$</td>
		<td align=\"center\">$UPS_seize_kilos$</td>
		<td align=\"center\">$UPS_seize_kilos_demi$</td>
		<td align=\"center\">$UPS_dixsept_kilos$</td>
		<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
		<td align=\"center\">$UPS_dixhuit_kilos$</td>
		<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
		<td align=\"center\">$UPS_dixneuf_kilos$</td>
		<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
		<td align=\"center\">$UPS_vingt_kilos$</td>
		</tr>";	
			break;	
		//FIN NEUF KILOS DEMI
			
			
			
			
			
			
			case 'dix kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>";	
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dix_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>";

			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dix_kilos$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>

			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIX KILOS
			
		
	
		case 'dix kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dix_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_dix_kilos_demi$</td>";	
		
			$message.="<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dix_kilos_demi$</b></td>";
			else
			$message.="<td  align=\"center\">$UPS_dix_kilos_demi$</td>";
			
			$message.="
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIX KILOS	DEMI
			
			
			
			
		case 'onze kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td  align=\"center\">$ICS_dix_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_onze_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_onze_kilos$</td>";	
		
			$message.="
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td  bgcolor=\"#EFA3A5\"   align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td  align=\"center\">$UPS_dix_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_onze_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_onze_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_onze_kilos_demi$</td>	
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN ONZE KILOS
			
			
			
			
			
		case 'onze kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td  align=\"center\">$ICS_dix_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_onze_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_onze_kilos$</td>";	
		
			$message.="
			<td align=\"center\">$ICS_onze_kilos_demi$</td>	
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\"><b>$UPS_onze_kilos$</b></td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_onze_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_onze_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN ONZE KILOS DEMI	
			
			
		case 'douze kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td  align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>	";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_onze_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_onze_kilos_demi$</td>";	
		
			$message.="
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_douze_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_douze_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DOUZE KILOS 
			
			
		
		case 'douze kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td  align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>	";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_onze_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\">$ICS_onze_kilos_demi$</td>";	
		
			$message.="
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_douze_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_douze_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DOUZE KILOS DEMI
		
			
		case 'treize kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_treize_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_treize_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_treize_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_treize_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN TREIZE KILOS	
			
			
			
		case 'treize kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td  bgcolor=\"#EFA3A5\" align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_treize_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_treize_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_treize_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_treize_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN TREIZE KILOS DEMI	
			
			
		case 'quatorze kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quatorze_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_quatorze_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quatorze_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_quatorze_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN QUATORZE KILOS
			
			
		case 'quatorze kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quatorze_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_quatorze_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos$_demi</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quatorze_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_quatorze_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN QUATORZE KILOS DEMI	
			
			
			
		case 'quinze kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quinze_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_quinze_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quinze_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_quinze_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN QUINZE KILOS 
		
		case 'quinze kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_quinze_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_quinze_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_quinze_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_quinze_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN QUINZE KILOS DEMI 	
			
			
			
		case 'seize kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td  bgcolor=\"#EFA3A5\" align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_seize_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_seize_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_seize_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_seize_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN SEIZE KILOS 	
			
		
		case 'seize kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_seize_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_seize_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_seize_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_seize_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN SEIZE KILOS DEMI	
			
			
		case 'dixsept kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixsept_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixsept_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixsept_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixsept_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXSEPT KILOS	
			
			
			
	case 'dixsept kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixsept_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixsept_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixsept_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixsept_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXSEPT KILOS	DEMI
			
			
			
	case 'dixhuit kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixhuit_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixhuit_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixhuit_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixhuit_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXHUIT KILOS	
			
			
			
		case 'dixhuit kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixhuit_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixhuit_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixhuit_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixhuit_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXHUIT KILOS DEMI
			
			
	case 'dixneuf kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixneuf_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixneuf_kilos$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\"  align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixneuf_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixneuf_kilos$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXNEUF KILOS 
			
		
		case 'dixneuf kilos demi': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_dixneuf_kilos_demi$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_dixneuf_kilos_demi$</b></td>";	
		
			$message.="
			<td align=\"center\">$ICS_vingt_kilos$</td>
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>19.5 Kg</b></td>
			<td align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_dixneuf_kilos_demi$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_dixneuf_kilos_demi$</b></td>";
			
			$message.="
			<td align=\"center\">$UPS_vingt_kilos$</td>
			</tr>";	
			break;	
		//FIN DIXNEUF KILOS DEMI
			
			
		case 'vingt kilos': 
			$message.="
			<tr> <td bgcolor=\"#ffbc3c\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>ICS Zone: <b>$Zone_ICS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2.0 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$ICS_demi_kilo$</td>
			<td align=\"center\">$ICS_kilo$</td>
			<td align=\"center\">$ICS_kilo_demi$</td>
			<td align=\"center\">$ICS_deux_kilos$</td>
			<td align=\"center\">$ICS_deux_kilos_demi$</td>
			<td align=\"center\">$ICS_trois_kilos$</td>
			<td align=\"center\">$ICS_trois_kilos_demi$</td>
			<td align=\"center\">$ICS_quatre_kilos$</td>
			<td align=\"center\">$ICS_quatre_kilos_demi$</td>
			<td align=\"center\">$ICS_cinq_kilos$</td>
			<td align=\"center\">$ICS_cinq_kilos_demi$</td>
			<td align=\"center\">$ICS_six_kilos$</td>
			<td align=\"center\">$ICS_six_kilos_demi$</td>
			<td align=\"center\">$ICS_sept_kilos$</td>
			<td align=\"center\">$ICS_sept_kilos_demi$</td>
			<td align=\"center\">$ICS_huit_kilos$</td>
			<td align=\"center\">$ICS_huit_kilos_demi$</td>
			<td align=\"center\">$ICS_neuf_kilos$</td>
			<td align=\"center\">$ICS_neuf_kilos_demi$</td>
			<td align=\"center\">$ICS_dix_kilos$</td>";	

			$message.="	
			 </tr>
			 <tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$ICS_dix_kilos_demi$</td>
			<td align=\"center\">$ICS_onze_kilos$</td>
			<td align=\"center\">$ICS_onze_kilos_demi$</td>
			<td align=\"center\">$ICS_douze_kilos$</td>
			<td align=\"center\">$ICS_douze_kilos_demi$</td>
			<td align=\"center\">$ICS_treize_kilos$</td>
			<td align=\"center\">$ICS_treize_kilos_demi$</td>
			<td align=\"center\">$ICS_quatorze_kilos$</td>
			<td align=\"center\">$ICS_quatorze_kilos_demi$</td>
			<td align=\"center\">$ICS_quinze_kilos$</td>
			<td align=\"center\">$ICS_quinze_kilos_demi$</td>
			<td align=\"center\">$ICS_seize_kilos$</td>
			<td align=\"center\">$ICS_seize_kilos_demi$</td>
			<td align=\"center\">$ICS_dixsept_kilos$</td>
			<td align=\"center\">$ICS_dixsept_kilos_demi$</td>
			<td align=\"center\">$ICS_dixhuit_kilos$</td>
			<td align=\"center\">$ICS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$ICS_dixneuf_kilos$</td>
			<td align=\"center\">$ICS_dixneuf_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="ICS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$ICS_vingt_kilos$</b></td>";
			else	
			$message.="<td  align=\"center\"><b>$ICS_vingt_kilos$</b></td>";	
		
			$message.="
			</tr>
		
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr> <td bgcolor=\"#56dcd3\" colspan=\"$nombre_de_collone_de_prix\" align=\"center\"><h3>UPS (Express Saver) Zone: <b>$Zone_UPS</b></h3></td></tr>
			<tr>
			<td align=\"center\"><b>0.5 Kg</b></td>
			<td align=\"center\"><b>1 Kg</b></td>
			<td align=\"center\"><b>1.5 Kg</b></td>
			<td align=\"center\"><b>2 Kg</b></td>
			<td align=\"center\"><b>2.5 Kg</b></td>
			<td align=\"center\"><b>3 Kg</b></td>
			<td align=\"center\"><b>3.5 Kg</b></td>
			<td align=\"center\"><b>4 Kg</b></td>
			<td align=\"center\"><b>4.5 Kg</b></td>
			<td align=\"center\"><b>5 Kg</b></td>
			<td align=\"center\"><b>5.5 Kg</b></td>
			<td align=\"center\"><b>6 Kg</b></td>
			<td align=\"center\"><b>6.5 Kg</b></td>
			<td align=\"center\"><b>7 Kg</b></td>
			<td align=\"center\"><b>7.5 Kg</b></td>
			<td align=\"center\"><b>8 Kg</b></td>
			<td align=\"center\"><b>8.5 Kg</b></td>
			<td align=\"center\"><b>9.0 Kg</b></td>
			<td align=\"center\"><b>9.5 Kg</b></td>
			<td align=\"center\"><b>10.0 Kg</b></td>
			</tr>
			<tr align=\"center\">
			<td align=\"center\">$UPS_demi_kilo$</td>
			<td align=\"center\">$UPS_kilo$</td>
			<td align=\"center\">$UPS_kilo_demi$</td>
			<td align=\"center\">$UPS_deux_kilos$</td>
			<td align=\"center\">$UPS_deux_kilos_demi$</td>
			<td align=\"center\">$UPS_trois_kilos$</td>
			<td align=\"center\">$UPS_trois_kilos_demi$</td>
			<td align=\"center\">$UPS_quatre_kilos$</td>
			<td align=\"center\">$UPS_quatre_kilos_demi$</td>
			<td align=\"center\">$UPS_cinq_kilos$</td>
			<td align=\"center\">$UPS_cinq_kilos_demi$</td>
			<td align=\"center\">$UPS_six_kilos$</td>
			<td align=\"center\">$UPS_six_kilos_demi$</td>
			<td align=\"center\">$UPS_sept_kilos$</td>
			<td align=\"center\">$UPS_sept_kilos_demi$</td>
			<td align=\"center\">$UPS_huit_kilos$</td>
			<td align=\"center\">$UPS_huit_kilos_demi$</td>
			<td align=\"center\">$UPS_neuf_kilos$</td>
			<td align=\"center\">$UPS_neuf_kilos_demi$</td>
			<td align=\"center\">$UPS_dix_kilos$</td>";

			$message.="
			</tr>
			<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
			<tr>
			<td align=\"center\"><b>10.5 Kg</b></td>
			<td align=\"center\"><b>11 Kg</b></td>
			<td align=\"center\"><b>11.5 Kg</b></td>
			<td align=\"center\"><b>12 Kg</b></td>
			<td align=\"center\"><b>12.5 Kg</b></td>
			<td align=\"center\"><b>13 Kg</b></td>
			<td align=\"center\"><b>13.5 Kg</b></td>
			<td align=\"center\"><b>14 Kg</b></td>
			<td align=\"center\"><b>14.5 Kg</b></td>
			<td align=\"center\"><b>15 Kg</b></td>
			<td align=\"center\"><b>15.5 Kg</b></td>
			<td align=\"center\"><b>16 Kg</b></td>
			<td align=\"center\"><b>16.5 Kg</b></td>
			<td align=\"center\"><b>17 Kg</b></td>
			<td align=\"center\"><b>17.5 Kg</b></td>
			<td align=\"center\"><b>18 Kg</b></td>
			<td align=\"center\"><b>18.5 Kg</b></td>
			<td align=\"center\"><b>19 Kg</b></td>
			<td align=\"center\"><b>19.5 Kg</b></td>
			<td bgcolor=\"#EFA3A5\" align=\"center\"><b>20 Kg</b></td>
			</tr>
			<tr>
			<td align=\"center\">$UPS_dix_kilos_demi$</td>
			<td align=\"center\">$UPS_onze_kilos$</td>
			<td align=\"center\">$UPS_onze_kilos_demi$</td>
			<td align=\"center\">$UPS_douze_kilos$</td>
			<td align=\"center\">$UPS_douze_kilos_demi$</td>
			<td align=\"center\">$UPS_treize_kilos$</td>
			<td align=\"center\">$UPS_treize_kilos_demi$</td>
			<td align=\"center\">$UPS_quatorze_kilos$</td>
			<td align=\"center\">$UPS_quatorze_kilos_demi$</td>
			<td align=\"center\">$UPS_quinze_kilos$</td>
			<td align=\"center\">$UPS_quinze_kilos_demi$</td>
			<td align=\"center\">$UPS_seize_kilos$</td>
			<td align=\"center\">$UPS_seize_kilos_demi$</td>
			<td align=\"center\">$UPS_dixsept_kilos$</td>
			<td align=\"center\">$UPS_dixsept_kilos_demi$</td>
			<td align=\"center\">$UPS_dixhuit_kilos$</td>
			<td align=\"center\">$UPS_dixhuit_kilos_demi$</td>
			<td align=\"center\">$UPS_dixneuf_kilos$</td>
			<td align=\"center\">$UPS_dixneuf_kilos_demi$</td>";
			
			if ($CompagnieOffrantLeMeilleurPrix=="UPS")	
			$message.="<td bgcolor=\"#52D017\"  align=\"center\"><b>$UPS_vingt_kilos$</b></td>";
			else
			$message.="<td align=\"center\"><b>$UPS_vingt_kilos$</b></td>";
			
			$message.="
			</tr>";	
			break;	
		//FIN VINGT KILOS 
			
			
	}//End Switch	
	
		
	
$message.="<tr><td colspan=\"$nombre_de_collone_de_prix\">&nbsp;</td></tr>
		<tr>
		<td colspan=\"10\" bgcolor=\"#b8e0d2\" align=\"center\"><b>Compagnie de transport à Utiliser</b></td>
		<td colspan=\"10\"  bgcolor=\"#A5D8FF\" align=\"center\"><b>Votre Token/Numéro de PO</b></td>
	</tr>
	<tr align=\"center\">
		<td colspan=\"10\" bgcolor=\"#b8e0d2\" align=\"center\"><b>$CompagnieOffrantLeMeilleurPrix</b></td>
		<td colspan=\"10\" bgcolor=\"#A5D8FF\" align=\"center\"><b>$TokenUnique</b></td>
	</tr>";
		
		
		
	$message.="
	</table>";	
	$to_address = array('rapports@direct-lens.com');
	$from_address = 'donotreply@entrepotdelalunette.com'; 
	$subject      = "Nouvelle demande d'envoi. Token généré: $TokenUnique";
	

	
		
	if (($TokenUnique<>'') && ($ErreurFrancais=='')){ 
		//SEND EMAIL
		$response = office365_mail($to_address, $from_address, $subject, null, $message);		
		$date_demande=date('Y-m-j');
		//SAUVEGARDER DANS LA BASE DE DONNÉES
		//requete pour sauvgarder dans la BD
		$InsertQuery = "INSERT INTO SHIPPING (shipping_id,lieu_depart,lieu_destination,type_boite,nombre_item_paquet,contenu_paquet,
		contenu_paquet_detail,token,poids_paquet,poids_reel_ou_estimation,auteur, compagnie_a_utiliser, montant_facturer, zone_ics, zone_ups, poids_volumetrique,
		poids_utiliser_pour_calcul, poids_utiliser_pour_calcul_en_kg,offre_ics,offre_ups,date_demande)
		VALUES ('','$lieu_depart','$lieu_destination','$type_de_boite','$nombre_item_paquet','$contenu_paquet',
		'$contenu_paquet_detail','$TokenUnique','$poids_paquet','$poids_reel_ou_estimation','$auteur','$CompagnieOffrantLeMeilleurPrix','$PrixLePlusBasOffert',
		'$Zone_ICS','$Zone_UPS','$PoidsVolumetrique','$Poids_A_Utiliser','$poids_en_kg','$Offre_ICS','$Offre_UPS','$date_demande')";
		$InsertResult = mysqli_query($con,$InsertQuery)			or die ( "Insert Query failed: " . mysqli_error($con));	
		
		//TODO Aller chercher l'ID du token qui vient d'être créé
		$queryLastID  	= "SELECT LAST_INSERT_ID() as DernierID;";
		$resultLastID 	= mysqli_query($con,$queryLastID)	or die ( "Insert Query failed: " . mysqli_error($con));	
		$DataLastID   	= mysqli_fetch_array($resultLastID,MYSQLI_ASSOC);
		//echo 'DERNIER ID CRÉÉ:'.$DataLastID[DernierID];
	}
		
		
	//VIDER LES VARIABLES	
	//echo '<br>Ré-initialisation des variables...';
	/*
	$compagnie_transport	=	"";	
	$lieu_depart 			=	"";	
	$lieu_destination 		=	"";		
	$contenu_paquet 		=	"";	
	$contenu_paquet_detail 	=	"";	
	$nombre_item_paquet 	=	"";	
	$type_de_boite			=	"";		
	$auteur					=	"";		
	$poids_paquet			=	"";		
	$poids_reel_ou_estimation=	"";		
	$hauteur_paquet			=	"";		
	$largeur_paquet			=	"";		
	$longueur_paquet		=	"";		
	*/
	//echo '..Terminée.';
	}
	
}//END IF
?><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Demande d'envoi</title>
    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>


<form action="shipping_stats.php" method="post" name="shipping_stats">

<input type="hidden" name="NouvelleDemande" id="NouvelleDemande" value="NouvelleDemande">    
	
	<div id="create-account" class="form">
           
            <h3>Détails sur l'envoi / Package details</h3>
			<h5 style="color:indigo";><strong>Tous les champs sont obligatoires / All fields are mandatory</strong></h5>
		
		<hr>
			<div class="box" style="border: 1px solid black;">  
               <p>
                <label for="lieu_depart">1) Départ / Leaving from</label>
                <select id="lieu_depart" name="lieu_depart">
                    <option value=""></option>
					<option value="EDLL Quebec"  	<?php if ($lieu_depart=="EDLL Quebec")     echo 'selected'; ?>>EDLL Québec</option>
                    <option value="Laboratoire STC" <?php if ($lieu_depart=="Laboratoire STC") echo 'selected'; ?>>Laboratoire Saint-Catharines/STC Lab</option>
					<option value="Siege Social TR" <?php if ($lieu_depart=="Siege Social TR") echo 'selected'; ?>>Siège social Trois-Rivieres/Head Office Trois-Rivieres</option>
					
					<option value="Laboratoire STC" 	<?php if ($lieu_depart=="Laboratoire STC")     echo 'selected'; ?>>Laboratoire Saint-Catharines / STC Lab</option>
					<option value="Siege Social TR" 	<?php if ($lieu_depart=="Siege Social TR")     echo 'selected'; ?>>Siège social Trois-Rivieres / Head Office Trois-Rivieres</option>
                    <option value="" disabled>ENTREPOT DE LA LUNETTE</option>
					<option value="EDLL_Chicoutimi" 	<?php if ($lieu_depart=="EDLL_Chicoutimi")     	echo 'selected'; ?>>EDLL Chicoutimi</option>
					<option value="EDLL_Drummondville" 	<?php if ($lieu_depart=="EDLL_Drummondville")  	echo 'selected'; ?>>EDLL Drummondville</option>
					<option value="EDLL_Gatineau" 		<?php if ($lieu_depart=="EDLL_Gatineau")     	echo 'selected'; ?>>EDLL Gatineau</option>
					<option value="EDLL_Granby" 		<?php if ($lieu_depart=="EDLL_Granby")     		echo 'selected'; ?>>EDLL Granby</option>
					<option value="EDLL_Laval" 			<?php if ($lieu_depart=="EDLL_Laval")     		echo 'selected'; ?>>EDLL Laval</option>
					<option value="EDLL_Levis" 			<?php if ($lieu_depart=="EDLL_Levis")     		echo 'selected'; ?>>EDLL Lévis</option>
					<option value="EDLL_Longueuil" 		<?php if ($lieu_depart=="EDLL_Longueuil")     	echo 'selected'; ?>>EDLL Longueuil</option>
					<option value="EDLL_Montreal" 		<?php if ($lieu_depart=="EDLL_Montreal")     	echo 'selected'; ?>>EDLL Montréal</option>
					<option value="EDLL_Quebec" 		<?php if ($lieu_depart=="EDLL_Quebec")     		echo 'selected'; ?>>EDLL Québec</option>
					<option value="EDLL_STJerome" 		<?php if ($lieu_depart=="EDLL_STJerome")     	echo 'selected'; ?>>EDLL Saint-Jérome</option>
					<option value="EDLL_Sherbrooke"		<?php if ($lieu_depart=="EDLL_Sherbrooke")     	echo 'selected'; ?>>EDLL Sherbrooke</option>
					<option value="EDLL_Terrebonne" 	<?php if ($lieu_depart=="EDLL_Terrebonne")     	echo 'selected'; ?>>EDLL Terrebonne</option>
					<option value="EDLL_TroisRivieres" 	<?php if ($lieu_depart=="EDLL_TroisRivieres") 	echo 'selected'; ?>>EDLL Trois-Rivières</option>
					<option value="" disabled>OPTICAL WAREHOUSE</option>
					<option value="OW_Halifax" 			<?php if ($lieu_depart=="OW_Halifax")     		echo 'selected'; ?>>OW Halifax</option>
					<option value="" disabled>HBO</option>
					<option value="HBC_88403" 			<?php if ($lieu_depart=="HBC_88403")     		echo 'selected'; ?>>HBC #88403 Bloor St.</option>
					<option value="HBC_88408" 			<?php if ($lieu_depart=="HBC_88408") 			echo 'selected'; ?>>HBC #88408 Oshawa</option>
					<option value="HBC_88409" 			<?php if ($lieu_depart=="HBC_88409") 			echo 'selected'; ?>>HBC #88409 Eglinton</option>
					<option value="HBC_88411" 			<?php if ($lieu_depart=="HBC_88411") 			echo 'selected'; ?>>HBC #88411 Sherway</option>
					<option value="HBC_88414" 			<?php if ($lieu_depart=="HBC_88414") 			echo 'selected'; ?>>HBC #88414 Yorkdale</option>
					<option value="HBC_88416" 			<?php if ($lieu_depart=="HBC_88416") 			echo 'selected'; ?>>HBC #88416 Vancouver DTN</option>
					<option value="HBC_88429" 			<?php if ($lieu_depart=="HBC_88429") 			echo 'selected'; ?>>HBC #88429 Saskatoon</option>
					<option value="HBC_88431" 			<?php if ($lieu_depart=="HBC_88431") 			echo 'selected'; ?>>HBC #88431 Calgary-DTN</option>
					<option value="HBC_88433" 			<?php if ($lieu_depart=="HBC_88433") 			echo 'selected'; ?>>HBC #88433 Polo Park</option>
					<option value="HBC_88434" 			<?php if ($lieu_depart=="HBC_88434") 			echo 'selected'; ?>>HBC #88434 Market Mall</option>
					<option value="HBC_88435" 			<?php if ($lieu_depart=="HBC_88435") 			echo 'selected'; ?>>HBC #88435 West Edmonton</option>
					<option value="HBC_88438" 			<?php if ($lieu_depart=="HBC_88438") 			echo 'selected'; ?>>HBC #88438 Metrotown</option>
					<option value="HBC_88439" 			<?php if ($lieu_depart=="HBC_88439") 			echo 'selected'; ?>>HBC #88439 Langley </option>
					<option value="HBC_88440" 			<?php if ($lieu_depart=="HBC_88440") 			echo 'selected'; ?>>HBC #88440 Rideau </option>
					<option value="HBC_88444" 			<?php if ($lieu_depart=="HBC_88444") 			echo 'selected'; ?>>HBC #88444 Mayfair</option>
                </select>
                </p> 
				
				<p>
                <label for="lieu_destination">2) Destination</label>
                <select id="lieu_destination" name="lieu_destination">
                    <option value=""></option>
                    <option value="Laboratoire STC" 	<?php if ($lieu_destination=="Laboratoire STC")     echo 'selected'; ?>>Laboratoire Saint-Catharines / STC Lab</option>
					<option value="Siege Social TR" 	<?php if ($lieu_destination=="Siege Social TR")     echo 'selected'; ?>>Siège social Trois-Rivieres / Head Office Trois-Rivieres</option>
                    <option value="" disabled>ENTREPOT DE LA LUNETTE</option>
					<option value="EDLL_Chicoutimi" 	<?php if ($lieu_destination=="EDLL_Chicoutimi")     echo 'selected'; ?>>EDLL Chicoutimi</option>
					<option value="EDLL_Drummondville" 	<?php if ($lieu_destination=="EDLL_Drummondville")  echo 'selected'; ?>>EDLL Drummondville</option>
					<option value="EDLL_Gatineau" 		<?php if ($lieu_destination=="EDLL_Gatineau")     	echo 'selected'; ?>>EDLL Gatineau</option>
					<option value="EDLL_Granby" 		<?php if ($lieu_destination=="EDLL_Granby")     	echo 'selected'; ?>>EDLL Granby</option>
					<option value="EDLL_Laval" 			<?php if ($lieu_destination=="EDLL_Laval")     		echo 'selected'; ?>>EDLL Laval</option>
					<option value="EDLL_Levis" 			<?php if ($lieu_destination=="EDLL_Levis")     		echo 'selected'; ?>>EDLL Lévis</option>
					<option value="EDLL_Longueuil" 		<?php if ($lieu_destination=="EDLL_Longueuil")     	echo 'selected'; ?>>EDLL Longueuil</option>
					<option value="EDLL_Montreal" 		<?php if ($lieu_destination=="EDLL_Montreal")     	echo 'selected'; ?>>EDLL Montréal</option>
					<option value="EDLL_Quebec" 		<?php if ($lieu_destination=="EDLL_Quebec")     	echo 'selected'; ?>>EDLL Québec</option>
					<option value="EDLL_STJerome" 		<?php if ($lieu_destination=="EDLL_STJerome")     	echo 'selected'; ?>>EDLL Saint-Jérome</option>
					<option value="EDLL_Sherbrooke"		<?php if ($lieu_destination=="EDLL_Sherbrooke")     echo 'selected'; ?>>EDLL Sherbrooke</option>
					<option value="EDLL_Terrebonne" 	<?php if ($lieu_destination=="EDLL_Terrebonne")     echo 'selected'; ?>>EDLL Terrebonne</option>
					<option value="EDLL_TroisRivieres" 	<?php if ($lieu_destination=="EDLL_TroisRivieres") 	echo 'selected'; ?>>EDLL Trois-Rivières</option>
					<option value="" disabled>OPTICAL WAREHOUSE</option>
					<option value="OW_Halifax" 			<?php if ($lieu_destination=="OW_Halifax")     		echo 'selected'; ?>>OW Halifax</option>
					<option value="" disabled>HBO</option>
					<option value="HBC_88403" 			<?php if ($lieu_destination=="HBC_88403")     		echo 'selected'; ?>>HBC #88403 Bloor St.</option>
					<option value="HBC_88408" 			<?php if ($lieu_destination=="HBC_88408") 			echo 'selected'; ?>>HBC #88408 Oshawa</option>
					<option value="HBC_88409" 			<?php if ($lieu_destination=="HBC_88409") 			echo 'selected'; ?>>HBC #88409 Eglinton</option>
					<option value="HBC_88411" 			<?php if ($lieu_destination=="HBC_88411") 			echo 'selected'; ?>>HBC #88411 Sherway</option>
					<option value="HBC_88414" 			<?php if ($lieu_destination=="HBC_88414") 			echo 'selected'; ?>>HBC #88414 Yorkdale</option>
					<option value="HBC_88416" 			<?php if ($lieu_destination=="HBC_88416") 			echo 'selected'; ?>>HBC #88416 Vancouver DTN</option>
					<option value="HBC_88429" 			<?php if ($lieu_destination=="HBC_88429") 			echo 'selected'; ?>>HBC #88429 Saskatoon</option>
					<option value="HBC_88431" 			<?php if ($lieu_destination=="HBC_88431") 			echo 'selected'; ?>>HBC #88431 Calgary-DTN</option>
					<option value="HBC_88433" 			<?php if ($lieu_destination=="HBC_88433") 			echo 'selected'; ?>>HBC #88433 Polo Park</option>
					<option value="HBC_88434" 			<?php if ($lieu_destination=="HBC_88434") 			echo 'selected'; ?>>HBC #88434 Market Mall</option>
					<option value="HBC_88435" 			<?php if ($lieu_destination=="HBC_88435") 			echo 'selected'; ?>>HBC #88435 West Edmonton</option>
					<option value="HBC_88438" 			<?php if ($lieu_destination=="HBC_88438") 			echo 'selected'; ?>>HBC #88438 Metrotown</option>
					<option value="HBC_88439" 			<?php if ($lieu_destination=="HBC_88439") 			echo 'selected'; ?>>HBC #88439 Langley </option>
					<option value="HBC_88440" 			<?php if ($lieu_destination=="HBC_88440") 			echo 'selected'; ?>>HBC #88440 Rideau </option>
					<option value="HBC_88444" 			<?php if ($lieu_destination=="HBC_88444") 			echo 'selected'; ?>>HBC #88444 Mayfair</option>
                </select>
				 </p> 	
					
				<p>	
				<label for="bon_de_retour">2B)Avez-vous un bon de retour pré-imprimé? / Do you have a pre-printed return slip ?</label>
               	<select id="bon_de_retour" name="bon_de_retour">
                <option value=""></option>
				<option value="oui" 			<?php if ($bon_de_retour=="oui") 			echo 'selected'; ?>>Oui / Yes</option>
				<option value="non" 			<?php if ($bon_de_retour=="non") 			echo 'selected'; ?>>Non / No</option>
                </select>
                </p> 
				
				<br>
				
				<p>
                <label for="contenu_paquet">3) Contenu du paquet / Content of package</label>
                <select id="contenu_paquet" name="contenu_paquet">
                    <option value=""></option>
                    <option value="Montures seulement" 	<?php if ($contenu_paquet=="Montures seulement") 	echo 'selected'; ?>>Montures seulement / Frames only</option>
					<option value="Montures et verres" 	<?php if ($contenu_paquet=="Montures et verres") 	echo 'selected'; ?>>Montures et verres montés / Mounted frames and lenses</option>
					<option value="autre" 				<?php if ($contenu_paquet=="autre") 				echo 'selected'; ?>>Autre(Veuillez spécifier*) / Other (Please specify*)</option>
                </select>
				<br>
			
                <label for="contenu_paquet_detail">3B) Si autre, spécifiez / If other, please specify:</label>
                <input name="contenu_paquet_detail" value="<?php echo $contenu_paquet_detail ?>" id="contenu_paquet_detail" type="text" />
              	</p>  
				
				<br>
				
				
				<p>
                <label for="nombre_item_paquet">4) Nombre d'items dans le paquet / Number of items in the box</label>
                <select id="nombre_item_paquet" name="nombre_item_paquet">
                    <option value=""></option>
					<option value="1" 	<?php if ($nombre_item_paquet=="1")		echo 'selected'; ?>>1</option>
					<option value="2" 	<?php if ($nombre_item_paquet=="2")		echo 'selected'; ?>>2</option>
					<option value="3" 	<?php if ($nombre_item_paquet=="3")		echo 'selected'; ?>>3</option>
					<option value="4"	<?php if ($nombre_item_paquet=="4")		echo 'selected'; ?>>4</option>
					<option value="5" 	<?php if ($nombre_item_paquet=="5")		echo 'selected'; ?>>5</option>
					<option value="6" 	<?php if ($nombre_item_paquet=="6")		echo 'selected'; ?>>6</option>
					<option value="7" 	<?php if ($nombre_item_paquet=="7")		echo 'selected'; ?>>7</option>
					<option value="8" 	<?php if ($nombre_item_paquet=="8")		echo 'selected'; ?>>8</option>
					<option value="9" 	<?php if ($nombre_item_paquet=="9")		echo 'selected'; ?>>9</option>
					<option value="10" 	<?php if ($nombre_item_paquet=="10")	echo 'selected'; ?>>10</option>					
					<option value="11" 	<?php if ($nombre_item_paquet=="11")	echo 'selected'; ?>>11</option>
					<option value="12" 	<?php if ($nombre_item_paquet=="12")	echo 'selected'; ?>>12</option>
					<option value="13" 	<?php if ($nombre_item_paquet=="13")	echo 'selected'; ?>>13</option>
					<option value="14" 	<?php if ($nombre_item_paquet=="14")	echo 'selected'; ?>>14</option>
					<option value="15" 	<?php if ($nombre_item_paquet=="15")	echo 'selected'; ?>>15</option>
					<option value="16" 	<?php if ($nombre_item_paquet=="16")	echo 'selected'; ?>>16</option>
					<option value="17" 	<?php if ($nombre_item_paquet=="17")	echo 'selected'; ?>>17</option>
					<option value="18" 	<?php if ($nombre_item_paquet=="18")	echo 'selected'; ?>>18</option>
					<option value="19" 	<?php if ($nombre_item_paquet=="19")	echo 'selected'; ?>>19</option>
					<option value="20" 	<?php if ($nombre_item_paquet=="20")	echo 'selected'; ?>>20</option>
					<option value="21" 	<?php if ($nombre_item_paquet=="21")	echo 'selected'; ?>>21</option>
					<option value="22" 	<?php if ($nombre_item_paquet=="22")	echo 'selected'; ?>>22</option>
					<option value="23" 	<?php if ($nombre_item_paquet=="23")	echo 'selected'; ?>>23</option>
					<option value="24" 	<?php if ($nombre_item_paquet=="24")	echo 'selected'; ?>>24</option>
					<option value="25" 	<?php if ($nombre_item_paquet=="25")	echo 'selected'; ?>>25</option>
					<option value="26" 	<?php if ($nombre_item_paquet=="26")	echo 'selected'; ?>>26</option>
					<option value="27" 	<?php if ($nombre_item_paquet=="27")	echo 'selected'; ?>>27</option>
					<option value="28" 	<?php if ($nombre_item_paquet=="28")	echo 'selected'; ?>>28</option>
					<option value="29" 	<?php if ($nombre_item_paquet=="29")	echo 'selected'; ?>>29</option>
					<option value="30" 	<?php if ($nombre_item_paquet=="30")	echo 'selected'; ?>>30</option>
					<option value="31" 	<?php if ($nombre_item_paquet=="31")	echo 'selected'; ?>>31</option>
					<option value="32" 	<?php if ($nombre_item_paquet=="32")	echo 'selected'; ?>>32</option>
					<option value="33" 	<?php if ($nombre_item_paquet=="33")	echo 'selected'; ?>>33</option>
					<option value="34" 	<?php if ($nombre_item_paquet=="34")	echo 'selected'; ?>>34</option>
					<option value="35" 	<?php if ($nombre_item_paquet=="35")	echo 'selected'; ?>>35</option>
					<option value="36" 	<?php if ($nombre_item_paquet=="36")	echo 'selected'; ?>>36</option>
					<option value="37" 	<?php if ($nombre_item_paquet=="37")	echo 'selected'; ?>>37</option>
					<option value="38" 	<?php if ($nombre_item_paquet=="38")	echo 'selected'; ?>>38</option>
					<option value="39" 	<?php if ($nombre_item_paquet=="39")	echo 'selected'; ?>>39</option>
					<option value="40" 	<?php if ($nombre_item_paquet=="40")	echo 'selected'; ?>>40</option>
					<option value="41" 	<?php if ($nombre_item_paquet=="41")	echo 'selected'; ?>>41</option>
					<option value="42" 	<?php if ($nombre_item_paquet=="42")	echo 'selected'; ?>>42</option>
					<option value="43" 	<?php if ($nombre_item_paquet=="43")	echo 'selected'; ?>>43</option>
					<option value="44" 	<?php if ($nombre_item_paquet=="44")	echo 'selected'; ?>>44</option>
					<option value="45" 	<?php if ($nombre_item_paquet=="45")	echo 'selected'; ?>>45</option>
					<option value="46" 	<?php if ($nombre_item_paquet=="46")	echo 'selected'; ?>>46</option>
					<option value="47" 	<?php if ($nombre_item_paquet=="47")	echo 'selected'; ?>>47</option>
					<option value="48" 	<?php if ($nombre_item_paquet=="48")	echo 'selected'; ?>>48</option>
					<option value="49" 	<?php if ($nombre_item_paquet=="49")	echo 'selected'; ?>>49</option>
					<option value="50" 	<?php if ($nombre_item_paquet=="50")	echo 'selected'; ?>>50</option>
				</select>
                </p>  
				
				<br>
				
				<p>
                <label for="type_de_boite">5) Type de boîte / Box type</label>
                <select id="type_de_boite" name="type_de_boite">
                    <option value="">&nbsp;</option>
					<option value="" disabled>Boîtes fournies par UPS / UPS provided box</option>
                    <option value="UPS(9x4x4)" 		<?php if ($type_de_boite=="UPS(9x4x4)")		echo 'selected'; ?>>UPS (9"x4"x4")</option>
					<option value="UPS(9x8x4)" 		<?php if ($type_de_boite=="UPS(9x8x4)")		echo 'selected'; ?>>UPS (9"x8"x4")</option>
					<option value="" disabled>Autre / Other</option>
					<option value="Autre" 			<?php if ($type_de_boite=="Autre")			echo 'selected'; ?>>Autre</option>
                </select>
                </p> 
			
				<label>5B) Si autre, spécifiez / If other, please specify:</label>
				<br>
				<label for="longueur_paquet">Longueur / Length:</label>
                <input name="longueur_paquet" value="<?php echo $longueur_paquet ?>" size="2" id="longueur_paquet" type="text" />
				
				<label for="largeur_paquet">Largeur / Width:</label>
				<input name="largeur_paquet" value="<?php echo $largeur_paquet ?>" size="2" id="largeur_paquet" type="text" />
				
				<label for="hauteur_paquet">Hauteur / Height:</label>
				<input name="hauteur_paquet" value="<?php echo $hauteur_paquet ?>" size="2" id="hauteur_paquet" type="text" />
              	</p> 
				
				<br>
				
				<p>	
			 		<label for="poids_paquet">6) Poids du paquet / Weight of the package: </label>
                	<input name="poids_paquet" size="4" value="<?php echo $poids_paquet ?>" id="poids_paquet" type="text" /> <b>Lbs</b>
              	</p> 
				
				
		
				<p>
                <label for="poids_reel_ou_estimation">6B) Avez vous pesé le paquet? / Did you weigh the package? </label>
                <select id="poids_reel_ou_estimation" name="poids_reel_ou_estimation">
                    <option value=""></option>
                    <option value="reel" 		<?php if ($poids_reel_ou_estimation=="reel") 		echo 'selected'; ?>>Oui / Yes</option>
					<option value="estimation" 	<?php if ($poids_reel_ou_estimation=="estimation") 	echo 'selected'; ?>>Non, c'est une estimation / No, it's an estimate</option>
                </select>
				<br><br>
					
				<p>	
			 		<label for="auteur">7) Entrez vos initiales / Enter your initials: </label>
                	<input name="auteur" size="4" value="<?php echo $auteur ?>" id="auteur" type="text" />
              	</p>
				
          	</div>

		
	
            <div class="clear"></div>
            <div class="hr"><hr /></div>    
	
		

        <input class="submit" value="Créer / Create" name="Btnnouveaucompte" type="submit" onClick="checkfr('nouveaucompte', this.name);" />
	
		
		<?php if ($TokenUnique<>'') { 
			echo '<h4 style="color:red";>'. $Message_Utilisateur_VolumetriqueFR. '</h4>';
			echo '<h4 style="color:red";>'. $Message_Utilisateur_VolumetriqueEN. '</h4>';
		}//END IF	
		?>
	
	
		
		<?php 
		$UrlPourImprimerToken = 'impression_token.php?tok=' . $TokenUnique . '&provider=' . $CompagnieOffrantLeMeilleurPrix . '&ID='.$DataLastID[DernierID]; 
		if (($TokenUnique<>'') && ($ErreurFrancais=='')){ 
			
			echo '<h4 style="color:darkgreen";>Enregistré avec Succès.'. "<a target=\"_blank\" href=\"$UrlPourImprimerToken\">".'Cliquez ici pour imprimer le Token.</a></h4>';
			
			echo '<h4 style="color:darkgreen";>Saved Sucessfully.' .      "<a target=\"_blank\" href=\"$UrlPourImprimerToken\">".'Cliquez here to print the Token.</a></h4>';
		}
		?>
        </div> 
		
</form>

<?php
	
	//echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	//$response=office365_mail($to_address, $from_address, $subject, null, $message);

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
		echo $message;
		/*if($response){ 
			echo '<h3>Résultat:Courriel envoyé avec succès aux adresses:'. $EmailEnvoyerA.'</h3><br><br>';
			//log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			echo '<h3>Résultat:Erreur durant l\'envoi du courriel, svp aviser Charles</h3>';
			//log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}*/	
		

?>