<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");

$date1 = "2018-01-01";
$date2 = "2018-03-31";
//$date1 = "2017-10-01";
//$date2 = "2017-12-31";
//VENTES PAR SUCCURSALE
//TR
$rptQueryTR="SELECT count(*) as NbrCommandesTR from ORDERS  WHERE user_id in   ('entrepotifc','entrepotsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultTR = mysql_query($rptQueryTR)		or die  ('I cannot select items because 1: ' . mysql_error());
$DataTR      = mysql_fetch_array($rptResultTR);
$NbrCommandesTR = $DataTR[NbrCommandesTR];

//DR
$rptQueryDR="SELECT count(*) as NbrCommandesDR from ORDERS  WHERE user_id in  ('entrepotdr','safedr')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultDR = mysql_query($rptQueryDR)		or die  ('I cannot select items because 2: ' . mysql_error());
$DataDR      = mysql_fetch_array($rptResultDR);
$NbrCommandesDR = $DataDR[NbrCommandesDR];

//LAVAL
$rptQueryLV="SELECT count(*) as NbrCommandesLV from ORDERS  WHERE user_id in  ('laval','lavalsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultLV = mysql_query($rptQueryLV)		or die  ('I cannot select items because 3: ' . mysql_error());
$DataLV      = mysql_fetch_array($rptResultLV);
$NbrCommandesLV = $DataLV[NbrCommandesLV];

//SHERBROOKE
$rptQuerySH="SELECT count(*) as NbrCommandesSH from ORDERS  WHERE user_id in  ('sherbrooke','sherbrookesafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultSH = mysql_query($rptQuerySH)		or die  ('I cannot select items because 9: ' . mysql_error());
$DataSH      = mysql_fetch_array($rptResultSH);
$NbrCommandesSH = $DataSH[NbrCommandesSH];

//Lévis
$rptQueryLE="SELECT count(*) as NbrCommandesLE from ORDERS  WHERE user_id in  ('levis','levissafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultLE = mysql_query($rptQueryLE)		or die  ('I cannot select items because 4: ' . mysql_error());
$DataLE      = mysql_fetch_array($rptResultLE);
$NbrCommandesLE = $DataLE[NbrCommandesLE];

//Longueuil
$rptQueryLO="SELECT count(*) as NbrCommandesLO from ORDERS  WHERE user_id in  ('longueuil','longueuilsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultLO = mysql_query($rptQueryLO)		or die  ('I cannot select items because 5: ' . mysql_error());
$DataLO      = mysql_fetch_array($rptResultLO);
$NbrCommandesLO = $DataLO[NbrCommandesLO];

//Terrebonne
$rptQueryTB="SELECT count(*) as NbrCommandesTB from ORDERS  WHERE user_id in  ('terrebonne','terrebonnesafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultTB = mysql_query($rptQueryTB)		or die  ('I cannot select items because 6: ' . mysql_error());
$DataTB      = mysql_fetch_array($rptResultTB);
$NbrCommandesTB = $DataTB[NbrCommandesTB];

//Halifax
$rptQueryHA="SELECT count(*) as NbrCommandesHA from ORDERS  WHERE user_id in  ('warehousehal','warehousehalsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultHA = mysql_query($rptQueryHA)		or die  ('I cannot select items because 7: ' . mysql_error());
$DataHA      = mysql_fetch_array($rptResultHA);
$NbrCommandesHA = $DataHA[NbrCommandesHA];

//Chicoutimi
$rptQueryCH="SELECT count(*) as NbrCommandesCH from ORDERS  WHERE user_id in  ('chicoutimi','chicoutimisafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultCH = mysql_query($rptQueryCH)		or die  ('I cannot select items because 8: ' . mysql_error());
$DataCH      = mysql_fetch_array($rptResultCH);
$NbrCommandesCH = $DataCH[NbrCommandesCH];


//Granby
$rptQueryGR="SELECT count(*) as NbrCommandesGR from ORDERS  WHERE user_id in  ('granby','granbysafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultGR 	= mysql_query($rptQueryGR)		or die  ('I cannot select items because 8: ' . mysql_error());
$DataGR     	= mysql_fetch_array($rptResultGR);
$NbrCommandesGR = $DataGR[NbrCommandesGR];




//Québec
$rptQueryQC="SELECT count(*) as NbrCommandesQC from ORDERS  WHERE user_id in  ('entrepotquebec','quebecsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is  null;";
$rptResultQC 	 = mysql_query($rptQueryQC)		or die  ('I cannot select items because 8: ' . mysql_error());
$DataQC     	 = mysql_fetch_array($rptResultQC);
$NbrCommandesQC  = $DataQC[NbrCommandesQC];


$TotalJobVendues = $NbrCommandesCH  + $NbrCommandesHA  + $NbrCommandesTB + $NbrCommandesLO + $NbrCommandesLE + $NbrCommandesSH + $NbrCommandesLV +  $NbrCommandesDR + $NbrCommandesTR + $NbrCommandesGR + $NbrCommandesSMB+$NbrCommandesQC;

//REPRISES PAR SUCCURSALE
//TR
$rptQueryTR2="SELECT count(*) as NbrReprisesTR2 from ORDERS  WHERE user_id in   ('entrepotifc','entrepotsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultTR2 = mysql_query($rptQueryTR2)		or die  ('I cannot select items because 10: ' . mysql_error());
$DataTR2      = mysql_fetch_array($rptResultTR2);
$NbrReprisesTR2 = $DataTR2[NbrReprisesTR2];

//DR
$rptQueryDR2="SELECT count(*) as NbrReprisesDR2 from ORDERS  WHERE user_id in  ('entrepotdr','safedr')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultDR2 = mysql_query($rptQueryDR2)		or die  ('I cannot select items because 11: ' . mysql_error());
$DataDR2      = mysql_fetch_array($rptResultDR2);
$NbrReprisesDR2 = $DataDR2[NbrReprisesDR2];

//LAVAL
$rptQueryLV2="SELECT count(*) as NbrReprisesLV2 from ORDERS  WHERE user_id in  ('laval','lavalsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT  null;";
$rptResultLV2 = mysql_query($rptQueryLV2)		or die  ('I cannot select items because 12: ' . mysql_error());
$DataLV2      = mysql_fetch_array($rptResultLV2);
$NbrReprisesLV2 = $DataLV2[NbrReprisesLV2];

//SHERBROOKE
$rptQuerySH2="SELECT count(*) as NbrReprisesSH2 from ORDERS  WHERE user_id in  ('sherbrooke','sherbrookesafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultSH2 = mysql_query($rptQuerySH2)		or die  ('I cannot select items because 13: ' . mysql_error());
$DataSH2      = mysql_fetch_array($rptResultSH2);
$NbrReprisesSH2 = $DataSH2[NbrReprisesSH2];


//Lévis
$rptQueryLE2="SELECT count(*) as NbrReprisesLE2 from ORDERS  WHERE user_id in  ('levis','levissafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultLE2 = mysql_query($rptQueryLE2)		or die  ('I cannot select items because 14: ' . mysql_error());
$DataLE2      = mysql_fetch_array($rptResultLE2);
$NbrReprisesLE2 = $DataLE2[NbrReprisesLE2];


//Longueuil
$rptQueryLO2="SELECT count(*) as NbrReprisesLO2 from ORDERS  WHERE user_id in  ('longueuil','longueuilsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT  null;";
$rptResultLO2 = mysql_query($rptQueryLO2)		or die  ('I cannot select items because: ' . mysql_error());
$DataLO2      = mysql_fetch_array($rptResultLO2);
$NbrReprisesLO2 = $DataLO2[NbrReprisesLO2];

//Terrebonne
$rptQueryTB2="SELECT count(*) as NbrReprisesTB2 from ORDERS  WHERE user_id in  ('terrebonne','terrebonnesafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultTB2 = mysql_query($rptQueryTB2)		or die  ('I cannot select items because: ' . mysql_error());
$DataTB2      = mysql_fetch_array($rptResultTB2);
$NbrReprisesTB2 = $DataTB2[NbrReprisesTB2];


//Halifax
$rptQueryHA2="SELECT count(*) as NbrReprisesHA2 from ORDERS  WHERE user_id in  ('warehousehal','warehousehalsafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT  null;";
$rptResultHA2 = mysql_query($rptQueryHA2)		or die  ('I cannot select items because: ' . mysql_error());
$DataHA2      = mysql_fetch_array($rptResultHA2);
$NbrReprisesHA2 = $DataHA2[NbrReprisesHA2];

//Chicoutimi
$rptQueryCH2="SELECT count(*) as NbrReprisesCH2 from ORDERS  WHERE user_id in  ('chicoutimi','chicoutimisafe')
and order_date_processed between '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultCH2 = mysql_query($rptQueryCH2)		or die  ('I cannot select items because: ' . mysql_error());
$DataCH2      = mysql_fetch_array($rptResultCH2);
$NbrReprisesCH2 = $DataCH2[NbrReprisesCH2];

//Granby
$rptQueryGR2="SELECT count(*) as NbrReprisesGR2 from ORDERS  WHERE user_id in  ('granby','granbysafe')
and order_date_processed BETWEEN '$date1' and '$date2' 
and order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultGR2 	= mysql_query($rptQueryGR2)		or die  ('I cannot select items because 1: ' . mysql_error());
$DataGR2      	= mysql_fetch_array($rptResultGR2);
$NbrReprisesGR2 = $DataGR2[NbrReprisesGR2];


//Québec
$rptQueryQC2="SELECT count(*) as NbrReprisesQC2 from ORDERS  WHERE user_id in  ('entrepotquebec','quebecsafe')
AND order_date_processed BETWEEN '$date1' and '$date2' 
AND order_status not in ('cancelled','on hold')
AND redo_order_num is NOT null;";
$rptResultQC2 	 = mysql_query($rptQueryQC2)		or die  ('I cannot select items because 2: ' . mysql_error());
$DataQC2      	 = mysql_fetch_array($rptResultQC2);
$NbrReprisesQC2 = $DataQC2[NbrReprisesQC2];


$TotalReprises = $NbrReprisesCH2+$NbrReprisesHA2+$NbrReprisesTB2+$NbrReprisesLO2+$NbrReprisesLE2+$NbrReprisesSH2+$NbrReprisesLV2+$NbrReprisesDR2+$NbrReprisesTR2+$NbrReprisesGR2+$NbrReprisesSMB2+$NbrReprisesQC2;
$PourcentageRepriseMoyen = ($TotalReprises/$TotalJobVendues)*100;
$PourcentageRepriseMoyen = money_format('%.2n',$PourcentageRepriseMoyen);
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


		$PourcentageRepriseTR = ($NbrReprisesTR2/$NbrCommandesTR) *100;
		$PourcentageRepriseTR = money_format('%.2n',$PourcentageRepriseTR);
		
		$PourcentageRepriseDR = ($NbrReprisesDR2/$NbrCommandesDR) *100;
		$PourcentageRepriseDR = money_format('%.2n',$PourcentageRepriseDR);
		
		$PourcentageRepriseLV = ($NbrReprisesLV2/$NbrCommandesLV) *100;
		$PourcentageRepriseLV = money_format('%.2n',$PourcentageRepriseLV);
		
		$PourcentageRepriseSH = ($NbrReprisesSH2/$NbrCommandesSH) *100;
		$PourcentageRepriseSH = money_format('%.2n',$PourcentageRepriseSH);
		
		$PourcentageRepriseLE = ($NbrReprisesLE2/$NbrCommandesLE) *100;
		$PourcentageRepriseLE = money_format('%.2n',$PourcentageRepriseLE);
		
		$PourcentageRepriseLO = ($NbrReprisesLO2/$NbrCommandesLO) *100;
		$PourcentageRepriseLO = money_format('%.2n',$PourcentageRepriseLO);
		
		$PourcentageRepriseTB = ($NbrReprisesTB2/$NbrCommandesTB) *100;
		$PourcentageRepriseTB = money_format('%.2n',$PourcentageRepriseTB);
		
		$PourcentageRepriseHA = ($NbrReprisesHA2/$NbrCommandesHA) *100;
		$PourcentageRepriseHA = money_format('%.2n',$PourcentageRepriseHA);
		
		$PourcentageRepriseCH = ($NbrReprisesCH2/$NbrCommandesCH) *100;
		$PourcentageRepriseCH = money_format('%.2n',$PourcentageRepriseCH);
		
		$PourcentageRepriseGR = ($NbrReprisesGR2/$NbrCommandesGR) *100;
		$PourcentageRepriseGR = money_format('%.2n',$PourcentageRepriseGR);
		
		$PourcentageRepriseSMB = ($NbrReprisesSMB2/$NbrCommandesSMB) *100;
		$PourcentageRepriseSMB = money_format('%.2n',$PourcentageRepriseSMB);
	
		$PourcentageRepriseQC = ($NbrReprisesQC2/$NbrCommandesQC) *100;
		$PourcentageRepriseQC = money_format('%.2n',$PourcentageRepriseQC);
		
		$message.="<body><table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr>
					  <td colspan=\"4\" align=\"center\">Ventes effectués entre le $date1 et le $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Succursale</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrCommandesSH</td>
					  <td align=\"center\">$NbrReprisesSH2</td>
					  <td align=\"center\">$PourcentageRepriseSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrCommandesTR</td>
					  <td align=\"center\">$NbrReprisesTR2</td>
					  <td align=\"center\">$PourcentageRepriseTR%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrCommandesCH</td>
					  <td align=\"center\">$NbrReprisesCH2</td>
					  <td align=\"center\">$PourcentageRepriseCH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrCommandesTB</td>
					  <td align=\"center\">$NbrReprisesTB2</td>
					  <td align=\"center\">$PourcentageRepriseTB%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrCommandesLV</td>
					  <td align=\"center\">$NbrReprisesLV2</td>
					  <td align=\"center\">$PourcentageRepriseLV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrCommandesHA</td>
					  <td align=\"center\">$NbrReprisesHA2</td>
					  <td align=\"center\">$PourcentageRepriseHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrCommandesDR</td>
					  <td align=\"center\">$NbrReprisesDR2</td>
					  <td align=\"center\">$PourcentageRepriseDR%</td>
				   </tr>
				 
				     <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrCommandesLE </td>
					  <td align=\"center\">$NbrReprisesLE2</td>
					  <td align=\"center\">$PourcentageRepriseLE%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrCommandesLO</td>
					  <td align=\"center\">$NbrReprisesLO2</td>
					  <td align=\"center\">$PourcentageRepriseLO%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrCommandesGR</td>
					  <td align=\"center\">$NbrReprisesGR2</td>
					  <td align=\"center\">$PourcentageRepriseGR%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrCommandesSMB</td>
					  <td align=\"center\">$NbrReprisesSMB2</td>
					  <td align=\"center\">$PourcentageRepriseSMB%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrCommandesQC</td>
					  <td align=\"center\">$NbrReprisesQC2</td>
					  <td align=\"center\">$PourcentageRepriseQC%</td>
				   </tr>
				   
				   <tr>
				    <th>Totaux</th>
				   	<td  align=\"center\">$TotalJobVendues</td>
					<td  align=\"center\">$TotalReprises</td>
					<td  align=\"center\">$PourcentageRepriseMoyen%</td>
				   </tr>
				   
				   
				   
				   </table>";
				   















	
//Rapports  sur Le nombre de jobs fabriqués par Laboratoire
//Swiss
$QuerySwiss="SELECT count(*) as NbrSwiss from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultSwiss = mysql_query($QuerySwiss)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwiss   = mysql_fetch_array($resultSwiss);
$NbrFabriques_Swiss = $DataSwiss[NbrSwiss];	

//Central Lab
$QueryHKO="SELECT count(*) as NbrHKO from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultHKO = mysql_query($QueryHKO)		or die  ('I cannot select items because: ' . mysql_error());
$DataHKO   = mysql_fetch_array($resultHKO);
$NbrFabriques_HKO = $DataHKO[NbrHKO];	

//GKB
$QueryGKB="SELECT count(*) as NbrGKB from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultGKB  = mysql_query($QueryGKB)		or die  ('I cannot select items because: ' . mysql_error());
$DataGKB    = mysql_fetch_array($resultGKB);
$NbrFabriques_GKB = $DataGKB[NbrGKB];	

//DLAB
$QuerySTC="SELECT count(order_num) as NbrSTC from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultSTC = mysql_query($QuerySTC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSTC   = mysql_fetch_array($resultSTC);
$NbrFabriques_STC = $DataSTC[NbrSTC];	

//CSC
$QueryCSC="SELECT count(*) as NbrCSC from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultCSC = mysql_query($QueryCSC)		or die  ('I cannot select items because: ' . mysql_error());
$DataCSC   = mysql_fetch_array($resultCSC);
$NbrFabriques_CSC = $DataCSC[NbrCSC];	

//VISION EASE
$QueryVE="SELECT count(*) as NbrVE from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultVE = mysql_query($QueryVE)		or die  ('I cannot select items because: ' . mysql_error());
$DataVE   = mysql_fetch_array($resultVE);
$NbrFabriques_VE = $DataVE[NbrVE];	

//Quest
$QueryQuest="SELECT count(*) as NbrQuest from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND user_id NOT IN ('redoifc','St.Catharines','redosafety','testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NULL;";
$resultQuest = mysql_query($QueryQuest)		or die  ('I cannot select items because: ' . mysql_error());
$DataQuest   = mysql_fetch_array($resultQuest);
$NbrFabriques_Quest = $DataQuest[NbrQuest];	



//NOMBRE DE REPRISE PAR FOUNISEUR
//Swiss
$QuerySwiss2="SELECT count(*) as NbrSwiss2 from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND user_id not in ('testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwiss2 = mysql_query($QuerySwiss2)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwiss2   = mysql_fetch_array($resultSwiss2);
$NbrReprise_Swiss2 = $DataSwiss2[NbrSwiss2];	

//Central Lab
$QueryHKO="SELECT count(*) as NbrHKO from ORDERS  WHERE lab in (66,67,59)
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND user_id not in ('testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultHKO = mysql_query($QueryHKO)		or die  ('I cannot select items because: ' . mysql_error());
$DataHKO   = mysql_fetch_array($resultHKO);
$NbrReprise_HKO = $DataHKO[NbrHKO];	

//GKB
$QueryGKB="SELECT count(*) as NbrGKB from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND user_id not in ('testcompany3','normandsafe','eyeviewsafe','GARAGEMP','test22','BSG','garantieatoutcasser')
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultGKB  = mysql_query($QueryGKB)		or die  ('I cannot select items because: ' . mysql_error());
$DataGKB    = mysql_fetch_array($resultGKB);
$NbrReprise_GKB = $DataGKB[NbrGKB];	

//DLAB
$QuerySTC="SELECT count(order_num) as NbrSTC from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSTC = mysql_query($QuerySTC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSTC   = mysql_fetch_array($resultSTC);
$NbrReprise_STC = $DataSTC[NbrSTC];	

//CSC
$QueryCSC="SELECT count(*) as NbrCSC from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultCSC = mysql_query($QueryCSC)		or die  ('I cannot select items because: ' . mysql_error());
$DataCSC   = mysql_fetch_array($resultCSC);
$NbrReprise_CSC = $DataCSC[NbrCSC];	

//VISION EASE
$QueryVE="SELECT count(*) as NbrVE from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultVE = mysql_query($QueryVE)		or die  ('I cannot select items because: ' . mysql_error());
$DataVE   = mysql_fetch_array($resultVE);
$NbrReprise_VE = $DataVE[NbrVE];	

//Quest
$QueryQuest="SELECT count(*) as NbrQuest from ORDERS  WHERE lab in (66,67,59)
and order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
and order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultQuest = mysql_query($QueryQuest)		or die  ('I cannot select items because: ' . mysql_error());
$DataQuest   = mysql_fetch_array($resultQuest);
$NbrReprise_Quest = $DataQuest[NbrQuest];	

$PourcentageRepriseSwiss = ($NbrReprise_Swiss2/$NbrFabriques_Swiss)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseDlab  = ($NbrReprise_STC/$NbrFabriques_STC)*100;
$PourcentageRepriseDlab = money_format('%.2n',$PourcentageRepriseDlab);

$PourcentageRepriseHKO  = ($NbrReprise_HKO/$NbrFabriques_HKO)*100;
$PourcentageRepriseHKO = money_format('%.2n',$PourcentageRepriseHKO);

$PourcentageRepriseGKB  = ($NbrReprise_GKB/$NbrFabriques_GKB)*100;
$PourcentageRepriseGKB = money_format('%.2n',$PourcentageRepriseGKB);

$PourcentageRepriseCSC  = ($NbrReprise_CSC/$NbrFabriques_CSC)*100;
$PourcentageRepriseCSC = money_format('%.2n',$PourcentageRepriseCSC);

$PourcentageRepriseVE  = ($NbrReprise_VE/$NbrFabriques_VE)*100;
$PourcentageRepriseVE = money_format('%.2n',$PourcentageRepriseVE);

$PourcentageRepriseQuest  = ($NbrReprise_Quest/$NbrFabriques_Quest)*100;
$PourcentageRepriseQuest = money_format('%.2n',$PourcentageRepriseQuest);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes par fabriquant entre le $date1 et $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Fabriquant</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Swiss</td>
					  <td align=\"center\">$NbrFabriques_Swiss</td>
					  <td align=\"center\">$NbrReprise_Swiss2</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Dlab</td>
					  <td align=\"center\">$NbrFabriques_STC</td>
					  <td align=\"center\">$NbrReprise_STC</td>
					  <td align=\"center\">$PourcentageRepriseDlab%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Central Lab</td>
					  <td align=\"center\">$NbrFabriques_HKO</td>
					  <td align=\"center\">$NbrReprise_HKO</td>
					  <td align=\"center\">$PourcentageRepriseHKO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Essilor Lab #1</td>
					  <td align=\"center\">$NbrFabriques_GKB</td>
					  <td align=\"center\">$NbrReprise_GKB</td>
					  <td align=\"center\">$PourcentageRepriseGKB%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">CSC</td>
					  <td align=\"center\">$NbrFabriques_CSC</td>
					  <td align=\"center\">$NbrReprise_CSC</td>
					  <td align=\"center\">$PourcentageRepriseCSC%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Quest</td>
					  <td align=\"center\">$NbrFabriques_Quest</td>
					  <td align=\"center\">$NbrReprise_Quest</td>
					  <td align=\"center\">$PourcentageRepriseQuest%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Vision Ease</td>
					  <td align=\"center\">$NbrFabriques_VE</td>
					  <td align=\"center\">$NbrReprise_VE</td>
					  <td align=\"center\">$PourcentageRepriseVE%</td>
				   </tr>

				   </tr></table>";	
	
		
		
//Partie 3, vente par fabriquant par entrepot
	
//Rapports  sur Le nombre de jobs fabriqués par Laboratoire

//1-Swiss:  1.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//1-Swiss:  1.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//1-Swiss:  1.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//1-Swiss:  1.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//1-Swiss:  1.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//1-Swiss:  1.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//1-Swiss:  1.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//1-Swiss:  1.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//1-Swiss:  1.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//1-Swiss:  1.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//1-Swiss:  1.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//1-Swiss:  1.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//1-Swiss:  1.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//1-Swiss:  1.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//1-Swiss:  1.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//1-Swiss:  1.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//1-Swiss:  1.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//1-Swiss:  1.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];



//1-Swiss:  1.10:Granby: les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrReprise_Swiss_GR = $DataSwissGR[nbrSwissGR];
//1-Swiss:  1.10:Granby: sans les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrOriginales_Swiss_GR = $DataSwissGR[nbrSwissGR];






//1-Swiss:  1.12:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//1-Swiss:  1.12:QC: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];






//1-Swiss:  1.13:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 10
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Swiss</b> entre le $date1 et $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";	
				   
				   
				   
				   
				   
				   
				   
				   
 //2-Dlab:  2.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//2-lab:  2.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//2-Dlab:  2.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//2-Dlab:  2.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//2-Dlab: 2.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//2-Dlab:  2.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//2-Dlab:  2.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//2-Dlab:  2.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//2-Dlab:  2.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//2-Dlab:  2.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//2-Dlab:  2.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//2-Swiss:  2.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//2-Dlab:  2.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//2-Dlab:  2.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//2-Dlab:  2.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//2-Dlab:  2.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//2-Dlab:  2.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//2-Dlab:  2.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];


//2-Dlab:  2.9:Granby: les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrReprise_Swiss_GR = $DataSwissGR[nbrSwissGR];
//2-Dlab:  2.9:Granby: sans les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrOriginales_Swiss_GR = $DataSwissGR[nbrSwissGR];





//2-Dlab:  2.11:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//2-Dlab:  2.11:QC: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];




//2-Dlab:  2.12:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 3
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);


//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Dlab</b> entre le $date1 et $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


					 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				   
				   <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				      
				    <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";
				   
				   
				   
				   
			
			   
				   
 //3-Central Lab:  3.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//3-Central Lab:  3.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//3-Central Lab:  3.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//3-Central Lab:  3.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//3-Central Lab: 3.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//3-Central Lab:  3.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//3-Central Lab:  3.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//3-Central Lab:  3.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//3-Central Lab:  3.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//3-Central Lab:  3.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//3-Central Lab:  3.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//3-Central Lab:  3.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//3-Central Lab:  3.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//3-Central Lab:  3.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//3-Central Lab:  3.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//3-Central Lab:  3.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//3-Central Lab:  3.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//3-Central Lab:  3.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];


//3-Central Lab:  3.10:Granby: les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_GR = $DataSwissTR[nbrSwissGR];
//3-Central Lab:  3.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_GR = $DataSwissTR[nbrSwissGR];




//3-Central Lab:  3.12:QC: les reprises
$querySwissQC = "SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' AND '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//3-Central Lab:  3.9:Longueuil: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];



//3-Central Lab:  3.11:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 25
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Central Lab</b> entre le $date1 et $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				     <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";	
				   
				   
				   
			   
			
			   
				   
 //4-Essilor Lab #1:  4.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//4-Essilor Lab #1:  4.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//4-Essilor Lab #1:  4.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//4-Essilor Lab #1:  4.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//4-Essilor Lab #1:  4.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//4-Essilor Lab #1:  4.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//4-Essilor Lab #1:  4.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//4-Essilor Lab #1:  4.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//4-Essilor Lab #1:  4.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//4-Essilor Lab #1:  4.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//4-Essilor Lab #1:  4.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//4-Essilor Lab #1:  4.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//4-Essilor Lab #1:  4.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//4-Essilor Lab #1:  4.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//4-Essilor Lab #1:  4.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//4-Essilor Lab #1:  4.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//4-Essilor Lab #1:  4.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//4-Essilor Lab #1:  4.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];



//4-Essilor Lab #1:  4.9:Granby: les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrReprise_Swiss_GR = $DataSwissGR[nbrSwissGR];
//4-Essilor Lab #1:  4.9:Longueuil: sans les reprises
$querySwissGR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissGR = mysql_query($querySwissGR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissGR   = mysql_fetch_array($resultSwissGR);
$NbrOriginales_Swiss_GR = $DataSwissGR[nbrSwissGR];




//4-Essilor Lab #1:  4.11:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//4-Essilor Lab #1:  4.10:QC sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];


//4-Essilor Lab #1:  4.10:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 69
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Essilor Lab #1</b> entre le $date1 et $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
				   
				   
				    <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				   
				    <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";				   
				   
				   
	
				   
 //5-CSC:  5.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//5-CSC:  5.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//5-CSC:  5.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//5-CSC:  5.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//5-CSC:  5.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//5-CSC:  5.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//5-CSC:  5.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//5-CSC:  5.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//5-CSC:  5.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//5-CSC:  5.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//5-CSC:  5.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//5-CSC:  5.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//5-CSC:  5.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//5-CSC:  5.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//5-CSC:  5.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//5-CSC:  5.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//5-CSC:  5.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//5-CSC:  5.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];


//5-CSC:  5.10:Granby: les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_GR = $DataSwissTR[nbrSwissGR];
//5-CSC:  5.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_GR = $DataSwissTR[nbrSwissGR];





//5-CSC:  5.12:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//5-CSC:  5.11:Sainte-Marie: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];



//5-CSC:  5.10:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 60
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>CSC</b> entre le  $date1 et le $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>

				   
				   
				    <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";				   
				   	
	
	
	
					   
 //6- Vision Ease  6.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//6- Vision Ease  6.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//6- Vision Ease  6.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//6- Vision Ease  6.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//6- Vision Ease  6.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//6- Vision Ease  6.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//6- Vision Ease  6.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//6- Vision Ease  6.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//6- Vision Ease  6.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//6- Vision Ease  6.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//6- Vision Ease  6.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//6- Vision Ease  6.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//6- Vision Ease  6.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//6- Vision Ease  6.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//6- Vision Ease  6.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//6- Vision Ease  6.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//6- Vision Ease  6.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//6- Vision Ease  6.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];

//6- Vision Ease  6.10:Granby: les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_GR = $DataSwissTR[nbrSwissGR];
//6- Vision Ease  6.10:Granby: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_GR = $DataSwissTR[nbrSwissGR];





//6- Vision Ease  6.12:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//6- Vision Ease  6.11:Sainte-Marie: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];


//6- Vision Ease  6.10:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 54
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Vision Ease</b> entre le $date1 et le $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				   
  				 <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
	
					 <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";				   
				   	
	
			   		
				   
	
					   
 //7-Quest  7.1:Trois-rivieres: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TR = $DataSwissTR[nbrSwissTR];	
//7-Quest  7.1:Trois-rivieres: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTR from ORDERS  WHERE 
user_id IN ('entrepotifc','entrepotsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TR = $DataSwissTR[nbrSwissTR];


//7-Quest  7.2:Sherbrooke: les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_SH = $DataSwissTR[nbrSwissSH];
//7-Quest  7.2:Sherbrooke: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissSH from ORDERS  WHERE 
user_id IN ('sherbrooke','sherbrookesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_SH = $DataSwissTR[nbrSwissSH];

//7-Quest  7.3:Chicoutimi: les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_CH = $DataSwissTR[nbrSwissCH];
//7-Quest  7.3:Chicoutimi: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissCH from ORDERS  WHERE 
user_id IN ('chicoutimi','chicoutimisafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_CH = $DataSwissTR[nbrSwissCH];

//7-Quest  7.4:Terrebonne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_TER = $DataSwissTR[nbrSwissTER];
//7-Quest  7.4:Terrebonne: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissTER from ORDERS  WHERE 
user_id IN ('terrebonne','terrebonnesafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_TER = $DataSwissTR[nbrSwissTER];


//7-Quest  7.5:Laval: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LAV = $DataSwissTR[nbrSwissLAV];
//6- Vision Ease  6.5:Laval: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLAV from ORDERS  WHERE 
user_id IN ('laval','lavalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LAV = $DataSwissTR[nbrSwissLAV];


//7-Quest  7.6:Halifax: les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_HA = $DataSwissTR[nbrSwissHA];
//6- Vision Ease  6.6:Halifax: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissHA from ORDERS  WHERE 
user_id IN ('warehousehal','warehousehalsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_HA = $DataSwissTR[nbrSwissHA];


//7-Quest  7.7:Drummondville: les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_DR = $DataSwissTR[nbrSwissDR];
//7-Quest  7.7:Drummondville: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissDR from ORDERS  WHERE 
user_id IN ('entrepotdr','safedr')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_DR = $DataSwissTR[nbrSwissDR];


//7-Quest  7.8:Levis: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LE = $DataSwissTR[nbrSwissLE];
//7-Quest  7.8:Levis: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLE from ORDERS  WHERE 
user_id IN ('levis','levissafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LE = $DataSwissTR[nbrSwissLE];


//7-Quest  7.9:Longueuil: les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_LO = $DataSwissTR[nbrSwissLO];
//7-Quest  7.9:Longueuil: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissLO from ORDERS  WHERE 
user_id IN ('longueuil','longueuilsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_LO = $DataSwissTR[nbrSwissLO];


//7-Quest  7.9:Granby: les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_GR = $DataSwissTR[nbrSwissGR];
//7-Quest  7.9:Granby: sans les reprises
$querySwissTR="SELECT count(*) as nbrSwissGR from ORDERS  WHERE 
user_id IN ('granby','granbysafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrOriginales_Swiss_GR = $DataSwissTR[nbrSwissGR];




//7-Quest  7.11:QC: les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrReprise_Swiss_QC = $DataSwissQC[nbrSwissQC];
//7-Quest  7.9:QC: sans les reprises
$querySwissQC="SELECT count(*) as nbrSwissQC from ORDERS  WHERE 
user_id IN ('entrepotquebec','quebecsafe')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS  NULL;";
$resultSwissQC = mysql_query($querySwissQC)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissQC   = mysql_fetch_array($resultSwissQC);
$NbrOriginales_Swiss_QC = $DataSwissQC[nbrSwissQC];



//7-Quest  7.10:Redo Interne: les reprises
$querySwissTR="SELECT count(*) as nbrSwissRedoInterne from ORDERS  WHERE 
user_id IN ('redoifc','ST.Catharines','redosafety')
AND order_date_processed between '$date1' and '$date2' 
AND prescript_lab = 68
AND order_status NOT IN ('cancelled','on hold')
AND redo_order_num IS NOT NULL;";
$resultSwissTR = mysql_query($querySwissTR)		or die  ('I cannot select items because: ' . mysql_error());
$DataSwissTR   = mysql_fetch_array($resultSwissTR);
$NbrReprise_Swiss_RedoInterne = $DataSwissTR[nbrSwissRedoInterne];



$PourcentageRepriseSwiss = ($NbrReprise_Swiss_TR/$NbrOriginales_Swiss_TR)*100;
$PourcentageRepriseSwiss = money_format('%.2n',$PourcentageRepriseSwiss);

$PourcentageRepriseSwissSH =  ($NbrReprise_Swiss_SH/$NbrOriginales_Swiss_SH)*100;
$PourcentageRepriseSwissSH = money_format('%.2n',$PourcentageRepriseSwissSH);

$PourcentageRepriseSwissCH =  ($NbrReprise_Swiss_CH/$NbrOriginales_Swiss_CH)*100;
$PourcentageRepriseSwissCH = money_format('%.2n',$PourcentageRepriseSwissCH);

$PourcentageRepriseSwissTER =  ($NbrReprise_Swiss_TER/$NbrOriginales_Swiss_TER)*100;
$PourcentageRepriseSwissTER = money_format('%.2n',$PourcentageRepriseSwissTER);

$PourcentageRepriseSwissLAV =  ($NbrReprise_Swiss_LAV/$NbrOriginales_Swiss_LAV)*100;
$PourcentageRepriseSwissLAV = money_format('%.2n',$PourcentageRepriseSwissLAV);

$PourcentageRepriseSwissHA =  ($NbrReprise_Swiss_HA/$NbrOriginales_Swiss_HA)*100;
$PourcentageRepriseSwissHA = money_format('%.2n',$PourcentageRepriseSwissHA);

$PourcentageRepriseSwissDR =  ($NbrReprise_Swiss_DR/$NbrOriginales_Swiss_DR)*100;
$PourcentageRepriseSwissDR = money_format('%.2n',$PourcentageRepriseSwissDR);

$PourcentageRepriseSwissLE =  ($NbrReprise_Swiss_LE/$NbrOriginales_Swiss_LE)*100;
$PourcentageRepriseSwissLE = money_format('%.2n',$PourcentageRepriseSwissLE);

$PourcentageRepriseSwissLO =  ($NbrReprise_Swiss_LO/$NbrOriginales_Swiss_LO)*100;
$PourcentageRepriseSwissLO = money_format('%.2n',$PourcentageRepriseSwissLO);

$PourcentageRepriseSwissGR =  ($NbrReprise_Swiss_GR/$NbrOriginales_Swiss_GR)*100;
$PourcentageRepriseSwissGR = money_format('%.2n',$PourcentageRepriseSwissGR);

$PourcentageRepriseSwissSMB =  ($NbrReprise_Swiss_SMB/$NbrOriginales_Swiss_SMB)*100;
$PourcentageRepriseSwissSMB = money_format('%.2n',$PourcentageRepriseSwissSMB);

$PourcentageRepriseSwissQC =  ($NbrReprise_Swiss_QC/$NbrOriginales_Swiss_QC)*100;
$PourcentageRepriseSwissQC = money_format('%.2n',$PourcentageRepriseSwissQC);

//AFFICHER LES NOMBRE DE COMMANDES PAR FABRIQUANT
$message.="<br><br>
<table width=\"450\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">
<tr>
<td colspan=\"4\" align=\"center\">Ventes <b>Quest</b> entre le $date1 et le $date2 <strong>(Excluant les reprises)</strong></td>
				   </tr>
		
				   <tr bgcolor=\"CCCCCC\">
					  <th align=\"center\">Entrepot</th>
					  <th align=\"center\">Nombre de  vente </th>
					  <th align=\"center\">Nombre de reprises</th>
					  <th align=\"center\">% Reprises</th>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Trois-Rivières</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TR</td>
					  <td align=\"center\">$NbrReprise_Swiss_TR</td>
					  <td align=\"center\">$PourcentageRepriseSwiss%</td>
				   </tr>
				   

				     <tr>
					  <td align=\"center\">Sherbrooke</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SH</td>
					  <td align=\"center\">$NbrReprise_Swiss_SH</td>
					  <td align=\"center\">$PourcentageRepriseSwissSH%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Chicoutimi</td>
					  <td align=\"center\">$NbrOriginales_Swiss_CH</td>
					  <td align=\"center\">$NbrReprise_Swiss_CH</td>
					  <td align=\"center\">$PourcentageRepriseSwissCH%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Terrebonne</td>
					  <td align=\"center\">$NbrOriginales_Swiss_TER</td>
					  <td align=\"center\">$NbrReprise_Swiss_TER</td>
					  <td align=\"center\">$PourcentageRepriseSwissTER%</td>
				   </tr>
				   
				 
				   
				    <tr>
					  <td align=\"center\">Laval</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LAV</td>
					  <td align=\"center\">$NbrReprise_Swiss_LAV</td>
					  <td align=\"center\">$PourcentageRepriseSwissLAV%</td>
				   </tr>
				   
				   <tr>
					  <td align=\"center\">Halifax</td>
					  <td align=\"center\">$NbrOriginales_Swiss_HA</td>
					  <td align=\"center\">$NbrReprise_Swiss_HA</td>
					  <td align=\"center\">$PourcentageRepriseSwissHA%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Drummondville</td>
					  <td align=\"center\">$NbrOriginales_Swiss_DR</td>
					  <td align=\"center\">$NbrReprise_Swiss_DR</td>
					  <td align=\"center\">$PourcentageRepriseSwissDR%</td>
				   </tr>

				    <tr>
					  <td align=\"center\">Lévis</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LE</td>
					  <td align=\"center\">$NbrReprise_Swiss_LE</td>
					  <td align=\"center\">$PourcentageRepriseSwissLE%</td>
				   </tr>


				 <tr>
					  <td align=\"center\">Longueuil</td>
					  <td align=\"center\">$NbrOriginales_Swiss_LO</td>
					  <td align=\"center\">$NbrReprise_Swiss_LO</td>
					  <td align=\"center\">$PourcentageRepriseSwissLO%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Granby</td>
					  <td align=\"center\">$NbrOriginales_Swiss_GR</td>
					  <td align=\"center\">$NbrReprise_Swiss_GR</td>
					  <td align=\"center\">$PourcentageRepriseSwissGR%</td>
				   </tr>
				   
				     
				    <tr>
					  <td align=\"center\">Sainte-Marie</td>
					  <td align=\"center\">$NbrOriginales_Swiss_SMB</td>
					  <td align=\"center\">$NbrReprise_Swiss_SMB</td>
					  <td align=\"center\">$PourcentageRepriseSwissSMB%</td>
				   </tr>
				   
				      
				    <tr>
					  <td align=\"center\">Québec</td>
					  <td align=\"center\">$NbrOriginales_Swiss_QC</td>
					  <td align=\"center\">$NbrReprise_Swiss_QC</td>
					  <td align=\"center\">$PourcentageRepriseSwissQC%</td>
				   </tr>
				   
				    <tr>
					  <td align=\"center\">Redo Interne</td>
					  <td align=\"center\">&nbsp;</td>
					  <td align=\"center\">$NbrReprise_Swiss_RedoInterne</td>
					  <td align=\"center\">&nbsp;</td>
				   </tr>

				  
				   </table>";				   
				   	
	
						   	
//Fin partie 3		 
				   	
echo $message;
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport Annuel $date1 - $date2";
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




function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}


?>