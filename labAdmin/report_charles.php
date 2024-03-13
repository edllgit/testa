<?php  
include("../Connections/sec_connect.inc.php");
include("export_functions.inc.php");

//$user_id = $_REQUEST[user_id]; //'drdenbak';


echo '<table border="1 px solid black;">
<tr>
<td>Acomba account num</td>
<td>Order num</td>
<td>Patient reference</td>

</tr>
<tr>';



$rptQuery="SELECT accounts.acomba_account_num, orders.order_num, order_from, lab, account_rebate,order_patient_first, order_patient_last  FROM accounts, orders WHERE  acomba_account_num <> '' AND orders.user_id = accounts.user_id AND order_date_shipped > '2013-01-31' and order_date_shipped < '2013-04-12' AND order_Status='filled' group by order_num order by order_num";
$ResultQuery=mysql_query($rptQuery)	or die  ('I cannot select items because: ' . mysql_error());
echo '<br>'. $rptQuery;
$count = 0;
while ($DataQuery=mysql_fetch_array($ResultQuery)){
$count= $count +1; 

switch($DataQuery['order_from']){
		case "ifcclub":		$Prefix_Facture = "1"; break;//IFC France
		case "ifcclubca":	$Prefix_Facture = "2"; break;//IFC.ca
		case "ifcclubus":	$Prefix_Facture = "3"; break;//IFC.us
		case "directlens":	$Prefix_Facture = "4"; break;//Direct-Lens
		case "lensnetclub":	$Prefix_Facture = "5"; break;//Lensnet Club
		case "aitlensclub":	$Prefix_Facture = "6"; break;//AIT lens club
		default:    		$Prefix_Facture = "0"; break;//Source de la commande inconnue
}


switch($DataQuery['account_rebate']){
		case "0":	$TermePaiement = 1; break;//Account rebate 0%
		case "5":	$TermePaiement = 5; break;//Account rebate 5%
		case "15":	$TermePaiement = 15; break;//Account rebate 15%
		case "20":	$TermePaiement = 20; break;//Account rebate 20% 
		case "25":	$TermePaiement = 25; break;//Account rebate 25% 
		case "30":	$TermePaiement = 30; break;//Account rebate 30%
		case "35":	$TermePaiement = 35; break;//Account rebate 35% 
		case "45":	$TermePaiement = 45; break;//Account rebate 35% 
		case "50":	$TermePaiement = 50; break;//Account rebate 50% 
		default:   	$TermePaiement = 00; break;//Account rebate non mappé%
}


switch($DataQuery['lab']){
	case "1":	    $NumeroLab = "01"; 	break;
	case "2":	    $NumeroLab = "02"; 	break;
	case "3":	    $NumeroLab = "03"; 	break;
	case "4":	    $NumeroLab = "04"; 	break;
	case "5":	    $NumeroLab = "05"; 	break;
	case "6":	    $NumeroLab = "06"; 	break;
	case "7":	    $NumeroLab = "07"; 	break;
	case "8":	    $NumeroLab = "08"; 	break;
	case "9":	    $NumeroLab = "09"; 	break;
	case "10":	    $NumeroLab = "10"; 	break;
	default:	    $NumeroLab = $DataQuery['lab']; 	break;
}

$FullOrderNum =  $Prefix_Facture . $NumeroLab . '-'. $DataQuery['order_num']; 

$PatientFirstName = strtoupper($DataQuery['order_patient_first']);
$PatientLastName  = strtoupper($DataQuery['order_patient_last']); 


$PatientContientChiffres    = false;

if (preg_match('#[0-9]#',$PatientFirstName)){
$PatientContientChiffres    = true;
}

if (preg_match('#[0-9]#',$PatientLastName)){
$PatientContientChiffres    = true;
}

if ($TermePaiement < 6)
$TermePaiement =  '&nbsp;'  . $TermePaiement  ;

//Si le nom ou prenom patient contient un chiffre, on utilise le nom de famille comme référence (GRM)
if ($PatientContientChiffres)
{
	$PatientReference =  $TermePaiement . substr($PatientLastName,0,8);
}else{
	if(($PatientFirstName == '') && ($PatientLastName == '')){
	//Nom et prenom sont vide, on ne met pas de point, uniquement le account rebate.
	$PatientReference = $TermePaiement ;
	}else{
	//Nom ne contient pas de chiffre, on prend premiere lettre prenom + un point + 7 lettre nom famille
	$PatientReference = $TermePaiement . substr($PatientFirstName,0,1) . '.' .substr($PatientLastName,0,6) ;
	}
}



	echo '<tr><td>' . $DataQuery['acomba_account_num']. '</td>';
	echo '<td>'		. $FullOrderNum	    . '</td>' ;
	echo '<td>'	    . $PatientReference . '</td>' ;
//	echo '<td>' 	. $DataQuery['account_rebate']. '</td>';
//	echo '<td>' 	. $TermePaiement. '</td>';
	echo '</tr>';
	
	
}
echo 'count:'. $count;
echo '</table>';

?>