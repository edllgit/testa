<style type="text/css">
<!--
.TextSize {
	font-size: 11pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
<div class="TextSize">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');         
$time_start = microtime(true);
$ftp_server = constant("FTP_WINDOWS_VM");
$ftp_user 	= constant("FTP_USER_SCT");

$ftp_pass 	=	constant("FTP_PASSWORD_SCT");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 
// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}
ftp_pasv($conn_id,true);
ftp_chdir($conn_id,"FROM DIRECT_LAB");
$directory=ftp_pwd($conn_id);
echo "<br>".$directory."<br>";
$contents=ftp_nlist($conn_id, ".");
$max=0;
$newest_file="";
foreach ($contents as $value) {//FIND NEWEST FILE
	
	$time=ftp_mdtm($conn_id,$value);
		if ((strpos($value,"DLab")!==false)&&(strpos($value,".csv")!==false)){
		if ($time>$max){
			$max=$time;
			$newest_file=$value;
		}
	}
}
echo "<br>Plus Récent:".$newest_file;
$local_file='tempdlabDlensSaintCatharines.csv';
//tempdlabDlensSaintCatharines
$server_file =$newest_file;
// try to download $server_file and save to $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo "<br>Successfully written $server_file to $local_file\n<br>";
} else {
    echo "There was a problem\n<br>";
}

// close the connection
$orderArray=array();
ftp_close($conn_id);  

$row = 1;
$handle = fopen("tempdlabDlensSaintCatharines.csv", "r");
$count=0;
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
{
	
	//COLLECT DATA INTO ARRAY FIRST
	$count++;
	$dlab_order_num			 = mysqli_real_escape_string($con,$orderArray[$count][1]=$data[0]);//No de facture DLAB
	$order_num 				 = $orderArray[$count][2]=$data[1];//No Facture DLENS (vide quand on importe des commande de DLAB)
	$order_num = str_replace('R','',$order_num);
	$user_id 				 = mysqli_real_escape_string($con,$orderArray[$count][3]=$data[2]);//User_id du client ou Nom du lab si c'est le lab qui sera facturé
	$order_date_processed	 = mysqli_real_escape_string($con,$orderArray[$count][4]=$data[3]);//Date d'entré de la commande
	$order_date_shipped		 = mysqli_real_escape_string($con,$orderArray[$count][5]=$data[4]);//date expédié(vide quand on recoit la commande de DLAB)
	$order_date_expected 	 = mysqli_real_escape_string($con,$orderArray[$count][6]=$data[5]);//date due 
	$station_id 			 = $orderArray[$count][34]=$data[33];//Station Scan ID
	echo '<br>Passe  order num:' .$order_num  ;
if (($order_num  <> '') && ($order_num <> 'Both') && (is_numeric($order_num)))
{
   $QueryPrescriptLab  = "SELECT prescript_lab FROM orders  WHERE order_num =  " . $order_num ; 
   echo '<br>'. $QueryPrescriptLab;
   $ResultPrescriptLab = mysqli_query($con,$QueryPrescriptLab)			or die ( "Query 1 failed: " . $QueryPrescriptLab . '<br>' . mysqli_error($con)  );
   $NbrPrescriptLab    = mysqli_num_rows($ResultPrescriptLab);
   echo '<br>$NbrPrescriptLab:'.$NbrPrescriptLab;
   if ($NbrPrescriptLab > 0){
	   $DataPrescriptLab = mysqli_fetch_array($ResultPrescriptLab,MYSQLI_ASSOC);
	   $PrescriptLab = $DataPrescriptLab[prescript_lab];
   }
}



			switch ($station_id){
				
				case "HDJ": $file_order_status = "on hold";	 	      	$faireLaMISEAJOUR = 'oui';	break;
				case "BRK": $file_order_status = "re-do";		      	$faireLaMISEAJOUR = 'oui';	break;
				case "SF":	$file_order_status = "waiting for lens";  	$faireLaMISEAJOUR = 'oui';	break;
				case "FTC":	$file_order_status = "waiting for frame"; 	$faireLaMISEAJOUR = 'oui';	break;
				case "TRAN": $file_order_status = "in transit";  	  	$faireLaMISEAJOUR = 'oui';  break;
				case "VF":	$file_order_status = "verifying";	 	  	$faireLaMISEAJOUR = 'oui';	break;
				case "ATE":	$file_order_status  = "in edging";	 	  	$faireLaMISEAJOUR = 'oui';	break;
				case "MOUNT":$file_order_status = "in mounting"; 	  	$faireLaMISEAJOUR = 'oui';	break;
				case "PST":	$file_order_status = "filled";		 	  	$faireLaMISEAJOUR = 'oui';	break;
				case "CAN":	$file_order_status = "in coating";	      	$faireLaMISEAJOUR = 'oui';	break;
				case "PPL":	$file_order_status = "in coating";	        $faireLaMISEAJOUR = 'oui';	break;				
				case "HC":	$file_order_status = "in coating";	        $faireLaMISEAJOUR = 'non';	break;
				case "COA": $file_order_status = "interlab";		    $faireLaMISEAJOUR = 'non';	break;
				case "DEL": $file_order_status = "cancelled";		    $faireLaMISEAJOUR = 'non';	break;				
				case "OE":	$file_order_status = "order imported";	    $faireLaMISEAJOUR = 'non';	break;
				case "RDR":	$file_order_status = "order imported";	    $faireLaMISEAJOUR = 'non';	break;
				case "RVOT":$file_order_status = "order imported";	    $faireLaMISEAJOUR = 'non';	break;
				case "RVC": $file_order_status = "re-do";			    $faireLaMISEAJOUR = 'non';	break;//Redo Vot Coating
				case "RPOG":$file_order_status = "job started";		    $faireLaMISEAJOUR = 'non';	break;
				case "WFS": $file_order_status  = "waiting for shape";  $faireLaMISEAJOUR = 'non';	break;
				case "INT": $file_order_status = "interlab qc";   		$faireLaMISEAJOUR = 'oui';	break;
				case "SUR":	$file_order_status = "job started";		    $faireLaMISEAJOUR = 'non';	break;
				default:    $file_order_status = "no update";		    $faireLaMISEAJOUR = 'non';  break;
			}
			$order_status = $file_order_status;
		
		
		//En premier on gere les job shippés, ensuite on fera les autres mises a jour
		
		//Si le status est a 'shipped'
			if (($file_order_status== "filled") && (is_numeric($order_num)) && ($order_num != '-1') && ($order_num != 'Both') )
			{
			//Protection pour empecher une commande déja shippé de changer de ship_date
			$DejaShipQuery="SELECT prescript_lab, order_status  FROM orders  WHERE order_num=  " . $order_num ; 
			$ResultDejaShip=mysqli_query($con,$DejaShipQuery)			or die ( "Query 2 failed: " . mysqli_error($con)  );
			$NbrResultat=mysqli_num_rows($ResultDejaShip);
						if ($NbrResultat > 0)
						{
						
						$DejaShipData=mysqli_fetch_array($ResultDejaShip,MYSQLI_ASSOC);
						$StatusActuel  = $DejaShipData['order_status'];
						$Prescript_Lab = $DejaShipData['prescript_lab'];
									if (($StatusActuel <> 'filled') && ($StatusActuel <> 'cancelled'))//Si le status actuel est différent de filled (shipped) et de cancelled on met a jour
									{
									echo '<br>ON SHIP la commande ' . $order_num;
									$todayDate = date("Y-m-d");// current date
									
									$queryMettreShipped = "UPDATE ORDERS SET order_status='filled', tray_num = '', order_date_shipped = '" . $todayDate . '\' WHERE order_num =' . $order_num . '';
									echo '  '. $queryMettreShipped . '<br>';
									$resultMettreaJour=mysqli_query($con,$queryMettreShipped)	or die ( "Query 3 failed: " . mysqli_error($con));
									
									//insertion dans status_history
									$todayDate = date("Y-m-d g:i a");// current date
									$currentTime = time($todayDate); //Change date into time
									$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
									$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
									echo '<br>'. $queryStatusHistory . '<br>';
										$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('Shipped','$order_num', '$datecomplete','update script Directlab STC 2.0')";
										echo '<br>'. $queryStatusHistory;
										$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
									}else{
										echo '<br>Commande ' .  $order_num  . ' deja shipped le ' .$order_date_shipped;
									}//End If  status actuel dans la bd <> shipped
						}//End if nbrResult > 0
			}//End If status == filles and order num <> '' OR -1
		
		//RENDU LA	
		
		if ((is_numeric($order_num)) && ($order_num <> '') && ($order_num != '-1') && ($order_num != 'Both') ){
		
			//On a un order_num directlens on doit mettre le status à jour dans la table orders
			echo '<br>Numero de commande Direct-lens:' . $order_num  . ' <br>';
			
			$QueryVerifSiShipped= "SELECT order_status FROM ORDERS WHERE ORDER_NUM = '$order_num'";
			$StatusVerifsiShip=mysqli_query($con,$QueryVerifSiShipped)			or die ( "Query 4 failed: " . mysqli_error($con) );
			$listItemShip=mysqli_fetch_array($StatusVerifsiShip,MYSQLI_ASSOC);
			
			
			$queryStatus = "SELECT COUNT(status_history_id) AS NBR FROM STATUS_HISTORY WHERE order_num = '$order_num'  and order_status = '". $file_order_status. "'";
			$resultStatus = mysqli_query($con,$queryStatus)		or die ('<br><br>Could not bleh  because 5: ' . mysqli_error($con));
			$DataStatus   = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
			$ExisteDeja   = $DataStatus['NBR'];
			
				
					   if (($listItemShip['order_status'] <> 'filled')  && ($listItemShip['order_status'] <> 'cancelled') && ($file_order_status <> 'no update') && ($ExisteDeja == 0)){
					   echo '<br>LE STATUS de la commande <b>avant</b> maj :' . $listItemShip['order_status'];
					   echo '<br>LE STATUS qu on veut inserer :' . $file_order_status;
					   //On vérifie si cette commande existe déja dans status_history
						
							
	
													if (($file_order_status <> 'verif') && ($PrescriptLab == 3))
													{
														$updateQuery = "UPDATE orders 	SET order_status = '" . $file_order_status . "' 	WHERE order_num = " . $order_num;
														echo '<br><br>'. $updateQuery . '<br>';
														$statusResult=mysqli_query($con,$updateQuery)			or die ( "Query failed 6: " . mysqli_error($con));
														echo '<br>Commande ' . $order_num . ' <b>Mise à Jour</b>: ' .  $file_order_status;
																						
														$todayDate = date("Y-m-d g:i a");// current date
														$currentTime = time($todayDate); //Change date into time
														$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
														$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
														$queryStatusHistory  = "INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$order_num', '$datecomplete','update script Directlab STC 2.0')";
														$resultStatusHistory = mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
													}//End If status <> verif
													
													
													if (($faireLaMISEAJOUR == 'oui') && ($PrescriptLab <>  3))
													{
														$updateQuery = "UPDATE orders 	SET order_status = '" . $file_order_status . "' 	WHERE order_num = " . $order_num;
														echo '<br><br>'. $updateQuery . '<br>';
														$statusResult=mysqli_query($con,$updateQuery)			or die ( "Query failed 6: " . mysqli_error($con));
														echo '<br>Commande ' . $order_num . ' <b>Mise à Jour</b>: ' .  $file_order_status;
																						
														$todayDate 	 = date("Y-m-d g:i a");// current date
														$currentTime = time($todayDate); //Change date into time
														$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
														$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
														$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$order_num', '$datecomplete','update script Directlab STC 2.0')";
														echo '<br>'. $queryStatusHistory . '<br>';
														$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
													}
													
													
																	
						}else {//End if status <> filled OR no update
							echo '<br>aucune maj, ' . $listItemShip['order_status'] . ' ' . $file_order_status . ' ' .$ExisteDeja  ;
						}
		}//End if Order num is Numeric 
			
		

}//End While qui collecte les données du CSV
fclose($handle);

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time 	  		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
			VALUES('Script MAJ status Saint-Catharines 2.0', '$time','$today','$heure_execution','script_maj_status_STC.php')"; 
echo '<br><br>'. 	$CronQuery;		
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));			
?>
</div>