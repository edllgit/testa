<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
require_once(__DIR__.'/../constants/mysql.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../connexion_hbc.inc.php";
include("admin_functionshbc.inc.php");
//include('../phpmailer_email_functions.inc.php');

session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


	

if (($_POST["email_cred_btn"]=="Email Credit to Customer") && (strlen($_POST["memo_credit_number"]) ==7)){
		
//Envoyer par email le credit au client	
$leOrderNumber = substr($_POST["memo_credit_number"],1,5);
$queryEmail    = "SELECT email FROM accounts WHERE user_id = (SELECT distinct user_id FROM orders WHERE order_num = $leOrderNumber)";
$resultEmail   = mysqli_query($con,$queryEmail)		or die ('Could not check already credited because: ' . mysqli_error($con));
$DataEmail     = mysqli_fetch_array($resultEmail,MYSQLI_ASSOC);
$CustomerEmail = $DataEmail[email];

//echo 'Emailing credit ' .$_POST["memo_credit_number"] . '  to customer at '.  $CustomerEmail;

//SEND BY EMAIL THE CREDIT TO THE CUSTOMER (COPY TO US)
$lab_pkey=$_SESSION["lab_pkey"];
$logo_file=$_SESSION["labAdminData"]["logo_file"];

$message= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Place Order</title>
<link href="../dl.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	font-family:Arial;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>
</head>';

$queryLab = "SELECT lab FROM orders  WHERE order_num = " . $leOrderNumber;
$ResultLab = mysqli_query($con,$queryLab)	or die  ('I cannot select items 0 because  : ' . mysqli_error($con) .'<br>'.$queryLab );	
$DataLab   = mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);
$MainLab   = $DataLab['lab']; 
$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits.*, orders.order_total,         					
orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num FROM     
	memo_credits,  orders , accounts, memo_codes
	WHERE mcred_memo_num = '" . $_POST["memo_credit_number"] . "' 
	AND orders.order_num = memo_credits.mcred_order_num 
	AND memo_codes.mc_lab =  $MainLab
	AND accounts.user_id = orders.user_id
	AND memo_codes.memo_code = memo_credits.mcred_memo_code  ";
	//echo '<br><br>'.$query;
	$nom_bd = constant('MYSQL_DB_DIRECT_LENS');
	mysqli_select_db($con,$nom_bd);
	$orderResult=mysqli_query($con,$query)	or die  ('I cannot select items because 3 : ' . mysqli_error($con) . '<br>'.$query);	
	$Data=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
    $message.='<body style="font-family:Arial;"><table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>';
	$queryLogo ="SELECT  logo_file FROM labs WHERE primary_key = (SELECT distinct lab FROM orders WHERE order_num = " .  $leOrderNumber  . ")";
	$ResultLogo=mysqli_query($con,$queryLogo)	or die  ('I cannot select items because 1 : ' . mysqli_error($con));	
	$DataLogo=mysqli_fetch_array($ResultLogo,MYSQLI_ASSOC);
	$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
	$ResultUser=mysqli_query($con,$queryUser)	or die  ('I cannot select items because 2 : ' . mysqli_error($con));	
	$DataUser=mysqli_fetch_array($ResultUser,MYSQLI_ASSOC);
	$CustomerLanguage = $DataUser[language];
	
 $message.= '<td align="left"><img src="'.constant('DIRECT_LENS_URL').'/logos/'. $DataLogo[logo_file]. '"/></td>
<td align="right"><img src="'.constant('DIRECT_LENS_URL').'/logos/direct-lens_logo.gif" width="200" height="60" /></td>
</tr></table>'; 
 
 $message.='<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	<td><div class="header2">';
    if ($CustomerLanguage == 'french'){ 
    $message.= 'Memo Credit pour votre commande #:'; 
	}else{ 
	$message.= 'Memo Credit for your Order #:';
    } 
	$message.= $Data[mcred_order_num] . '</div>
    </td>
    </tr>
    </table>';
	
	
	$message.='<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">';
    if ($CustomerLanguage == 'french'){ 
    $message.='D&Eacute;TAIL DU CR&Eacute;DIT';
	}else{ 
	$message.='MEMO ORDER INFORMATION';
	} 
    $message.='</td>
    </tr>
	<tr>
    <td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){ 
    $message.='Date du memo cr&eacute;dit: ';
	}else{ 
    $message.='Memo Order Date:';
	} 
    $message.='</td>
   <td width="520" class="formCellNosides">'.  $Data[mcred_date]. '</strong></td>
   </tr>';
    
   
	$message.='<tr>
    <td align="right" class="formCellNosides">  '; 
	if ($CustomerLanguage == 'french'){ 
    $message.='Num&eacute;ro de commande: ';
	}else{ 
	$message.='Order Number:';
	} 
	$message.='</td>
    <td width="520" class="formCellNosides"><strong>'. $Data[mcred_order_num]. '</strong></td>
    </tr>';
    
    
	$message.='<tr><td align="right" class="formCellNosides">';
	 if ($CustomerLanguage == 'french'){ 
     $message.='Total de la commande: ';
	 }else{ 
	 $message.='Order Total:';
	 } 
	 $message.='</td><td width="520" class="formCellNosides"><strong>'.  $Data[order_total]. '</strong></td>
    </tr>';
    
	
	$message.='<tr>
    <td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){ 
    $message.='Nom du client';
	}else{ 
	$message.='Customer Name:';
	} 
    $message.='</td>
    <td width="520" class="formCellNosides"><strong>' . $Data[company]. '</strong></td>
    </tr>';
    
	
	$message.='<tr>
    <td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){ 
    $message.=' Num&eacute;ro de compte client: ';
	}else{ 
    $message.='Customer Account:';
	} 
    $message.='</td>
    <td width="520" class="formCellNosides"><strong>'. $Data[account_num]. '</strong></td>
    </tr>';
	
	
    $message.='<tr>
    <td align="right" class="formCellNosides" nowrap>';
    if ($CustomerLanguage == 'french'){ 
    $message.='Num&eacute;ro de r&eacute;f&eacute;rence patient:'; 
	}else{ 	
    $message.='Patient Reference Number:';
	} 
    $message.='</td>
    <td width="520" class="formCellNosides"><strong>' .  $Data[patient_ref_num].'</strong></td>
    </tr>';
	
    
    $message.='<tr>
    <td align="right" class="formCellNosides" nowrap>';
    if ($CustomerLanguage == 'french'){ 
    $message.='Pr&eacute;nom patient: ';
	}else{ 	
    $message.='Patient First Name:';
	} 
	$message.='</td>
    <td width="520" class="formCellNosides"><strong>'. 	 $Data[order_patient_first]. '</strong></td>
    </tr>';
	

    $message.='<tr>
    <td align="right" class="formCellNosides" nowrap>';
	if ($CustomerLanguage == 'french'){ 
    $message.='Nom de famille patient: ';
	}else{ 	
    $message.='Patient Last Name:';
	} 
     $message.='</td>
    <td width="520" class="formCellNosides"><strong>'.  $Data[order_patient_last]. '</strong></td>
    </tr>';
	
	
     $message.='<tr>
    <td align="right" class="formCellNosides">';
     if ($CustomerLanguage == 'french'){ 
     $message.='Num&eacute;ro de memo cr&eacute;dit: ';
	 }else{ 
     $message.='Memo Order Number:';
	 } 
   $message.= '</td><td width="520" class="formCellNosides"><strong>'.  $Data[mcred_memo_num]. '</strong></td>
    </tr>';
	
	
    $message.='<tr>
    <td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){ 
     $message.='Valeur du memo cr&eacute;dit: ';
	}else{	
     $message.='Memo Order Value:';
	} 
   $message.='</td><td width="520" class="formCellNosides"><strong>-'. $Data[mcred_abs_amount]. '$</strong></td>
    </tr>';
    
    
	$message.='<tr><td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){
    $message.='Raison du cr&eacute;dit:';
	}else{ 	
     $message.='Reason Code:';
	} 
    $message.='</td><td width="520" class="formCellNosides"><strong>'.$Data[mcred_memo_code] . '-' .  $Data[mc_description] . '</strong></td>
    </tr>';
	
	
	$message.='<tr><td align="right" class="formCellNosides">';
    if ($CustomerLanguage == 'french'){
    $message.='D&eacute;tail du cr&eacute;dit:';
	}else{ 	
     $message.='Credit Detail:';
	} 
    $message.='</td><td width="520" class="formCellNosides"><strong>'.$Data[mcred_detail] . '</strong></td>
    </tr>';
   
   
    $message.='<tr>';
    if ( $Data[optipoints_to_substract] > 0)
	{ 
			 $message.= '<tr><td><img width="200" src="'.constant('DIRECT_LENS_URL').'/images/Logo_Opti-Points.png" /></td></tr>';
			if ($CustomerLanguage == 'french')
			{
			 $message.= '<td colspan="2"><p style="font-family:Arial;">Cette commande a &eacute;t&eacute; cr&eacute;dit&eacute;e gr&acirc;ce &agrave; vos Opti-Points! Cette demande de cr&eacute;dit n\'est pas 
couverte selon les politiques de garanties limit&eacute;es de LensNet Club.';		
			 $message.= "<br><br>Raison: $Data[optipoints_reason]". "<br>Nb de points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}else{
			 $message.= '<td colspan="2"><p style="font-family:Arial;">Your credit request cannot be covered under the terms of the Limited Warranty as a manufacturer\'s 
defect. However, we have covered your request under your available Opti-Points.';			
			 $message.= "<br><br>Reason: $Data[optipoints_reason]". "<br>Number of points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}
    }  
echo '<br><br>'. $message . '<br><br>';

//Send the email TO THE CUSTOMER
$send_to_address = str_split($CustomerEmail,100);
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Memo Credit Customer Copy/ Memo credit copie client";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
if ($response) 
echo '<br><b>Credit sucessfully sent to the customer at : '.$CustomerEmail. '</b>';
else
echo '<br>Error while trying to send the credit at '. var_dump($CustomerEmail) . ', please contact administrator' ;

echo $message;

//Send the email TO ORDERSRCO
$send_to_address=array('dbeaulieu@direct-lens.com');	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Memo Credit Administration Copy";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
if ($response) 
echo '<br><b>Credit sucessfully sent to the customer at administration</b>';
else
echo '<br>Error while trying to send the credit at  administration, please contact administrator' ;
}//End if  a memo credit num has been submitted and has to be sent to the customer by email


if($_POST[rpt_search]=="Find")
{
	
	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
	//On Cr?? l'array avec tous les order num qui ont ?t? entr?s	

	$_POST[order_num] =  trim($_POST[order_num],"\n");
	$_POST[order_num] =  trim($_POST[order_num],"\r");
	$_POST[order_num] =  trim($_POST[order_num]," ");
	
	
	//Enlever la virgule de la fin s'il y en a une 
	if (substr($_POST["order_num"], -1) == ',') {
	$_POST["order_num"] = substr($_POST["order_num"],0,strlen($_POST["order_num"])-1);
	}

	$Array_OrderNum =  explode(",", $_POST["order_num"]);
	//Valider les num?ros de commandes pass?, longeur doit etre de 7, doit etre numeric
	$errorMessage = '';
	$PassValidation = true;
	$Array_OrderNum = array_filter(array_map('trim', $Array_OrderNum));
	
		foreach( $Array_OrderNum as $value ){
		$valueSansEspace = trim($value, " ");
		
			if (strlen($valueSansEspace)<> 5)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Invalid Order number (should be 5 caracters)";
			$PassValidation = false;
			}
		
			if (is_numeric($valueSansEspace)==false)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Order number contains illegal caracters";
			$PassValidation = false;
			}
			
			$comma  = ',';
			$CommaInOrderNum = strpos($_POST["order_num"], $comma);
			$LongeurOrderNum = strlen(trim($_POST["order_num"], " "));

			//Si la longeur est > a 5 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque num?ro
			if (($LongeurOrderNum >5) && ($CommaInOrderNum == '' )){
			$errorMessage .= "<br>Order numbers does not contain any comma (,)  please separate each order number with a comma";
			$PassValidation = false;
			}
			
		}//End for each
	  
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name,  memo_credits.mcred_memo_num from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key), memo_credits
			WHERE orders.order_num IN ($_POST[order_num]) AND  
			 memo_credits.mcred_order_num = orders.order_num GROUP BY mcred_memo_num";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty

}//End if Verify






if(isset($_POST[UpdateOrderNum])){
$UpdateDetails  = "";

foreach( $_POST[UpdateOrderNum] as $the_order_num ){	
	//First we need to insert in status_history to keep a track of what has been updated
	$todayDate = date("Y-m-d g:i a");// current date
	$order_date_shipped = date("Y-m-d");// current date
	$currentTime = time($todayDate); //Change date into time
	$timeAfterOneHour = $currentTime;
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$acces_id = $_SESSION["access_admin_id"];
	if (strlen($the_order_num)==7)
	{
		
		$queryGetInfo  		= "SELECT user_id, patient_ref_num, order_patient_first, order_patient_last FROM orders WHERE order_num =  " .$the_order_num;
		$resultGetInfo 		= mysqli_query($con,$queryGetInfo)		or die ('Could not check already credited because: ' . mysqli_error($con));
		$DataGetInfo   		= mysqli_fetch_array($resultGetInfo,MYSQLI_ASSOC);
		
		$querymemoCred  	= "SELECT count(mcred_primary_key) as nbrMemoCred FROM memo_credits WHERE mcred_order_num =  ". $the_order_num;
		$resultmemoCred 	= mysqli_query($con,$querymemoCred)		or die ('Could not check querymemoCred because: ' . mysqli_error($con));
		$DatamemoCred   	= mysqli_fetch_array($resultmemoCred,MYSQLI_ASSOC);
		$nbrMemoCred 	 	= $DatamemoCred[nbrMemoCred];
		
		$querymemoCredTemp  = "SELECT count(mcred_primary_key_temp) as nbrMemoCredTemp  FROM memo_credits WHERE mcred_order_num =  ". $the_order_num;
		$resultmemoCredTemp = mysqli_query($con,$querymemoCredTemp)		or die ('Could not check querymemoCredTemp because: ' . mysqli_error($con));
		$DatamemoCredTemp   = mysqli_fetch_array($resultmemoCredTemp,MYSQLI_ASSOC);
		$nbrMemoCredTemp 	= $DatamemoCredTemp[nbrMemoCredTemp];
		
		$MemoCredExistant   = $nbrMemoCred + $nbrMemoCredTemp;
		
		switch($MemoCredExistant){
		case 0: $AjoutMemoNum = 'A';    break;
		case 1: $AjoutMemoNum = 'B';    break;
		case 2: $AjoutMemoNum = 'C';    break;
		case 3: $AjoutMemoNum = 'D';    break;
		case 4: $AjoutMemoNum = 'E';    break;
		case 5: $AjoutMemoNum = 'F';    break;
		case 6: $AjoutMemoNum = 'G';    break;
		case 7: $AjoutMemoNum = 'H';    break;
		case 8: $AjoutMemoNum = 'I';    break;
		case 9: $AjoutMemoNum = 'J';    break;
		case 10:$AjoutMemoNum = 'K';    break;
		}
		
		
		$mcred_memo_num     =  'M'. $the_order_num . $AjoutMemoNum;
		$today=date("Y-m-d");
		
		//We create the credit in memo_credits table
		$InsertQuery = "INSERT INTO memo_credits (
		mcred_acct_user_id,		mcred_order_num,		pat_ref_num,		patient_first_name,		patient_last_name,		mcred_memo_num,		mcred_cred_type,		mcred_date)
		VALUES (		'$DataGetInfo[user_id]', 		$the_order_num, 		'$DataGetInfo[patient_ref_num]', 		'$DataGetInfo[order_patient_first]', 		'$DataGetInfo[order_patient_last]',
		'$mcred_memo_num',		'credit',		'$datecomplete')";		//Ces info viendront de la table ORDERS
		$UpdateDetails  .= "<br>Credit  request for order #$the_order_num has been set  to: Request received";
		$resultInsert=mysqli_query($con,$InsertQuery)		or die ('Could not insert because: ' . mysqli_error($con));

		//mcred_disc_type,   		//mcred_amount,
		//mcred_abs_amount,    		//mcred_memo_code,
		
		$todayDate = date("Y-m-d g:i a");// current date
		$ip	       = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		$acces_id  = $_SESSION["access_admin_id"];	
		
		//We add the credit status in credit_status_history table
		$InsertQueryHistory = "INSERT INTO memo_credits_status_history (
		 		 mcred_memo_num,       order_num,      request_status,        update_time,       update_type,  update_ip,  access_id)
		VALUES ('$mcred_memo_num', $the_order_num,    'Request received',    '$datecomplete',        'manual',     '$ip',     $acces_id)";
		
		$resultInsertHistory=mysqli_query($con,$InsertQueryHistory)		or die ('Could not insert because: ' . mysqli_error($con)); 	  
		$DisplayUpdateDetail = true;
	}	
		
}//End for each

	
}//End if Update Status
?>
<html>
<head> 
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		$Order_Num_Sans_Espace =  $_POST[order_num];
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace,"\n");
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace,"\r");
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace," ");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="email_credit_hbc.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Email Credit to HBC Store</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" >                        
                        <div style="font-size:14px;" align="center">Type <b>One</b> Order Number</div>					
                        <textarea cols="20" name="order_num" style="font-size:16px;"  rows="1" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Find" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                		
			</table>
</form>


<form  method="post" name="email_credit" id="email_credit" action="email_credit_hbc.php">
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysqli_query($con,$rptQuery)		or die  ('<strong>Errors occured during the process:  Please be sure that there are no extra spaces or break line after your last order number !</strong> '. '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'. $rptQuery . mysqli_error($con));
			$usercount=mysqli_num_rows($rptResult);
				$rptQuery="";}
					
if (($usercount == 0) && ($_POST[rpt_search]=="Find")) {
echo '<p align="center">No <b>pending</b> credit request have been found with this order number: '. $_POST["order_num"] .'</p>';	
echo '</form>';
}
			
if ($usercount != 0){

echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr bgcolor=\"#000000\"></tr>";
	  echo "<tr>
	 		    <td align=\"center\">&nbsp;</td>
                <td align=\"center\"><strong>Order Number</strong></td>
                <td align=\"center\"><strong>Order Date</strong></td>
				<td align=\"center\"><strong>Main Lab</strong></td>
				<td align=\"center\"><strong>Company</strong></td>
				<td align=\"center\"><strong>Tray</strong></td>
				<td align=\"center\"><strong>Pat. First</strong></td>
				<td align=\"center\"><strong>Pat. Last</strong></td>
				<td align=\"center\"><strong>Ref</strong></td>
                <td align=\"center\"><strong>Date Shipped</strong></td>
                <td align=\"center\"><strong>Order Status</strong></td>
	            </tr>
				<tr>";		  
$OrderAbleToUpdate = 0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){

			$order_date=$listItem[order_date_processed];
			$ship_date=$listItem[order_date_shipped];
				
				switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";					break;
						case 'order imported':			$list_order_status = "Order Imported";				break;
						case 'job started':				$list_order_status = "Surfacing";				    break;
						case 'in coating':				$list_order_status = "In Coating";					break;
						case 'in mounting':				$list_order_status = "In Mounting";					break;
						case 'in edging':				$list_order_status = "In Edging";					break;
						case 'order completed':			$list_order_status = "Order Completed";				break;
						case 'interlab':				$list_order_status = "Interlab P";					break;
						case 'interlab qc':				$list_order_status = "Interlab QC";					break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";				break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";				break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";				break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";				break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";				break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";				break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";				break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";			break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";			break;
						case 'information in hand':		$list_order_status = "Info in Hand";			    break;
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Redo";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'verifying':				$list_order_status = "Verifying";					break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";		break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
				}			
          
			
			
			//Commande d?ja shipp?, on disable la case a cocher
				
				$queryMcredMemoNum     = "SELECT mcred_memo_num FROM memo_credits WHERE mcred_order_num = ". $listItem[order_num];
				$resultMcredMemoNum    = mysqli_query($con,$queryMcredMemoNum)		or die ('Could not check already credited because: ' . mysqli_error($con));
				$DataMcredMemoNum      = mysqli_fetch_array($resultMcredMemoNum,MYSQLI_ASSOC);
				$OrderAbleToUpdate     +=1;
				
echo "
<td style=\"font-size:16px;\" align=\"center\"><input type=\"checkbox\" name=\"memo_credit_number\" id=\"memo_credit_number\" value=\"$listItem[mcred_memo_num]\"></td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[order_num]</td> 
<td style=\"font-size:16px;\" align=\"center\">$order_date</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[lab_name]</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[company]</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[tray_num]&nbsp;</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[order_patient_first]&nbsp;</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[order_patient_last]&nbsp;</td>
<td style=\"font-size:16px;\" align=\"center\">$listItem[patient_ref_num]&nbsp;</td>";
			
			
		
		 
				    
				if($ship_date!=0)
                echo "<td style=\"font-size:16px;\"    align=\"center\">$ship_date&nbsp;</td>";
				else
				if ($list_order_status=='Cancelled'){
				echo "<td  align=\"center\">&nbsp;</td>";
				}else{
				echo "<td align=\"center\">&nbsp;</td>";
				}
                	
		
		
		//if ($list_order_status!='Shipped'){
		//echo "<td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$list_order_status</td>";
		//}else{
		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>";
		//}
		
		
		$queryAlreadyCredited  = "SELECT SUM(mcred_abs_amount) as dejacredite FROM memo_credits WHERE mcred_order_num = $listItem[order_num]";
		$resultAlreadyCredited = mysqli_query($con,$queryAlreadyCredited)		or die ('Could not check already credited because: ' . mysqli_error($con));
		$DataAlreadyCredited   = mysqli_fetch_array($resultAlreadyCredited,MYSQLI_ASSOC);
		if ($DataAlreadyCredited[dejacredite] == '')
		{
			$dejaCredite = '0'. '$';
		}else{
			$dejaCredite = $DataAlreadyCredited[dejacredite]	. '$';
		}
		
		echo	"</tr>";
}//END WHILE		  
				  
echo "</table>";
echo '<p><input style="font-size:14px;" name="email_cred_btn" type="submit" id="email_cred_btn" value="Email Credit to Customer" class="formField"></p>';
echo "</form>";
}
?>
  
<br><br>    
</td>
</tr>
</table>
  <p>&nbsp;</p>
</body>
</html>