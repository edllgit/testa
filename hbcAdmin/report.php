<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include("admin_functions.inc.php");
include "../connexion_hbc.inc.php";
//Le fichier getlang est partagé avec le labAdmin..Ne pas modifier!
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION["labAdminData"]["primary_key"]==""){
	var_dump($_SESSION["labAdminData"]);
	echo "You are not logged in. Click <a href='/hbcAdmin'>here</a> to login.";
	exit();
}
if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

$queryAccess = "SELECT * FROM access WHERE id=" . $_SESSION["accessid"];
$resultAccess= mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
$AccessData  = mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);

$FiltreEntrepot = " AND 3=3 ";
if($AccessData[id]== 1){//Griffé Lunetier Trois-Rivieres
	$FiltreEntrepot = " AND orders.user_id  IN ('88666')";
}elseif($AccessData[id]== 2){//HBC All access admin: D.Bouffard
	$FiltreEntrepot = " AND orders.user_id  IN 
	('88409','88405','88449','88403','88408','88411','88414','88440','88442','88431','88432','88434','88435','88441','88429','88416','88438','88439','88444','88430','88433','88666','redo_hbc','garantieatoutcasser')";
}elseif($AccessData[id]== 6){//HBC #88409
	$FiltreEntrepot = " AND orders.user_id  IN ('88409')";
}elseif($AccessData[id]== 7){//HBC #88405
	$FiltreEntrepot = " AND orders.user_id  IN ('88405')";
}elseif($AccessData[id]== 8){//HBC #88449
	$FiltreEntrepot = " AND orders.user_id  IN ('88449')";
}elseif($AccessData[id]== 9){//HBC #88403
	$FiltreEntrepot = " AND orders.user_id  IN ('88403')";
}elseif($AccessData[id]== 10){//HBC #88408
	$FiltreEntrepot = " AND orders.user_id  IN ('88408')";
}elseif($AccessData[id]== 11){//HBC #88411
	$FiltreEntrepot = " AND orders.user_id  IN ('88411')";
}elseif($AccessData[id]== 12){//HBC #88414
	$FiltreEntrepot = " AND orders.user_id  IN ('88414')";
}elseif($AccessData[id]== 13){//HBC #88440
	$FiltreEntrepot = " AND orders.user_id  IN ('88440')";
}elseif($AccessData[id]== 14){//HBC #88442
	$FiltreEntrepot = " AND orders.user_id  IN ('88442')";
}elseif($AccessData[id]== 15){//HBC #88431
	$FiltreEntrepot = " AND orders.user_id  IN ('88431')";
}elseif($AccessData[id]== 16){//HBC #88432
	$FiltreEntrepot = " AND orders.user_id  IN ('88432')";
}elseif($AccessData[id]== 17){//HBC #88434
	$FiltreEntrepot = " AND orders.user_id  IN ('88434')";
}elseif($AccessData[id]== 18){//HBC #88435
	$FiltreEntrepot = " AND orders.user_id  IN ('88435')";
}elseif($AccessData[id]== 19){//HBC #88441
	$FiltreEntrepot = " AND orders.user_id  IN ('88441')";
}elseif($AccessData[id]== 20){//HBC #88429
	$FiltreEntrepot = " AND orders.user_id  IN ('88429')";
}elseif($AccessData[id]== 21){//HBC #88416
	$FiltreEntrepot = " AND orders.user_id  IN ('88416')";
}elseif($AccessData[id]== 22){//HBC #88438
	$FiltreEntrepot = " AND orders.user_id  IN ('88438')";
}elseif($AccessData[id]== 23){//HBC #88439
	$FiltreEntrepot = " AND orders.user_id  IN ('88439')";
}elseif($AccessData[id]== 24){//HBC #88444
	$FiltreEntrepot = " AND orders.user_id  IN ('88444')";
}elseif($AccessData[id]== 25){//HBC #88430
	$FiltreEntrepot = " AND orders.user_id  IN ('88430')";
}elseif($AccessData[id]== 26){//HBC #88433
	$FiltreEntrepot = " AND orders.user_id  IN ('88433')";
}elseif($AccessData[id]== 54){//Swisscoat
$FiltreEntrepot = " AND orders.user_id  IN 
	('88409','88405','88449','88403','88408','88411','88414','88440','88442','88431','88432','88434','88435','88441','88429','88416','88438','88439','88444','88430','88433','redo_hbc')";
}


$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST["updateStatus"]=="fill order(s)")
	update_order_status();

if($_POST["rpt_search"]=="search orders"){
			
	$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.tray_num, accounts.company, orders.order_status,orders.redo_order_num, orders.order_shipping_cost, est_ship_date.est_ship_date from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)

	WHERE orders.lab='$lab_pkey' $FiltreEntrepot AND orders.order_num != '0'";

	switch($_POST["order_status"]){
		case "processing":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Confirmed";
		break;
		case "confirmed":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Confirmed";
		break;
		case "order imported":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Order Imported";
		break;
		case "job started":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Surfacing";
		break;
		case "in coating":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Coating";
		break;
		case "profilo":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Profilo";
		break;
		case "in mounting":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Mounting";
		break;
		case "in edging":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Edging";
		break;
		case "out for clip":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Out for Clip";
		break;
		case "order completed":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Order Completed";
		break;
		case "delay issue 0":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 0";
		break;
		case "delay issue 1":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 1";
		break;
		case "delay issue 2":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 2";
		break;
		case "delay issue 3":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 3";
		break;
		case "delay issue 4":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 4";
		break;
		case "delay issue 5":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 5";
		break;
		case "delay issue 6":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 6";
		break;
		case "waiting for frame":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Frame";
		break;
			
		case "waiting for frame knr":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Frame KNR";
		break;
		case "waiting for shape":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Shape";
		break;
		case "re-do":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Redo";
		break;
		case "in transit":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Transit";
		break;
		
		case "central lab marking":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Central Lab Marking";
		break;
		
		case "interlab":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Interlab P";
		break;
		
		case "interlab vot":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Interlab P";
		break;
		
		case "interlab qc":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Interlab QC";
		break;
		
		case "filled":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Shipped";
		break;
		case "cancelled":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Cancelled";
		break;
		case "information in hand":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Info in Hand";
		break;
		case "on hold":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "On Hold";
		break;
		case "open":
			$rptQuery.=" AND (orders.order_status!='filled' AND orders.order_status!='cancelled' AND orders.order_status!='basket')";
			$order_status_heading = "Open";
		break;
		case "waiting for lens":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Lens";
		break;
		case "verifying":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Verifying";
		break;
			
		case "scanned shape to swiss":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Scanned shape to SWISS";
		break;
			
		case "interlab tr":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Interlab Trois-Rivieres";
		break;
		
			
		case "all":
			$rptQuery.=" AND orders.order_status!='basket'";
			$order_status_heading = "Open";
		break;
	}
	$heading="$order_status_heading - $_POST[order_type] Orders";
	
	
	
	if($_POST["acct_num"]!=""){//if entered acct number
		$rptQuery.=" AND accounts.account_num='" . $_POST["acct_num"] . "'";
		$query="select account_num, company from accounts where account_num='$_POST[acct_num]'";
		$result=mysqli_query($con,$query) or die ("Could not find acct list");
		$acctData=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$heading.=" from account $acctData[company] with account number $_POST[acct_num]";
	}
	elseif($_POST["acctName"]==""){//if select ALL accounts

	}else{
		$rptQuery.=" AND orders.user_id='" . $_POST["acctName"] . "'";// ONE account was selected
		$query="SELECT user_id, company FROM accounts where user_id='$_POST[acctName]'";
		$result=mysqli_query($con,$query) or die ("Could not find acct list");
		$acctData=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$heading.=" from account $acctData[company]";
	}	
	if (($_POST["date_from"] != "All") && ($_POST["date_to"] != "All")){//select orders by date
		$date_from=date("Y-m-d",strtotime($_POST["date_from"]));
		$date_to=date("Y-m-d",strtotime($_POST["date_to"]));
		$dateInfo = " for date range: " . $_POST["date_from"] . " - " . $_POST["date_to"];
		
		if ($_POST["order_status"]!= 'filled')
		{
		$rptQuery.=" AND orders.order_date_processed between '$date_from' and '$date_to'";
		}else 
			{
			$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";
			}
		
	}


	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
		$rptQuery="SELECT  accounts.user_id as user_id, accounts.account_num, accounts.company, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.tray_num, accounts.company, orders.order_status, orders.redo_order_num,orders.order_shipping_cost,  est_ship_date.est_ship_date from orders
	
		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
		
		LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	
		WHERE orders.lab='$lab_pkey' $FiltreEntrepot AND orders.order_num = '$_POST[order_num]'";
	
		$heading="Order Number $_POST[order_num]";
	}

	if($_POST["patient_ref_num"]!=""){//search for patient reference number only and ignore all other form settings
		
		//Si la référence patient contient un espace, on split les 2 parties afin de chercher nom et prénom séparement
		if ($_POST["patient_ref_num"])
			$mystring = $_POST["patient_ref_num"];
			$findme   = ' ';
			$pos 	  = strpos($mystring, $findme);	
		
			if ($pos === false) {//Ne contient aucun espace
				$patient_ref_num="%".$_POST["patient_ref_num"]."%";
				$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.tray_num, accounts.company, orders.order_shipping_cost, orders.order_status,orders.redo_order_num, est_ship_date.est_ship_date from orders
				LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
				LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
				WHERE orders.lab='$lab_pkey' $FiltreEntrepot AND (orders.patient_ref_num like '$patient_ref_num' OR order_patient_first like '$patient_ref_num' OR order_patient_last like '$patient_ref_num')";

			}else{
				
				//Splitter en deux
				$pos 	  = $pos+1;	
				$PositionFinPrenom 			= $pos-1;
				$PositionDebutNomdeFamille  = $pos;
				$LongeurReferencePatient    = strlen($_POST["patient_ref_num"]);
				$PremierePartie 			= substr($_POST["patient_ref_num"],0,$PositionFinPrenom);
				$LongeurDeuxiemePartie   = ($LongeurReferencePatient-$pos);
				$DeuxiemePartie 			= substr($_POST["patient_ref_num"],$PositionDebutNomdeFamille,$LongeurDeuxiemePartie);
				//Cas possible  R1,  R2, R3, R4, 1,  2,  3
				$DeuxiemePartieV2 = $DeuxiemePartie .' R1';
				$DeuxiemePartieV3 = $DeuxiemePartie .' R2';
				$DeuxiemePartieV4 = $DeuxiemePartie .' R3';
				$DeuxiemePartieV5 = $DeuxiemePartie .' 1';
				$DeuxiemePartieV6 = $DeuxiemePartie .' 2';
				$DeuxiemePartieV7 = $DeuxiemePartie .' 3';
				
				$rptQuery="SELECT  accounts.user_id as user_id, accounts.account_num, accounts.company, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.tray_num, accounts.company, orders.order_shipping_cost, orders.order_status,orders.redo_order_num, est_ship_date.est_ship_date from orders
				LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
				LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
				WHERE orders.lab='$lab_pkey' $FiltreEntrepot AND order_patient_first like '%$PremierePartie%' AND order_patient_last like '%$DeuxiemePartie%'";
				
				
				//echo '<br>'. $rptQuery;
			}


			
			$heading="Orders for Patient Reference Number $_POST[patient_ref_num]";
	}
	
	
	if($_POST["tray_num"]!=""){//search for patient reference number only and ignore all other form settings
		$tray_num="%".$_POST["tray_num"]."%";
		$rptQuery="SELECT  accounts.user_id as user_id, accounts.account_num, accounts.company, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.tray_num, accounts.company, orders.order_shipping_cost, orders.order_status,orders.redo_order_num,est_ship_date.est_ship_date from orders
	
		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
		
		LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	
		WHERE orders.lab='$lab_pkey' $FiltreEntrepot  AND (orders.tray_num like '$tray_num')";
	
		$heading="Orders for Tray Number $_POST[tray_num]";
	}
	
	


	$rptQuery.=" group by order_num desc order by buying_group, company, order_date_processed";
	
	$heading.=$dateInfo;
	$heading=ucwords($heading);
}//END IF ($_POST[updateStatus]=="fill order(s)")
	
//echo '<br>'.$rptQuery;


if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
if($_POST["order_status"]!="")
	$_SESSION["order_status"]=$_POST["order_status"];
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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

<body onLoad="doOnLoad();">
<?php echo "<div style='font-size:10px'>Welcome  ".$_SESSION["labAdminData"]["user_id"]."</div>"; ?>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="report.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo $adm_orderrpts_txt; ?></font></b></td>
            	</tr>

				<tr bgcolor="#DDDDDD">
					
					<td colspan="2" nowrap bgcolor="#DDDDDD">
					<div align="center">
						<?php echo $adm_ordernum_txt; ?>
						<input name="order_num" type="text" id="order_num" size="10" class="formField">
					</div>
					</td>
					
					
					<td nowrap="nowrap">
					<div align="center">
						<?php echo $adm_patrefnum_txt; ?>
						<input name="patient_ref_num" type="text" id="patient_ref_num" size="20" class="formField">
					</div>
					</td>
					
					
	
					<td  colspan="2">&nbsp;</td>
	
				</tr>
				
				
				<tr bgcolor="#FFFFFF">
					<td width="15%" nowrap ><div align="right">
						<?php echo $adm_selorderstat_txt; ?>
					</div></td>
					<td width="15%" colspan="3" align="left" nowrap="nowrap">
					<select name="order_status" id="order_status" class="formField">
					  <option value="all">All</option>
					  <option value="cancelled">Cancelled</option>
					  <option value="processing">Confirmed</option>
                      <option value="central lab marking">Central Lab Marking</option>
					  <option value="delay issue 0">Delay Issue 0</option>
                      <option value="delay issue 1">Delay Issue 1</option>
                      <option value="delay issue 2">Delay Issue 2</option>
                      <option value="delay issue 3">Delay Issue 3</option>
                      <option value="delay issue 4">Delay Issue 4</option>
                      <option value="delay issue 5">Delay Issue 5</option>
                      <option value="delay issue 6">Delay Issue 6</option>
					  <option value="on hold">On Hold</option>
					  <option value="information in hand">Info in Hand</option>
					  <option value="in coating">In Coating</option>
					  <option value="in mounting">In Mounting</option>
                      <option value="in edging">In Edging</option>
                      <option value="job started">In Progress</option>
					  <option value="in transit">In Transit</option>
                      <option value="interlab">Interlab P</option>
                      <option value="interlab vot">Interlab P</option>
                      <option value="interlab qc">Interlab QC</option>
					  <option value="open">Open</option>
					  <option value="order completed">Order Completed</option>
                      <option value="order imported">Order Imported</option>
                      <option value="out for clip">Out for Clip</option>
 					  <option value="re-do">Redo</option>
                      <option value="filled">Shipped</option>
                      <option value="waiting for frame">Waiting for Frame</option>
					  <option value="waiting for frame knr">Waiting for Frame KNR</option>
                      <option value="waiting for frame hko">Waiting for Frame Central Lab</option>
					  <option value="waiting for lens">Waiting for Lens</option>
					  <option value="waiting for shape">Waiting for Shape</option>
                      <option value="verifying">Verifying</option>
					  <option value="scanned shape to swiss">Scanned shape to SWISS</option>
					  <option value="interlab tr">Interlab Trois-Rivieres</option>
					 </select>
                      
                  <?php echo '   Tray Num:'; ?>
                    <input name="tray_num" type="text" id="tray_num" size="5" class="formField">
                     </td>
				</tr>
	
				
				
				<tr bgcolor="#DDDDDD">
					<td colspan="3" align="center">
						<?php echo $adm_datefr_txt; ?>
						<input name="date_from" type="text" class="formField" id="date_from" 
                   		 value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> 
                     	 size="11"><!--order_date_processed-->

						<?php echo $adm_through_txt; ?>
						<input name="date_to" type="text" class="formField" id="date_to" value=
						<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>  
					 	size="11"><!-- order_date_processed (date order submitted) -->
					</td>
				</tr>
				
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
						<?php echo $adm_selacct_txt; ?>
					</div></td>
					<td align="left" nowrap ><select name="acctName" id="acctName" class="formField">
						<option value=""><?php echo $adm_all_txt; ?></option>
						<?php
	$query="select company, user_id, main_lab, approved from accounts where main_lab='$lab_pkey' and approved='approved' order by company";
	$result=mysqli_query($con,$query)or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
}
?>
					</select></td>
					<td align="left" nowrap ><div align="right">
						<?php echo $adm_oracctnum_txt; ?>
					</div></td>
					<td align="left" nowrap ><input name="acct_num" type="text" id="acct_num" size="10" class="formField"></td>
				</tr>
				

				<tr bgcolor="#FFFFFF">
					<td colspan="6">
						<div align="center">
							<input name="submit" type="submit" id="submit" value="<?php echo $btn_searchord_txt; ?>" class="formField">
							<input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField">
						</div>
					</td>
					</tr>
			</table>
</form>
			<?php 
			
			if ($rptQuery!=""){
				$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$usercount=mysqli_num_rows($rptResult);
				$rptQuery="";}	
if ($usercount != 0){//some orders were found
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
	if((($_POST["acctName"]!="")||($_POST["acct_num"]!=""))&&(($_POST["order_status"]=="filled")&&($_POST["order_type"]=="all"))){
		echo "<td colspan=\"11\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$stmtForm&nbsp;$exportForm</td>";//show Print Statement button if one acct is selected, order status is filled (Shipped) and order type is ALL
	}
	elseif($_GET["prnStmt"]=="yes"){
		echo "<td colspan=\"11\"><font color=\"white\">$heading</font></td>";//show Print Statement button if page is returned from Statement screen
	}
	elseif($_GET["exportData"]=="yes"){
		echo "<td colspan=\"11\"><font color=\"white\">$heading</font></td>";//show Export Report button if returning from Export Screen
	}
	elseif($_POST["rpt_search"]=="search orders"){
		echo "<td colspan=\"11\"><font color=\"white\">$heading</font></td>";//show Export Report button if form has been posted
	}else{
		echo "<td colspan=\"13\"><font color=\"white\">$heading</font></td>";//form has not been posted or first view
	}
	echo "</tr>";
	if(($_SESSION["order_status"]!="filled")&&($_SESSION["order_status"]!="cancelled"))//print order status form ONLY if order is not filled or cancelled
		echo "<form action=\"report.php\" method=\"post\" name=\"statusForm\">";
	//print the top heading row
	echo "<tr>
			<th align=\"center\">".$adm_orderno_txt."</th>
			<th align=\"center\">".$adm_orderdate_txt."</th>
			<th align=\"center\">".$adm_shipdate_txt."</th>
			<th align=\"center\">".$adm_estshipdate_txt."</th>
			<th align=\"center\">".$adm_compacct_txt."</th>
			<th align=\"center\">Lab</th>
			<th align=\"center\">Product</th>
			<th align=\"center\">".$adm_patient_txt."</th>
			<th align=\"center\"># Optipro</th>
			<th align=\"center\">".$adm_traynum_txt."</th>
			<th align=\"center\">".$adm_orderstatus_txt."</th>";
	echo "</tr>";
	$bgTotal=0;			  
	$acctTotal=0;
	do{
	
	if ($mylang == 'lang_english'){
		switch(strtolower($listItem["order_status"])){
		case 'processing':			$list_order_status = "Confirmed";				break;
		case 'order imported':		$list_order_status = "Order Imported";			break;
		case 'job started':			$list_order_status = "Surfacing";				break;
		case 'in coating':			$list_order_status = "In Coating";				break;
		case 'in mounting':			$list_order_status = "In Mounting";				break;
		case 'in edging':			$list_order_status = "In Edging";				break;
		case 'order completed':		$list_order_status = "Order Completed";			break;
		case 'delay issue 0':		$list_order_status = "Delay Issue 0";			break;
		case 'delay issue 1':		$list_order_status = "Delay Issue 1";			break;
		case 'delay issue 2':		$list_order_status = "Delay Issue 2";			break;
		case 'delay issue 3':		$list_order_status = "Delay Issue 3";			break;
		case 'delay issue 4':		$list_order_status = "Delay Issue 4";			break;
		case 'delay issue 5':		$list_order_status = "Delay Issue 5";			break;
		case 'delay issue 6':		$list_order_status = "Delay Issue 6";			break;
		case 'in transit':			$list_order_status = "In Transit";				break;
		case 'filled':				$list_order_status = "Shipped";					break;
		case 'cancelled':			$list_order_status = "Cancelled";				break;
		case 'waiting for frame':	$list_order_status = "Waiting for Frame";		break;
		case 'waiting for shape':	$list_order_status = "Waiting for Shape";		break;
		case 're-do':				$list_order_status = "Redo";					break;
		case 'on hold':				$list_order_status = "On Hold";					break;	
		case 'waiting for lens':	$list_order_status = "Waiting for Lens";		break;	
		case 'in edging swiss':		$list_order_status = "In Edging Swiss";			break;	
		case 'scanned shape to swiss':$list_order_status = "Scanned shape to Swiss";	break;
		case 'waiting for frame swiss':$list_order_status= "Waiting for frame SWISS"; break;
		case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";		break;
		case 'waiting for frame ho/supplier':	$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			}
	}else{
		switch(strtolower($listItem["order_status"])){
			case 'processing':			$list_order_status = "Commande Transmise";		break;
			case 'in coating':			$list_order_status = "Traitement AR";			break;
			case 'in transit':			$list_order_status = "En Transit";				break;
			case 'in mounting':			$list_order_status = "Au Montage";				break;
			case 'in edging':			$list_order_status = "Au Taillage";				break;
			case 'order imported':		$list_order_status = "Commande en cours";		break;
			case 'information in hand':	$list_order_status = "Info Transmise";   		break;
			case 'on hold':				$list_order_status = "En Attente";				break;	
			case 'order completed':		$list_order_status = "Production Terminée";   	break;
			case 'delay issue 0':		$list_order_status = "Délai 0";					break;
			case 'delay issue 1':		$list_order_status = "Délai 1";					break;
			case 'delay issue 2':		$list_order_status = "Délai 2";					break;
			case 'delay issue 3':		$list_order_status = "Délai 3";					break;
			case 'delay issue 4':		$list_order_status = "Délai 4";					break;
			case 'delay issue 5':		$list_order_status = "Délai 5";					break;
			case 'delay issue 6':		$list_order_status = "Délai 6";					break;
			case 'filled':				$list_order_status = "Expédiée";    			break;
			case 'cancelled':			$list_order_status = "Annulée";					break;
			case 'waiting for frame':	$list_order_status = "Attente de monture";		break;
			case 'waiting for lens':	$list_order_status = "Attente de verres";		break;	
			case 'waiting for shape':	$list_order_status = "Attente de forme";		break;
			case 're-do':				$list_order_status = "Reprise Interne";			break;	
			case 'job started':			$list_order_status = "Surfaçage";				break;
			case 'in edging swiss':		$list_order_status = "In Edging Swiss";			break;	
			case 'scanned shape to swiss':$list_order_status = "Scanned shape to Swiss";break;	
			case 'waiting for frame swiss':$list_order_status= "En attente de monture SWISS"; break;
			case 'waiting for frame store':	$list_order_status = "Attente de monture Magasin";		break;
			case 'waiting for frame ho/supplier':	$list_order_status = "Attente de monture Siege Social/Fournisseur";		break;
				}
	}		
	if(!isset($currentAcct)){
			$currentAcct=$listItem["company"];
		}
		
		$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
		$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
		$est_ship_date=$listItem[est_ship_date];

		
			

		switch ($listItem[prescript_lab]){
		case 10: 	$LabthatProduces  = 'Swisscoat'; break;
		case  3:	$LabthatProduces  = 'St.Catharines'; break;;
		default :   $LabthatProduces = '';		
		}
			
		$LabthatProduceJob ="";
		if ($listItem[prescript_lab]==10){
			
		}
		
		
		$preTotal = $listItem["order_total"] + $listItem["order_shipping_cost"];
		$orderTotal=money_format('%.2n',$preTotal);
		
		if(($currentBG!=$listItem["bg_name"])&&($currentAcct!=$listItem["company"])){
			$bgTotal=money_format('%.2n',$bgTotal);
			
			echo "<tr>
			<th align=\"center\">".$adm_orderno_txt."</th>
			<th align=\"center\">".$adm_orderdate_txt."</th>
			<th align=\"center\">".$adm_shipdate_txt."</th>
			<th align=\"center\">".$adm_estshipdate_txt."</th>
			<th align=\"center\">".$adm_compacct_txt."</th>
			<th align=\"center\">Lab</th>
			<th align=\"center\">Product</th>
			<th align=\"center\">".$adm_patient_txt."</th>
			<th align=\"center\"># Optipro</th>
			<th align=\"center\">".$adm_traynum_txt."</th>
			<th align=\"center\">".$adm_orderstatus_txt."</th></tr>";
			$acctTotal=0;			  
			$currentAcct=$listItem["company"];
			$bgTotal=0;
	}
		elseif(($currentBG==$listItem["bg_name"])&&($currentAcct!=$listItem["company"])){
			$acctTotal=money_format('%.2n',$acctTotal);
			echo "<tr bgcolor=\"#555555\"><td colspan=\"11\"><font color=\"white\">&nbsp;</font></td></tr>";//print the account total and then print the next heading row
			echo "<tr>
			<th align=\"center\">".$adm_orderno_txt."</th>
			<th align=\"center\">".$adm_orderdate_txt."</th>
			<th align=\"center\">".$adm_shipdate_txt."</th>
			<th align=\"center\">".$adm_estshipdate_txt."</th>
			<th align=\"center\">".$adm_compacct_txt."</th>
			<th align=\"center\">Lab</th>
			<th align=\"center\">Product</th>
			<th align=\"center\">".$adm_patient_txt."</th>
			<th align=\"center\">".$adm_patientrefno_txt."</th>
			<th align=\"center\">".$adm_traynum_txt."</th>
			<th align=\"center\">".$adm_orderstatus_txt."</th></tr>";
			$acctTotal=0;			  
			$currentAcct=$listItem["company"];
	}
		$bgTotal=bcadd($bgTotal, $orderTotal, 2);
		$bgTotal=bcsub($bgTotal, $pmt_amount, 2);
		$acctTotal=bcadd($acctTotal, $orderTotal, 2);
		$acctTotal=bcsub($acctTotal, $pmt_amount, 2);
		echo  "<tr><td align=\"center\"><a href=\"display_order.php?order_num=$listItem[order_num]$type_string&po_num=$listItem[po_num]$type_string\">$listItem[order_num]";
		if ($listItem['redo_order_num']!=0) echo "R";
		echo "</a></td>
			<td align=\"center\">$listItem[order_date_processed]</td>";
			if($ship_date!=0)
				echo "<td align=\"center\">$listItem[order_date_shipped]</td>";
			else
				echo "<td align=\"center\">$listItem[order_date_shipped]</td>";
				
echo "<td align=\"center\">$est_ship_date</td>";

echo "<td align=\"center\">$listItem[user_id]</td>
			<td align=\"center\">$LabthatProduces</td>
			<td align=\"center\">$listItem[order_product_name]</td>
			<td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
			<td align=\"center\">$listItem[order_num_optipro]</td>
			<td align=\"center\">$listItem[tray_num]</td>
			<td align=\"center\"><a target=\"_blank\" href=\"status_history.php?order_num=$listItem[order_num]\">$list_order_status</a></td>
			
			<td align=\"center\">";
			if($pmt_amount!=0)
				echo "\$$pmt_amount</td>";
			else
				echo "&nbsp;</td>";
		  echo "</tr>";
	}while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC));
	
	//END WHILE

		$acctTotal=money_format('%.2n',$acctTotal);
		$bgTotal=money_format('%.2n',$bgTotal);
					
	if(($_SESSION["order_status"]!="filled")&&($_SESSION["order_status"]!="cancelled")){//print Fill Order(s) button ONLY if orders are not filled or cancelled
		echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"11\">&nbsp;</td><td colspan=\"2\"></td></tr></form>";
	}
	echo "</table>";

}else{
	echo "<div class=\"formField\">".$adm_noorders_txt."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
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
