<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
require_once(__DIR__.'/../constants/mysql.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");


session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
//Provient de la recherche
//echo '<br>$_REQUEST[mcred_memo_num]:'. $_REQUEST[mcred_memo_num];
if ($_REQUEST[mcred_memo_num] <> ''){
	$mcred_memo_num = mysqli_escape_string($con,$_REQUEST[mcred_memo_num]);
	$order_num      = substr($mcred_memo_num,1,7);
}else{
//Afficher erreur et stopper le processus
echo '<br><p>Error: No memo credit has been submitted</p>';	
exit();		
}
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
  		<td width="25%"><?php include("adminNav.php");?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="edit_credit_request.php">

<?php 
//1- Enregistrer le changement de status (approved) dans memo_Credits_status_history
$todayDate 			= date("Y-m-d g:i a");// current date
$order_date_shipped = date("Y-m-d");// current date
$currentTime 	    = time($todayDate); //Change date into time
$timeAfterOneHour   = $currentTime;
$datecomplete 	    = date("Y-m-d H:i:s",$timeAfterOneHour);
$ip		  	  	    = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$acces_id           = $_SESSION["access_admin_id"];
$update_type 		= 'manual';

$queryApprove= "INSERT INTO memo_credits_status_history  (mcred_memo_num, 	order_num, 	request_status, request_status_fr,	update_time, 	update_type, 	
update_ip, 	access_id)   VALUES('$mcred_memo_num',  $order_num, 'Approved','Approuvé',  '$datecomplete','$update_type',   '$ip',     $acces_id )";
//echo '<br><br>'. $queryApprove;
$resultApprove=mysqli_query($con,$queryApprove)		or die ('Could not insert because: ' . mysqli_error($con). $queryApprove); 	 
  
//Select information from the credit request
$query="SELECT * from memo_credits_temp WHERE  mcred_memo_num ='$mcred_memo_num'";
$result=mysqli_query($con,$query)		or die ("Could not create new product because 1" . mysqli_error($con). $query );
$DataAapprouver=mysqli_fetch_array($result,MYSQLI_ASSOC);

//Get the PK of the  lab that request the credit
$queryEmail="SELECT lab  FROM orders WHERE order_num  = $DataAapprouver[mcred_order_num]";
$resultEmail=mysqli_query($con,$queryEmail)		or die ("Could not create new product because 2  " . mysqli_error($con). $queryEmail);
$DataEmail=mysqli_fetch_array($resultEmail,MYSQLI_ASSOC);


//2- SI IL Y EN A, SUPPRIMER / METTRE A JOUR LES OPTI-POINTS
//first if there are optipoints to save in the history we insert it
		if ($DataAapprouver[optipoints_to_substract] > 0)
		{
			$queryMemoCode = "SELECT mc_description from memo_codes WHERE memo_code = $DataAapprouver[mcred_memo_code] and mc_lab= $DataEmail[lab]";
			$resultMemoCode=mysqli_query($con,$queryMemoCode)		or die ("Could not create new product because3  " . mysqli_error($con). $queryMemoCode );
			$DataMemoCode=mysqli_fetch_array($resultMemoCode,MYSQLI_ASSOC);
			$Memo_Code = $DataMemoCode['mc_description'];
			//Insert in lnc history to keep a trace
			$QueryInsertRewardHistory= "INSERT INTO lnc_reward_history (access_id,detail,amount,datetime,user_id) VALUES
			(14, '$DataAapprouver[mcred_order_num]:  $DataAapprouver[optipoints_reason]', '-$DataAapprouver[optipoints_to_substract]','$datecomplete', 	             '$DataAapprouver[mcred_acct_user_id]')";
			$resultInsert=mysqli_query($con,$QueryInsertRewardHistory)	or die ("Could not create new product because 4" . mysqli_error($con).$QueryInsertRewardHistory );
			//Then Optipoints needs to be deducted from the customer accounts  
			$queryDetail = "SELECT lnc_reward_points from accounts WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
			$resultDetail=mysqli_query($con,$queryDetail)		or die ("Could not create new product because5  " . mysqli_error($con).$queryDetail );
			$DataDetail=mysqli_fetch_array($resultDetail,MYSQLI_ASSOC);
			$ActualPointBalance = $DataDetail[lnc_reward_points];
			$NewPointBalance = $ActualPointBalance - $DataAapprouver[optipoints_to_substract];		
			$queryUpdateBalance = "UPDATE accounts SET lnc_reward_points = $NewPointBalance  WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
			$resultBalance=mysqli_query($con,$queryUpdateBalance)	or die ("Could not create new product because 6 " . mysqli_error($con). $queryUpdateBalance );
		}//end if
	
		
	$PrenomPatient     = mysqli_real_escape_string($con,$DataAapprouver[patient_first_name]);
	$NomFamillePatient = mysqli_real_escape_string($con,$DataAapprouver[patient_last_name]);
	
//3- Deplacer le memo credit de la table memo_credits_temp vers la table memo_credits
	    $tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$datedujour = date("Y-m-d", $tomorrow);
		//Insert the credit request into credit table because it has been approved
		$query="INSERT INTO memo_credits(mcred_acct_user_id, mcred_order_num, pat_ref_num, patient_first_name, patient_last_name, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date,             date_mc_applied,optipoints_to_substract,optipoints_reason, mcred_detail) 
			VALUES (
			'$DataAapprouver[mcred_acct_user_id]',		'$DataAapprouver[mcred_order_num]',
			'$DataAapprouver[pat_ref_num]',				'$PrenomPatient',
			'$NomFamillePatient',		'$DataAapprouver[mcred_memo_num]',
			'$DataAapprouver[mcred_cred_type]',			'$DataAapprouver[mcred_disc_type]',
			'$DataAapprouver[mcred_amount]',			'$DataAapprouver[mcred_abs_amount]',
			'$DataAapprouver[mcred_memo_code]',			'$DataAapprouver[mcred_date]',
			'$DataAapprouver[date_mc_applied]',			'$DataAapprouver[optipoints_to_substract]',
			'$DataAapprouver[optipoints_reason]', 		'$DataAapprouver[mcred_detail]')";
			
			$result=mysqli_query($con,$query)		or die ("Could not create new product because 7 ".$query . mysqli_error($con). $query );
          
  
         
//4- 	//Delete from temp table because it has been inserted in memo credit table
		$queryDelete="UPDATE memo_credits_temp  SET  mcred_approbation  = 'approved' WHERE  mcred_memo_num ='$mcred_memo_num'";
		$resultDelete=mysqli_query($con,$queryDelete)		or die ("Could not delete temporary memo credit" . mysqli_error($con). $queryDelete );	
	
	

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

$queryLab = "SELECT lab FROM orders  WHERE order_num = " . $DataAapprouver[mcred_order_num];
$ResultLab = mysqli_query($con,$queryLab)	or die  ('I cannot select items 0 because  : ' . mysqli_error($con) .'<br>'.$queryLab );	
$DataLab   = mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);
$MainLab   = $DataLab['lab']; 
$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits.*, orders.order_total,         					
orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num FROM     
	memo_credits,  orders , accounts, memo_codes
	WHERE mcred_memo_num = '" . $_REQUEST[mcred_memo_num] . "' 
	AND orders.order_num = memo_credits.mcred_order_num 
	AND memo_codes.mc_lab =  $MainLab
	AND accounts.user_id = orders.user_id
	AND memo_codes.memo_code = memo_credits.mcred_memo_code  ";
	//echo '<br><br>'.$query;
	$nom_bd = constant('MYSQL_DB_DIRECT_LENS');
	//mysql_select_db($nom_bd);
	$orderResult=mysqli_query($con,$query)	or die  ('I cannot select items because 3 : ' . mysqli_error($con) . '<br>'.$query);	
	$Data=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
	
   $message.='<body style="font-family:Arial;"><table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>';

	$queryLogo ="SELECT  logo_file FROM labs WHERE primary_key = (SELECT distinct lab FROM orders WHERE order_num = " .  $order_num  . ")";
	$ResultLogo=mysqli_query($con,$queryLogo)	or die  ('I cannot select items because 1 : ' . mysqli_error($con).$queryLogo);	
	$DataLogo=mysqli_fetch_array($ResultLogo,MYSQLI_ASSOC);
	$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
	$ResultUser=mysqli_query($con,$queryUser)	or die  ('I cannot select items because 2 : ' . mysqli_error($con).$queryUser);	
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
			echo '<tr><td><img width="200" src="../images//Logo_Opti-Points.png" /></td></tr>';
			if ($CustomerLanguage == 'french')
			{
			echo '<td colspan="2"><p style="font-family:Arial;">Cette commande a &eacute;t&eacute; cr&eacute;dit&eacute;e gr�ce � vos Opti-Points! Cette demande de cr&eacute;dit n\'est pas 
couverte selon les politiques de garanties limit&eacute;es de LensNet Club.';		
			echo "<br><br>Raison: $Data[optipoints_reason]". "<br>Nb de points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}else{
			echo '<td colspan="2"><p style="font-family:Arial;">Your credit request cannot be covered under the terms of the Limited Warranty as a manufacturer\'s 
defect. However, we have covered your request under your available Opti-Points.';			
			echo "<br><br>Reason: $Data[optipoints_reason]". "<br>Number of points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}
    }  ?>
    
<?php echo '<br><br>'.  $message;  ?>
    
<?php 
$queryCustomerEmail  = "SELECT email, user_id FROM accounts WHERE user_id = (SELECT mcred_acct_user_id FROM memo_credits WHERE mcred_memo_num = '$Data[mcred_memo_num]')";
$resultCustomerEmail = mysqli_query($con,$queryCustomerEmail)	or die  ('I cannot select items because 8 : ' . mysqli_error($con) . '<br>'.$queryCustomerEmail);	
$DataCustomerEmail   = mysqli_fetch_array($resultCustomerEmail,MYSQLI_ASSOC);
$customerEmail       = $DataCustomerEmail[email];
$user_id      		 = $DataCustomerEmail[user_id];
//Send the email TO THE CUSTOMER
$send_to_address = str_split($customerEmail,100);
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Memo Credit Customer Copy/ Memo credit copie client";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
if ($response) 
echo '<br><b>Email envoy&eacute; avec succ&egrave;s au client &agrave; l\'adresse: '.$customerEmail. '</b>';
else
echo '<br>Erreur durant l\'envoie du email au client: '. $customerEmail;




//Send the email TO ORDERSRCO
$send_to_address=array('dbeaulieu@direct-lens.com');	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Memo Credit Administration Copy";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
if ($response) 
echo '<br><b>Copie du Email envoy&eacute; avec succ&egrave;s &agrave; l\'adresse:  dbeaulieu@direct-lens.com</b>';
else
echo '<br>Erreur durant l\'envoie de la copie du email dbeaulieu@direct-lens.com';

$CopieSylvie = 'no';//Par default a non
$CopieEric   = 'no';//Par default a non
switch($user_id){
	//Trois-rivieres
	case 'entrepotifc'		:  		 $CopieSylvie = 'no';  break;
	case 'entrepotframes'	:  		 $CopieSylvie = 'no';  break;
	case 'entrepotsafe'	    :	 	 $CopieSylvie = 'no';  break;
	//Drummondville		
	case 'entrepotdr'		:  		 $CopieSylvie = 'no';  break;
	case 'entrepotdrframes' :  		 $CopieSylvie = 'no';  break;	
	case 'safedr'		    :  		 $CopieSylvie = 'no';  break;	
	//Laval
	case 'laval'			 : 	     $CopieSylvie = 'no';  break;
	case 'entrepotlavalframe':  	 $CopieSylvie = 'no';  break;
	case 'lavalsafe'		 :  	 $CopieSylvie = 'no';  break;
	//Terrebonne	
	case 'terrebonne'			  :  $CopieSylvie = 'no';  break;
	case 'entrepotterrebonneframe':  $CopieSylvie = 'no';  break;
	case 'terrebonnesafe'		  :  $CopieSylvie = 'no';  break;
	//Sherbrooke
	case 'sherbrooke'			  :  $CopieSylvie = 'no';  break;	
	case 'entrepotsherbrookeframe':  $CopieSylvie = 'no';  break;
	case 'sherbrookesafe'		  :  $CopieSylvie = 'no';  break;
	//Chicoutimi
	case 'chicoutimi'			  :  $CopieSylvie = 'no';  break;	
	case 'chicoutimisafe'		  :  $CopieSylvie = 'no';  break;
	//Longueuil
	case 'longueuil'			  :  $CopieSylvie = 'no';  break;	
	case 'longueuilsafe'		  :  $CopieSylvie = 'no';  break;
	//Granby
	case 'granby'			      :  $CopieSylvie = 'no';  break;	
	case 'granbysafe'		      :  $CopieSylvie = 'no';  break;
	//Qu�bec
	case 'entrepotquebec'		  :  $CopieSylvie = 'no';  break;
	case 'quebecsafe'		      :  $CopieSylvie = 'no';  break;
	//ENTREPOTS ANGLOPHONES
	//Halifax
	case 'warehousehal' 	 :  	 $CopieEric = 'no';  break;	
	case 'warehousehalframes':   	 $CopieEric = 'no';  break;
	case 'warehousehalsafe'  :   	 $CopieEric = 'no';  break;				
}



/*
if ($CopieEric == 'yes'){//Copie Pour Eric (entrepots  STC et Halifax seulement)
	$send_to_address=array('commis@entrepotdelalunette.com');	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject="Copie d'un credit EDLL: " . $Data[mcred_memo_num];
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	if ($response) 
	echo '<br><b>Copie du Email envoy&eacute; avec succ&egrave;s &agrave; l\'adresse:  commis@entrepotdelalunette.com</b>';
	else
	echo '<br>Erreur durant l\'envoie de la copie du email  a commis@entrepotdelalunette.com';
}*/

?>  
    </tr>
    </table>
</body>
</html>

     
<?php /*?>//6- Link to print the credit Commented to send an email instead of printing the credit after approval<?php */?>

			<?php /*?><script type="text/javascript">
			window.open( "/admin/print_credit.php?memo_num=<?php echo $mcred_memo_num?>" )
			</script><?php */?>
            
    <p><br><a href="credit_status_update_detail.php?mcred_memo_num=<?php echo $mcred_memo_num ;?>">Credit <?php echo $mcred_memo_num; ?> approved</a></p> 	
<?php           
$adresse = "credit_status_update_detail.php?mcred_memo_num=". $mcred_memo_num;
//Rediriger dans la page de detail du credit
header("Location:".$adresse);          
?>         		
</table>
</form>     
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>