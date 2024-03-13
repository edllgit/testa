<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php  
if ($_REQUEST['delais'] == "") {
$delais = 6;
}else {
$delais = $_REQUEST['delais'];
}  
if ($_REQUEST['lab'] == "") {
echo 'No lab have been selected';
exit();
}else {
$lab = $_REQUEST['lab'];
$queryLab="SELECT * from labs 
 WHERE  primary_key = " . $lab;
$resultLab=mysqli_query($con,$queryLab) or die ("Could not find list");
$rowlab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
}

$StatusaExclure = "  O.order_status NOT IN('cancelled','filled','basket','shipped','in transit')";
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Rapport des commandes en retard <?php echo $rowlab['lab_name'] ?>   (<?php echo $delais ?>  jours)</title>
</head>

<body>
<h3 align="center">Liens: <a style="text-decoration:none;" href="index.php?lab=<?php echo $lab; ?>&delais=5">(5 jours)</a>&nbsp;&nbsp;<a  style="text-decoration:none;" href="index.php?lab=<?php echo $lab; ?>&delais=6">(6 jours)</a> &nbsp;&nbsp;<a style="text-decoration:none;" href="index.php?lab=<?php echo $lab; ?>&delais=7">(7 jours)</a>
&nbsp;&nbsp;<a style="text-decoration:none;" href="index.php?lab=<?php echo $lab; ?>&delais=10">(10 jours)</a></h3>

<br />
<h2 align="center"><?php echo $rowlab['lab_name'] ?> <?php echo $delais ?> jours de retard</h2><br />


<?php
// 1- VOT
$Prescriptlab      = 1;
$Nomlab 	 	   = 'VOT';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . " AND $StatusaExclure  AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
 //echo  $query;
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>










<?php
// 2- Saint-Catharines
$Prescriptlab      = 3;
$Nomlab 	 	   = 'Saint-Catharines';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>








<?php
// 3- Swiss
$Prescriptlab      = 10 ;
$Nomlab 	 	   = 'Swiss';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>






<?php
// 4- HKO
$Prescriptlab      = 25 ;
$Nomlab 	 	   = 'HKO';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result,MYSQLI_ASSOC);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array(); 
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>








<?php
// 5- Vision-ease
$Prescriptlab      = 54 ;
$Nomlab 	 	   = 'Vision Ease';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>







<?php
// 6- CSC
$Prescriptlab      = 60 ;
$Nomlab 	 	   = 'CSC';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>




<?php
// 7- Versano
$Prescriptlab      = 61 ;
$Nomlab 	 	   = 'Versano';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>




<?php
// 8- Acculab
$Prescriptlab      = 63 ;
$Nomlab 	 	   = 'Acculab';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>







<?php
// 9- Identity
$Prescriptlab      = 64 ;
$Nomlab 	 	   = 'Identity Optical';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>











<?php
// 10- POG
$Prescriptlab      = 55;
$Nomlab 	 	   = 'Precision (POG)';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>





<?php
// 11- Crystal
$Prescriptlab      = 57;
$Nomlab 	 	   = 'Crystal';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>












<?php
// 12- US OPTICAL
$Prescriptlab      = 58;
$Nomlab 	 	   = 'US OPTICAL';
$tomorrow    	   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$datecomplete 	   = date("Y/m/d", $tomorrow);
$compteur_jobs_VOT = 0;
$query="SELECT  O.*, A.account_num from orders O, accounts A 
 WHERE  O.prescript_lab = $Prescriptlab  AND O.user_id = A.user_id  AND A.main_lab = ". $lab . "   AND $StatusaExclure AND O.order_date_processed < '$datecomplete'  AND O.order_date_processed > '2014-01-01' ORDER BY O.order_date_processed";
$result	  		   = mysqli_query($con,$query) or die ("Could not find list");
$nbrResult 		   = mysqli_num_rows($result);
if ($nbrResult > 0) {
?>
<table width="1150" border="1" align="center">
<tr>
    <th align="center" width="60">Order Date</th>
	<th align="center" width="60">Est Ship. Date</th>
	<th width="60">Order Number</th>
	<th width="80">Patient </th>
  	<th width="100">Ref Patient</th>
    <th width="50">Tray Num</th>
	<th width="370">Product</th>
	<th width="90">Status</th>
	<th width="90">Since</th>
	<th width="90">Manufacturer</th>
</tr>
<?php
$listCommandes = array();
while ($row=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	$queryLastUpdate    = "SELECT max(update_time) as update_time FROM status_history WHERE  order_num =".  $row['order_num'];
	$resultLastUpdate   = mysqli_query($con,$queryLastUpdate) or die ("Could not find list");
	$rowLastUpdate      = mysqli_fetch_array($resultLastUpdate,MYSQLI_ASSOC);
	$queryEst           = "SELECT *  FROM est_ship_date WHERE  order_num =".  $row['order_num'];
	$resultEst		    = mysqli_query($con,$queryEst) or die ("Could not find list");
	$rowEst			    = mysqli_fetch_array($resultEst,MYSQLI_ASSOC);
	$compteur_jobs_VOT +=1;
	$today 			    = date("Y-m-d");
	
	echo '<tr><td align="center">'. $row['order_date_processed'].'</td>'; 
	if ($today  > $rowEst['est_ship_date']  )
	{
		echo ' <td bgcolor="#CC6633" align="center">' .  $rowEst['est_ship_date']	 .  '</td>  '; 
	}else{
		echo ' <td align="center">'					  .  $rowEst['est_ship_date']	 .  '</td>  ';
	}
	  
	  echo '<td align="center">' . $row['order_num']		   . '</td>';
	  echo '<td align="center">' . $row['order_patient_first'] . ' '	. $row['order_patient_last']. '</td>';
	  echo '<td align="center">' . $row['patient_ref_num']	   . '</td>';
	  echo '<td align="center">' . $row['tray_num']			   . '</td>';
	  echo '<td align="center">' . $row['order_product_name']  . '</td>';
	  
	switch($row['order_status']){
			case "processing":			$order_status = "Confirmed";			break;
			case "confirmed":			$order_status = "Confirmed";			break;
			case "order imported":		$order_status = "Order Imported";		break;
			case "job started":			$order_status = "Surfacing";			break;
			case "in coating":			$order_status = "In Coating";			break;
			case "in mounting":			$order_status = "In Mounting";			break;
			case "in edging":			$order_status = "In Edging";			break;
			case "order completed":		$order_status = "Order Completed";		break;
			case "delay issue 0":		$order_status = "Delay Issue 0";		break;	
			case "delay issue 1":		$order_status = "Delay Issue 1";		break;
			case "delay issue 2":		$order_status = "Delay Issue 2";		break;
			case "delay issue 3":		$order_status = "Delay Issue 3";		break;
			case "delay issue 4":		$order_status = "Delay Issue 4";		break;
			case "delay issue 5":		$order_status = "Delay Issue 5";		break;
			case "delay issue 6":		$order_status = "Delay Issue 6";		break;
			case "verifying":			$order_status = "Verifying";			break;
			case "waiting for frame":	$order_status = "Waiting for Frame";	break;
			case "waiting for shape":	$order_status = "Waiting for Shape";	break;
			case "re-do":				$order_status = "Redo";					break;
			case "in transit":			$order_status = "In Transit";			break;
			case "filled":				$order_status = "Shipped";				break;
			case "cancelLed":			$order_status = "CancelLed";			break;
			case "information in hand":	$order_status = "Info in Hand";			break;
			case "on hold":				$order_status = "On Hold";				break;
			case "waiting for lens":	$order_status = "Waiting for lens";		break;
			case "interlab":			$order_status = "Interlab P";			break;
	}
	  
	if ($order_status == "Confirmed"){
		echo '<td bgcolor="#CCFF33" align="center">';
	}else{
		echo '<td  align="center">';
	}
	
	echo $order_status . '</td><td align="center">' .  $rowLastUpdate['update_time'] . '</td>';
	echo '<td align="center">';
	$queryLab  = "SELECT lab_name FROM labs WHERE primary_key =". $row['prescript_lab'];
	$resultLab = mysqli_query($con,$queryLab) or die ("Could not find list". mysqli_error($con));
	$DataLab   = mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
	echo  $DataLab[lab_name];
	echo '</td>';
	}
	echo '<h3 align="center">Number of orders for '. $Nomlab . ' :'. $compteur_jobs_VOT. '</h3>';
	?>
	</table>
	<br /><br>
<?php  }?>





</body>
</html>