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
include("admin_functions.inc.php");


session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

if ($_POST[mcred_memo_num] <> ''){
	 $mcred_memo_num 	  = mysqli_escape_string($con,$_POST[mcred_memo_num]);
	 $order_num      	  = substr($mcred_memo_num,1,7);	
	
	 $queryRequestDetail  = "SELECT * FROM memo_credits_temp WHERE mcred_memo_num = '$mcred_memo_num' ";
	 $resultRequestDetail = mysqli_query($con,$queryRequestDetail)		or die ('Could not check already credited because: ' . mysqli_error($con));
	 $DataCredit	      = mysqli_fetch_array($resultRequestDetail,MYSQLI_ASSOC); 
	 
	 $queryOrder  		  = "SELECT * FROM orders WHERE order_num = $order_num";
	 $resultOrder 		  = mysqli_query($con,$queryOrder)		or die ('Could not check already credited because: ' . mysqli_error($con));
	 $DataOrder	          = mysqli_fetch_array($resultOrder,MYSQLI_ASSOC); 
	 
	 $queryAccount 		  = "SELECT * FROM accounts WHERE user_id = '$DataOrder[user_id]'";
	 $resultAccount 	  = mysqli_query($con,$queryAccount)		or die ('Could not check already credited because: ' . mysqli_error($con));
	 $DataAccount         = mysqli_fetch_array($resultAccount,MYSQLI_ASSOC);
	 
	 
	 if ($_POST[update_credit]=="Save"){
     //On met a jour la demande de crédit dans memo_credits_temp
	 //6 champs possible de mettre a jour: mcred_Date, mcred_abs_amount, mcred_memo_code, mcred_detail, optipoints_to_substract, optipoints_reason
	 $todayDate = date("Y-m-d g:i a");// current date
	 $currentTime = time($todayDate); //Change date into time
	 $timeAfterOneHour = $currentTime;
	 $datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	 $ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	 $acces_id = $_SESSION["access_admin_id"];
	 
		 //1- mcred_date
		 if ($DataCredit[mcred_date] <> $_POST[mcred_date]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET mcred_date = '$_POST[mcred_date]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_date because: ' . mysqli_error($con));
		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status,  request_status_fr,                                                        update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Credit date updated from <b>$DataCredit[mcred_date]</b> to  <b>$_POST[mcred_date]</b>', 'Date du crédit modifié de <b>$DataCredit[mcred_date]</b> a <b>$_POST[mcred_date]</b>',     '$datecomplete',        'manual',     '$ip',     $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_date because: ' . mysqli_error($con));
		 }
		 
		 
		  //2- mcred_abs_amount
		 if ($DataCredit[mcred_abs_amount] <> $_POST[mcred_abs_amount]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET mcred_abs_amount = '$_POST[mcred_abs_amount]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_abs_amount because: ' . mysqli_error($con));

		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status,  request_status_fr,                                                               update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Credit amount updated from <b>$DataCredit[mcred_abs_amount]$</b> to  <b>$_POST[mcred_abs_amount]$</b>', 'Montant du crédit mis à jour de <b>$DataCredit[mcred_abs_amount]$</b> a  <b>$_POST[mcred_abs_amount]$</b>',    '$datecomplete',        'manual',     '$ip',     $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_abs_amount because: ' . mysqli_error($con));
		 }
		 
		 
		   //3- mcred_memo_code
		 if ($DataCredit[mcred_memo_code] <> $_POST[mcred_memo_code]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET mcred_memo_code = '$_POST[mcred_memo_code]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status, request_status_fr,                                                               update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Credit reason updated from <b>$DataCredit[mcred_memo_code]</b> to  <b>$_POST[mcred_memo_code]</b>',  'Raison du crédit mis a jour de <b>$DataCredit[mcred_memo_code]</b> a <b>$_POST[mcred_memo_code]</b>',    '$datecomplete',        'manual',     '$ip',     $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 }
		 
		 //4- mcred_detail
		 if ($DataCredit[mcred_detail] <> $_POST[mcred_detail]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET mcred_detail = '$_POST[mcred_detail]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status,  request_status_fr,                                                              update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Credit detail updated from  <b>[$DataCredit[mcred_detail]]</b> to  <b>[$_POST[mcred_detail]]</b>', 'Le détail du crédit est mis a jour de <b>[$DataCredit[mcred_detail]]</b> a  <b>[$_POST[mcred_detail]]</b>',    '$datecomplete',        'manual',     '$ip',     $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 }
		 
		 
		  //5- optipoints_to_substract
		 if ($DataCredit[optipoints_to_substract] <> $_POST[optipoints_to_substract]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET optipoints_to_substract = '$_POST[optipoints_to_substract]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status,   request_status_fr,                                                               update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Optipoints to substract updated from <b>$DataCredit[optipoints_to_substract]</b> to  <b>$_POST[optipoints_to_substract]</b>', 'Nombre d\'optipoints a soustraire mis a jour de <b>$DataCredit[optipoints_to_substract]</b> a  <b>$_POST[optipoints_to_substract]</b>', '$datecomplete','manual',  '$ip',  $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 }
		 
		  //6- optipoints_reason
		 if ($DataCredit[optipoints_reason] <> $_POST[optipoints_reason]){
		 $queryUpdateDate = "UPDATE memo_credits_temp SET optipoints_reason = '$_POST[optipoints_reason]' WHERE mcred_memo_num = '$mcred_memo_num' ";
		 $resultUpdateDate = mysqli_query($con,$queryUpdateDate)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 $queryHistory = "INSERT INTO  memo_credits_status_history 
		   (mcred_memo_num,       order_num,      request_status,  request_status_fr,                                                               update_time,        update_type,  update_ip,   access_id)
	VALUES ('$mcred_memo_num',   $order_num,    'Optipoints reason updated from <b>[$DataCredit[optipoints_reason]]</b> to  <b>[$_POST[optipoints_reason]]</b>', 'La raison de suppression des Opti-Points est mis a jour de m <b>[$DataCredit[optipoints_reason]]</b> a  <b>[$_POST[optipoints_reason]]</b>', '$datecomplete','manual',  '$ip',  $acces_id)";
		 $resultUpdateDate = mysqli_query($con,$queryHistory)		or die ('Could not update mcred_memo_code because: ' . mysqli_error($con));
		 }
		 
//Redirection automatique vers credit_status_update_detail.php avec le mcredordernum en parametre (request)
$adresse = "credit_status_update_detail.php?mcred_memo_num=". $mcred_memo_num;
//Rediriger dans la page de detail du credit
header("Location:".$adresse);
		 
?>
<form id="redirect" action="edit_credit_request.php" method="post">
	<input type="hidden" name="mcred_memo_num" value="<?php echo $mcred_memo_num; ?>">
</form>
<script>
    document.getElementById('redirect').submit();
</script>
	 
<?php	 
	 }
	 
	 
	  
}else{
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
<form name="update_credit_request" id="update_credit_request" method="post" action="edit_credit_request.php">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php include("adminNav.php");?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
        
        <table width="90%" border="1">	
            <tr>
               <td width="265px">Customer:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <b><?php echo  $DataAccount[company] . '</b> #' .  $DataAccount[account_num];?></td>
               <td width="125px">Order Number:   <b><?php echo  $DataCredit[mcred_order_num];?></b></td>
                
            </tr>
            
            
            
              <tr><td></td></tr><tr><td></td></tr>
             
            <tr>
            <td width="265px">Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;<input type="text" name="mcred_date" 	   id="mcred_date"       size="8" value="<?php echo $DataCredit[mcred_date];?>"></td>
            	
            <td width="250px">Patient:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo  $DataOrder[order_patient_first] . ' ' .  $DataOrder[order_patient_last] . '  Ref:' .  $DataOrder[patient_ref_num];?></td>
            </tr>
            
            <tr><td></td></tr><tr><td></td></tr>
            
            <tr>
            <td width="250px">Order Total:   &nbsp;&nbsp;&nbsp;&nbsp; <?php echo  $DataOrder[order_total];?>$</td> 
            <td>Credit amount:&nbsp;
			<input type="text" name="mcred_abs_amount" 	   id="mcred_abs_amount"       size="8" value="<?php echo $DataCredit[mcred_abs_amount];?>">$</td>
            </tr>
            
            
           
        </table>
        
        
         <table width="90%" border="1">	
           <tr><td></td></tr><tr><td></td></tr>
            <tr>
                <td width="555px">Credit Reason:
                <select name="mcred_memo_code" class="formField">
				<?php $codeQuery="SELECT * FROM memo_codes WHERE active='yes' AND mc_lab= $DataOrder[lab] AND mc_primary_key NOT IN (48,58)"; //get lab's memo codes
					  $codeResult=mysqli_query($con,$codeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
					  while($codeData=mysqli_fetch_array($codeResult,MYSQLI_ASSOC)){
								$codeData[memo_code]=stripslashes($codeData[memo_code]);
								$codeData[mc_description]=stripslashes($codeData[mc_description]);
								echo "<option value=\"$codeData[memo_code]\"";
								if ($codeData[memo_code] == $DataCredit[mcred_memo_code])
								echo  ' selected';
								echo ">$codeData[memo_code] - $codeData[mc_description]</option>";
							}
				?>
				</select></td>
                <td>Credit Detail: <input type="text" name="mcred_detail"  id="mcred_detail"   size="37" value="<?php echo $DataCredit[mcred_detail];?>"></td>
            </tr>   
        </table>
        
        
        
         <table width="90%" border="1">	
           <tr><td></td></tr><tr><td></td></tr>
            <tr>
             <td width="320px">Opti-Points balance:   <b><?php echo  $DataAccount[lnc_reward_points];?></b></td>
                <td width="260px" >Opti-Points to substract:
                <input type="text" name="optipoints_to_substract"  id="optipoints_to_substract" size="9" value="<?php echo $DataCredit[optipoints_to_substract];?>"></td>
                <td width="345px">Opti-Points Reason: <input type="text" name="optipoints_reason"  id="optipoints_reason" size="30" value="<?php echo $DataCredit[optipoints_reason];?>"></td>
            </tr>
            
           <tr>
           <td align="center" colspan="3"><input  type="submit" name="update_credit" value="Save" </td>
           </tr>
        </table>
        <input type="hidden" name="mcred_memo_num" id="mcred_memo_num" value="<?php echo $mcred_memo_num; ?>">
        </form>
<p><a href="credit_status_update_detail.php?mcred_memo_num=<?php echo $mcred_memo_num ;?>">Back to update status page</a></p>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>