 <?php 
 session_start();
 include("../Connections/sec_connect.inc.php");
  include('../includes/phpmailer_email_functions.inc.php');
 require_once('../includes/class.ses.php');					


if (isset($_POST[PostedForm]))//The form has been submitted
{
$OrderNumToUpdate = $_POST[the_order_num];
$result = count($OrderNumToUpdate);

if(isset($_POST["batch_approve"]))
$action = 'approve';
if(isset($_POST["batch_print"]))
$action = 'print';
if(isset($_POST["batch_refuse"]))
$action = 'refuse';

//Print batch credits
	if($action =='print')
	{
		foreach ($OrderNumToUpdate as &$value) {
			//echo '<br>contenu: '. $value . '<br>' ;	
			?> 
			<script type="text/javascript">
			window.open( "/admin/displayMemoCreditForm_admin.php?memo_num=<?php echo $value?>" )
			</script>
			<?php	
		}
	}
	

//Approve batch credits	
	if($action =='approve')
	{
		foreach ($OrderNumToUpdate as &$value) {
			$date1 = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$datecomplete = date("Y/m/d", $date1);
		
			//Select information from the credit request
			$query="SELECT * from memo_credits_temp WHERE  mcred_memo_num ='$value'";
			$result=mysql_query($query)		or die ("Could not create new product because 1 " . mysql_error() );
			$DataAapprouver=mysql_fetch_array($result);
			mysql_free_result($result);

			//Get the PK of the  lab that request the credit
			$queryEmail="SELECT lab  FROM orders WHERE order_num  = $DataAapprouver[mcred_order_num]";
			$resultEmail=mysql_query($queryEmail)		or die ("Could not create new product because  " . mysql_error()  );
			$DataEmail=mysql_fetch_array($resultEmail);
			
			switch($DataEmail[lab]){
				case "50":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Directlab Eagle
				case "47":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Ait Lens Club
				case "46":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Illinois
				case "43":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Pacific
				case "45":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Suisse
				case "44":	$Report_Email	= array('dbeaulieu@direct-lens.com');													break;//Lensnet Pacific
				
				case "41":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Directlab USA
				
				
				case "38":	$Report_Email	= array('dbeaulieu@direct-lens.com');																break;//Lensnet Club Afrique de l'Ouest
				case "1":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Vision Optics Technologies
				case "3":	$Report_Email	= array('followorderssct@direct-lens.com');													break;//Directlab St. Catharines
				case "21":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;// Directlab Trois-Rivieres
				case "22":	$Report_Email	= array('labo@direct-lens.com','dbeaulieu@direct-lens.com');									break;//Directlab Drummondville
				case "28":	$Report_Email	= array('lensnetqc@direct-lens.com','dbeaulieu@direct-lens.com');								break;//Lensnet Club QC
				case "29":	$Report_Email	= array('lensneton@direct-lens.com');															break;//Lensnet Club ON
				case "32":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club USA
				case "33":	$Report_Email	= array('lensnetatlantic@direct-lens.com');													break;//Lensnet Club Atlantic
				case "34":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club West
				case "32":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club USA
				default:	$Report_Email = array('dbeaulieu@direct-lens.com');															break;
			}

			//first if there are optipoints to save in the history we insert it
			if ($DataAapprouver[optipoints_to_substract] > 0)
			{
				$queryMemoCode = "SELECT mc_description from memo_codes WHERE memo_code = $DataAapprouver[mcred_memo_code] and mc_lab= $DataEmail[lab]";
				$resultMemoCode=mysql_query($queryMemoCode)		or die ("Could not create new product because  " . mysql_error() );
				$DataMemoCode=mysql_fetch_array($resultMemoCode);
				$Memo_Code = $DataMemoCode['mc_description'];
				//Insert in lnc history to keep a trace
				$QueryInsertRewardHistory= "INSERT INTO lnc_reward_history (access_id,detail,amount,datetime,user_id) VALUES(14,'$DataAapprouver[mcred_order_num]: $DataAapprouver[optipoints_reason]','-$DataAapprouver[optipoints_to_substract]', 					                '$datecomplete','$DataAapprouver[mcred_acct_user_id]')";
				$resultInsert=mysql_query($QueryInsertRewardHistory)		or die ("Could not create new product because " . mysql_error() );
				//Then Optipoints needs to be deducted from the customer accounts  
				$queryDetail = "SELECT lnc_reward_points from accounts WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
				$resultDetail=mysql_query($queryDetail)		or die ("Could not create new product because  " . mysql_error() );
				$DataDetail=mysql_fetch_array($resultDetail);
				$ActualPointBalance = $DataDetail[lnc_reward_points];
				$NewPointBalance = $ActualPointBalance - $DataAapprouver[optipoints_to_substract];		
				$queryUpdateBalance = "UPDATE accounts SET lnc_reward_points = $NewPointBalance  WHERE  user_id = '$DataAapprouver[mcred_acct_user_id]'";
				$resultBalance=mysql_query($queryUpdateBalance)		or die ("Could not create new product because  " . mysql_error() );
			}//end if
		
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
			'$DataAapprouver[mcred_memo_code]',			'$datedujour',
			'$DataAapprouver[date_mc_applied]',			'$DataAapprouver[optipoints_to_substract]',
			'$DataAapprouver[optipoints_reason]', 		'$DataAapprouver[mcred_detail]')";
			$result=mysql_query($query)		or die ("Could not create new product because  ".$query . mysql_error() );
		//Send the email to the lab that requested the credit to advise them  of the decison (APPROVED)
		//Email to warn about the memo credit that needs to be approved or refused
		$message="Your request for a credit on order $DataAapprouver[mcred_order_num] Account: $DataAapprouver[mcred_acct_user_id] of $DataAapprouver[mcred_abs_amount] $ that was made on  $DataAapprouver[mcred_date]  <br><br>   <b>Has been approved </b>. The credit  <b>will  now  </b> appear on the monthly statement of this customer.  Thanks";
		
		//We send the email
		$curTime= date("m-d-Y");	
		$to_address=$Report_Email;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject='Direct-Lens Credit Request Update: Approved';
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
		
		//Delete from temp table because it has been inserted in memo credit table
		$queryDelete="DELETE from memo_credits_temp WHERE  mcred_memo_num ='$value'";
		$resultDelete=mysql_query($queryDelete)		or die ("Could not delete temporary memo credit" . mysql_error()  );	
		
		}//end for each
	
	}//end if action = approved
	
	

	
//Refuse batch credits	
	if($action =='refuse')
	{
		foreach ($OrderNumToUpdate as &$value) 
		{
		
			$date1 = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$datecomplete = date("Y/m/d", $date1);
			//Select information from the credit request
			$query="SELECT * from memo_credits_temp WHERE  mcred_memo_num ='$value' and   mcred_approbation  NOT IN ('approve','approved','refuse','refused')";
			$result=mysql_query($query)		or die ("Could not create new product because 1 " . mysql_error() );
			$DataAapprouver=mysql_fetch_array($result);
			mysql_free_result($result);
			//Get the PK of the  lab that request the credit
			$queryEmail="SELECT lab  FROM orders WHERE order_num  = $DataAapprouver[mcred_order_num]";
			$resultEmail=mysql_query($queryEmail)		or die ("Could not create new product because  " . mysql_error()  );
			$DataEmail=mysql_fetch_array($resultEmail);
			
			switch($DataEmail[lab]){
				case "50":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Directlab Eagle
				case "47":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Ait Lens Club
				case "46":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Illinois
				case "43":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Pacific
				case "45":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Directlab Suisse
				case "44":	$Report_Email	= array('dbeaulieu@direct-lens.com');													break;//Lensnet Pacific
				
				case "41":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Directlab USA
				
				
				case "38":	$Report_Email	= array('dbeaulieu@direct-lens.com');																break;//Lensnet Club Afrique de l'Ouest
				case "1":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;//Vision Optics Technologies
				case "3":	$Report_Email	= array('followorderssct@direct-lens.com');													break;//Directlab St. Catharines
				case "21":	$Report_Email	= array('dbeaulieu@direct-lens.com');															break;// Directlab Trois-Rivieres
				case "22":	$Report_Email	= array('labo@direct-lens.com','dbeaulieu@direct-lens.com');									break;//Directlab Drummondville
				case "28":	$Report_Email	= array('lensnetqc@direct-lens.com','dbeaulieu@direct-lens.com');								break;//Lensnet Club QC
				case "29":	$Report_Email	= array('lensneton@direct-lens.com');															break;//Lensnet Club ON
				case "32":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club USA
				case "33":	$Report_Email	= array('lensnetatlantic@direct-lens.com');													break;//Lensnet Club Atlantic
				case "34":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club West
				case "32":	$Report_Email	= array('dbeaulieu@direct-lens.com');														break;//Lensnet Club USA
				default:	$Report_Email 	= array('dbeaulieu@direct-lens.com');															break;
			}
			
		//Send the email to the lab that requested the credit to advise them  of the decison (REFUSE)
		//Email to warn about the memo credit that needs to be approved or refused
		$message="Your request for a credit on order $DataAapprouver[mcred_order_num]  Account: $DataAapprouver[mcred_acct_user_id] of $DataAapprouver[mcred_abs_amount] $ that was made on  $DataAapprouver[mcred_date]  <br><br> <b>Has been refused</b>. The credit  <b>will not</b> appear on the monthly statement of this customer. Please contact Trois-rivieres customer service for more details. Thanks";
		//We send the email
		$curTime= date("m-d-Y");	
		$to_address=$Report_Email;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject='Direct-Lens Credit Request Update: Refused';
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
		
		
		//Credit refuser: on efface le memo credit de la table temp
		/*$queryDelete="DELETE from memo_credits_temp WHERE  mcred_memo_num ='$value'";
		$resultDelete=mysql_query($queryDelete)		or die ("Could not delete temporary memo credit" . mysql_error() );*/
		
		$queryDelete="UPDATE memo_credits_temp  SET mcred_approbation = 'refuse', mcred_approbation_date='$datecomplete'  WHERE  mcred_memo_num ='$value'";
		$resultDelete=mysql_query($queryDelete)		or die ("Could not delete temporary memo credit" . mysql_error() );

		}//end foreach
		
	}//end if action = refuse

}//END IF FORM IS POSTED (via submit buttons)
?>    
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
</head> 
<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
    
	<div><table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Memo credits waiting for approval</font></b></td>
       		    </tr>
            	<tr bgcolor="#DDDDDD">
            	<td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Order Num</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Lab</b></font></p></td>
            	<td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Compte</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Ref. Pat.</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Pat. Name</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Credit/Debit</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Amount</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Memo Code</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>OptiPoints</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Date</b></font></p></td>
           <?php /*?>     <td align="left"  nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Approve</b></font></p></td>
                <td align="left"  nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Refuse</b></font></p></td><?php */?>
               
            	</tr>
                <form  name="memo_credit_approval" id="memo_credit_approval" action="memo_credit_approval.php" method="post">
   <?php
	$QueryMemoCreditTemp="SELECT * FROM memo_credits_temp WHERE mcred_approbation <> 'refuse'";
	$ResultMemoCreditTemp=mysql_query($QueryMemoCreditTemp)	or die (  mysql_error() );
	while($DataMemoCreditTemp=mysql_fetch_array($ResultMemoCreditTemp)){
	$count++;
	if (($count%2)==0)
   		$bgcolor="#DDDDDD";
	else 
		$bgcolor="#FFFFFF";
				
	$queryLab="SELECT lab_name, lab_email, primary_key as lab_primary_key FROM labs WHERE primary_key = (SELECT distinct lab  FROM orders WHERE order_num  = $DataMemoCreditTemp[mcred_order_num]) ";
	$ResultLab=mysql_query($queryLab)	or die ( "Query failed 1: " . mysql_error() );				
	$DataLab=mysql_fetch_array($ResultLab);
	mysql_free_result($ResultLab);

	$queryCode="SELECT mc_description FROM memo_codes WHERE mc_lab = $DataLab[lab_primary_key] AND  memo_code = '$DataMemoCreditTemp[mcred_memo_code]' ";
	$ResultCode=mysql_query($queryCode)	or die ( "Query failed 2: " . mysql_error() );				
	$DataCode=mysql_fetch_array($ResultCode);
	mysql_free_result($ResultCode);

					echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					echo "<input name=\"the_order_num[$DataMemoCreditTemp[mcred_order_num]]]\" type=\"checkbox\"";
		
					if (isset($OrderNumToUpdate[$DataMemoCreditTemp[mcred_order_num]])){
					echo "checked='checked'";
					}
					
					echo "value=\"$DataMemoCreditTemp[mcred_memo_num]\">
					$DataMemoCreditTemp[mcred_order_num]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataLab[lab_name]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_acct_user_id]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[pat_ref_num]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[patient_first_name] $DataMemoCreditTemp[patient_last_name]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_cred_type]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_abs_amount]</td>
					<td  align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataCode[mc_description]</td>
					<td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					if( $DataMemoCreditTemp[optipoints_to_substract]> 0) echo $DataMemoCreditTemp[optipoints_to_substract];
					echo "</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_date]</td>";
					//<td align=\"left\"><font size=\"3\" face=\"Arial, Helvetica, sans-serif\"><a href=\"memo_credit_approval.php?pkey=$DataMemoCreditTemp[mcred_primary_key_temp]&approve=yes\">Approve</a></td>
					//<td align=\"left\"><font size=\"3\" face=\"Arial, Helvetica, sans-serif\"><a href=\"memo_credit_approval.php?pkey=$DataMemoCreditTemp[mcred_primary_key_temp]&approve=no\">Refuse</a></td>
					echo "</tr>";
					echo '<tr><td>&nbsp;</td></tr>';
				}
				echo "<tr>
				<td><input name=\"batch_approve\" value=\"Approve these credits\" id=\"batch_approve\" disabled type=\"submit\"";
				if($action != 'print')
				echo " disabled ";
				echo "></td>
				<td><input name=\"batch_refuse\"  value=\"Refuse these credits\"    id=\"batch_refuse\" disabled type=\"submit\"></td>
				<td><input name=\"batch_print\"   value=\"Print these credits\" 	id=\"batch_print\"   type=\"submit\"></td>
				</tr>";
				mysql_free_result($ResultMemoCreditTemp);
				?>
				<input type='hidden' name="PostedForm" id="PostedForm" value="yes" ></form></table></div>
</td>
</tr>
</table>
</body>
</html>