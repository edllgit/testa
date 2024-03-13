<?php
ob_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
//Provient de la recherche
if ($_POST[mcred_memo_num] <> ''){
	$mcred_memo_num = mysql_escape_string($_REQUEST[mcred_memo_num]);
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
<form  method="post" name="verification" id="verification" action="edit_credit_request_eagle.php">
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

$queryApprove= "INSERT INTO memo_credits_status_history  (mcred_memo_num, 	order_num, 	request_status, request_status_fr,	update_time, 	update_type, 	update_ip, 	access_id)
												   VALUES('$mcred_memo_num',  $order_num, 'Approved','Approuvé',  '$datecomplete','$update_type',   '$ip',     $acces_id )";
$resultApprove=mysql_query($queryApprove)		or die ('Could not insert because: ' . mysql_error()); 	 
  
  
//Select information from the credit request
$query="SELECT * from memo_credits_temp WHERE  mcred_memo_num ='$mcred_memo_num'";
$result=mysql_query($query)		or die ("Could not create new product because 1 " . mysql_error() );
$DataAapprouver=mysql_fetch_array($result);

//Get the PK of the  lab that request the credit
$queryEmail="SELECT lab  FROM orders WHERE order_num  = $DataAapprouver[mcred_order_num]";
$resultEmail=mysql_query($queryEmail)		or die ("Could not create new product because 2  " . mysql_error()  );
$DataEmail=mysql_fetch_array($resultEmail);


//2- SI IL Y EN A, SUPPRIMER / METTRE A JOUR LES OPTI-POINTS
//first if there are optipoints to save in the history we insert it
			if ($DataAapprouver[optipoints_to_substract] > 0)
			{
				$queryMemoCode = "SELECT mc_description from memo_codes WHERE memo_code = $DataAapprouver[mcred_memo_code] and mc_lab= $DataEmail[lab]";
				$resultMemoCode=mysql_query($queryMemoCode)		or die ("Could not create new product because3  " . mysql_error() );
				$DataMemoCode=mysql_fetch_array($resultMemoCode);
				$Memo_Code = $DataMemoCode['mc_description'];
				//Insert in lnc history to keep a trace
				$QueryInsertRewardHistory= "INSERT INTO lnc_reward_history (access_id,detail,amount,datetime,user_id) VALUES(14,'$DataAapprouver[mcred_order_num]: $DataAapprouver[optipoints_reason]','-$DataAapprouver[optipoints_to_substract]', 					                '$datecomplete','$DataAapprouver[mcred_acct_user_id]')";
				$resultInsert=mysql_query($QueryInsertRewardHistory)		or die ("Could not create new product because 4" . mysql_error() );
				//Then Optipoints needs to be deducted from the customer accounts  
				$queryDetail = "SELECT lnc_reward_points from accounts WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
				$resultDetail=mysql_query($queryDetail)		or die ("Could not create new product because5  " . mysql_error() );
				$DataDetail=mysql_fetch_array($resultDetail);
				$ActualPointBalance = $DataDetail[lnc_reward_points];
				$NewPointBalance = $ActualPointBalance - $DataAapprouver[optipoints_to_substract];		
				$queryUpdateBalance = "UPDATE accounts SET lnc_reward_points = $NewPointBalance  WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
				$resultBalance=mysql_query($queryUpdateBalance)		or die ("Could not create new product because 6 " . mysql_error() );
			}//end if
	
			
//3- Deplacer le memo credit de la table memo_credits_temp vers la table memo_credits
	    $tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$datedujour = date("Y-m-d", $tomorrow);
		//Insert the credit request into credit table because it has been approved
		$query="INSERT INTO memo_credits(mcred_acct_user_id, mcred_order_num, pat_ref_num, patient_first_name, patient_last_name, mcred_memo_num, mcred_cred_type, mcred_disc_type, mcred_amount, mcred_abs_amount, mcred_memo_code, mcred_date,             date_mc_applied,optipoints_to_substract,optipoints_reason, mcred_detail) 
			VALUES (
			'$DataAapprouver[mcred_acct_user_id]',		'$DataAapprouver[mcred_order_num]',
			'$DataAapprouver[pat_ref_num]',				'$DataAapprouver[patient_first_name]',
			'$DataAapprouver[patient_last_name]',		'$DataAapprouver[mcred_memo_num]',
			'$DataAapprouver[mcred_cred_type]',			'$DataAapprouver[mcred_disc_type]',
			'$DataAapprouver[mcred_amount]',			'$DataAapprouver[mcred_abs_amount]',
			'$DataAapprouver[mcred_memo_code]',			'$DataAapprouver[mcred_date]',
			'$DataAapprouver[date_mc_applied]',			'$DataAapprouver[optipoints_to_substract]',
			'$DataAapprouver[optipoints_reason]', 		'$DataAapprouver[mcred_detail]')";
			$result=mysql_query($query)		or die ("Could not create new product because 7 ".$query . mysql_error() );
          
//4- 	//Delete from temp table because it has been inserted in memo credit table
		$queryDelete="UPDATE memo_credits_temp  SET  mcred_approbation  = 'approved' WHERE  mcred_memo_num ='$mcred_memo_num'";
		$resultDelete=mysql_query($queryDelete)		or die ("Could not delete temporary memo credit" . mysql_error()  );	
		 
//5- Link to print the credit
			?>
			<script type="text/javascript">
			window.open( "/admin/print_credit.php?memo_num=<?php echo $mcred_memo_num?>" )
			</script>
            <p><br><a href="credit_status_update_detail_eagle.php?mcred_memo_num=<?php echo$mcred_memo_num ;?>">Credit <?php echo $mcred_memo_num; ?> approved</a></p> 	
<?php           
$adresse = "credit_status_update_detail_eagle.php?mcred_memo_num=". $mcred_memo_num;
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