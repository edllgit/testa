<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);	
$today      = date("Y-m-d");
$NbrJour    = 4;
$tomorrow   = mktime(0,0,0,date("m"),date("d")-$NbrJour,date("Y"));
$dateMoinsNbrJour = date("Y/m/d", $tomorrow);

$demain = mktime(0,0,0,date("m"),date("d")-45,date("Y"));
$IlyA45Jours = date("Y/m/d", $demain);
$demain = mktime(0,0,0,date("m"),date("d")-10,date("Y"));
$IlyA10Jours = date("Y/m/d", $demain);
$demain = mktime(0,0,0,date("m"),date("d")-20,date("Y"));
$IlyA20Jours = date("Y/m/d", $demain);
$demain = mktime(0,0,0,date("m"),date("d")-30,date("Y"));
$IlyA30Jours = date("Y/m/d", $demain);
		
$QueryAccount="SELECT accounts.*, labs.lab_name FROM accounts, labs WHERE accounts.main_lab = labs.primary_key AND approved='approved' AND last_connexion <> '0000-00-00 00:00:00' AND last_connexion < '$dateMoinsNbrJour'
AND  last_connexion >  '$IlyA30Jours' AND accounts.main_lab <> 26 order by company, last_connexion desc ";//Sortir les clients qui se sont déja connectés
//Et qui ne se sont pas connecté depuis X jours


echo $QueryAccount;
$ResultAccount=mysqli_query($con,$QueryAccount)		or die  ('I cannot select items because: ' . mysqli_error($con));

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
		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Company</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Last Connexion</td>  
			    <td align=\"center\">Days since last connexion.</td>

				</tr>";
			
		$main_lab = '';	
				
		while ($listItem=mysqli_fetch_array($ResultAccount,MYSQLI_ASSOC)){
		
		$LastConnexion = substr($listItem[last_connexion],0,10);
		echo '<br><br>Last connexion: ' . $LastConnexion;
		echo ' Today: ' . $today;
		
		
$diff = abs(strtotime($LastConnexion) - strtotime($today));
$years = floor($diff / (365*60*60*24));
$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
echo ' Jour de difference: ' . $days;
		
		
		if ($main_lab == ''){
		$main_lab = $listItem[main_lab];	
		}	
		
		
		if ($main_lab <> $listItem[main_lab]){//Si different, on a changé de main lab
		//AJouter saut de ligne dans le email pour espacer chaque main lab
		/*$message.="<tr>
                <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>  
			    <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
				<td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
				</tr><tr>
                <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>  
			    <td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
				<td align=\"center\">&nbsp;</td>
                <td align=\"center\">&nbsp;</td>
				</tr><tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Company</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Last Connexion</td>  
			    <td align=\"center\">Days since last connexion.</td>

				</tr>";*/
		$main_lab = $listItem[main_lab];		
		}

		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				
				if ($days > 5 ){
				$bgcolor="#FFFF00";
				}
				
				if ($days > 12 ){
				$bgcolor="#FF9933";
				}
				
				if ($days > 16 ){
				$bgcolor="#FF0000";
				}
				
			
				

//1- #FFFF00 jaune
//2- #FF9933 orange
//3- #FF0000 rouge			
				
				
				
				echo '<br>bg color: ' . $bgcolor;
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[company]</td>
                 <td align=\"center\">$listItem[lab_name]</td>
				 <td align=\"center\">"; 
			$message.= substr($listItem[last_connexion],0,10);
			$message.="</td>";
           
		   
		   
		    $message.="
                <td align=\"center\">$days</td>";
				
            $message.="</tr>";
		}//END WHILE	
		$message.="</table>";

		
		echo $message;
		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');



//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Warning! Last log-in 4 days or more: ".$curTime;
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
	
		// Générer le contenu HTML du rapport


		// Créez un nom de fichier unique avec un horodatage
		$date = new DateTime();
		$timestamp = $date->format('Y-m-d_H-i-s');

		$nomFichier = 'r_customer_flag_'. $timestamp;
	
		// Enregistrez le contenu HTML dans un fichier
		$cheminFichierHtml = 'C:/All_Rapports_EDLL/LABO/' . $nomFichier . '.html';
		file_put_contents($cheminFichierHtml, $message);

	
		echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
	

$time_end	= microtime(true);
$time 		= $time_end - $time_start;
$today 		= date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   		= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   		= $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips		= $ip  . ' ' .$ip2 ;
$CronQuery  = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Rapport Customer Flag (Ted) 2.0', '$time','$today','$timeplus3heures','rapport_customer_flag.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
	
?>