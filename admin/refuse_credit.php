<?php 
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");


session_start();


ob_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
//Provient de la recherche
if ($_POST[mcred_memo_num] <> ''){
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
//1- Enregistrer le changement de status (refused) dans memo_Credits_status_history
$todayDate 			= date("Y-m-d g:i a");// current date
$order_date_shipped = date("Y-m-d");// current date
$currentTime 	    = time($todayDate); //Change date into time
$timeAfterOneHour   = $currentTime;
$datecomplete 	    = date("Y-m-d H:i:s",$timeAfterOneHour);
$ip		  	  	    = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$acces_id           = $_SESSION["access_admin_id"];
$update_type 		= 'manual';
$refused_reason     = $_POST[refused_reason];

$queryApprove= "INSERT INTO memo_credits_status_history  (mcred_memo_num, 	order_num, 	request_status, request_status_fr,	update_time, 	update_type, 	update_ip, 	access_id)
												   VALUES('$mcred_memo_num',  $order_num, 'Refused: $refused_reason', 'Refus�: $refused_reason',  '$datecomplete','$update_type',   '$ip',     $acces_id )";
$resultApprove=mysqli_query($con,$queryApprove)		or die ('Could not insert because: ' . mysqli_error($con)); 	 
  
  	$OldMcred_memo_num = $mcred_memo_num;
	$NewMcred_Memo_Num = $mcred_memo_num . 'Z';

//4- 	//Update approbation status in  temp_table to REFUSED
		$queryRefused  = "UPDATE memo_credits_temp  SET  mcred_approbation  = 'refused', mcred_memo_num = '$NewMcred_Memo_Num' WHERE  mcred_memo_num ='$mcred_memo_num'";
		$resultRefused = mysqli_query($con,$queryRefused)		or die ("Could not delete temporary memo credit" . mysqli_error($con));	
		
$adresse = "credit_status_update_detail.php?mcred_memo_num=". $mcred_memo_num;
//Rediriger dans la page de detail du credit
header("Location:".$adresse);
exit;
		 
?>
                		
</table>
</form>
        
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>