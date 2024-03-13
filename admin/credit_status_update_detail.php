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
//Provient de la recherche
if ($_REQUEST[mcred_memo_num] <> ''){
	$mcred_memo_num = mysqli_escape_string($con,$_REQUEST[mcred_memo_num]);
	$order_num      = substr($mcred_memo_num,1,7);
}else{
//Provient du menu déroulant
$mcred_memo_num = mysqli_escape_string($con,$_POST[mcred_order_num]);
$order_num      = substr($mcred_memo_num,1,7);			
}

$queryCreditFinished   = "SELECT mcred_approbation FROM memo_credits_temp WHERE mcred_memo_num = '$mcred_memo_num'";
$resultCreditFinished  = mysqli_query($con,$queryCreditFinished)		or die ('Could not check already credited because: ' . mysqli_error($con));
$DataCreditFinished    = mysqli_fetch_array($resultCreditFinished);
//echo 'Status actuel: '. $DataCreditFinished[mcred_approbation];
$EmpecherModification = false;
$statusActuel         = '';

if ($DataCreditFinished[mcred_approbation] == 'refused'){
	$EmpecherModification = true;
	$statusActuel		  = "Refused";
}

if ($DataCreditFinished[mcred_approbation] == 'approved'){
	$EmpecherModification = true;
	$statusActuel 		  = "Approved";
}
?>
<html><strong></strong>
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
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Credit Status Update</font></b></td>
            	</tr>
  
<?php   
$queryOrder   = "SELECT lab, order_num ,company, order_total, order_date_processed, order_date_shipped,accounts.user_id FROM orders, accounts WHERE orders.user_id = accounts.user_id AND order_num = $order_num";
$resultOrder  = mysqli_query($con,$queryOrder)		or die ('Could not check already credited because: ' . mysqli_error($con));
$DataOrder    = mysqli_fetch_array($resultOrder,MYSQLI_ASSOC);

$queryCredit   = "SELECT * FROM memo_credits_temp WHERE  mcred_memo_num = '$mcred_memo_num'";
$resultCredit  = mysqli_query($con,$queryCredit)		or die ('Could not check already credited because: ' . mysqli_error($con));
$DataCredit    = mysqli_fetch_array($resultCredit,MYSQLI_ASSOC);

$queryCreditDejaEmis   = "SELECT * FROM memo_credits WHERE  mcred_order_num = $order_num";
$resultCreditDejaEmis  = mysqli_query($con,$queryCreditDejaEmis)		or die ('Could not check already credited because: ' . mysqli_error($con));
$nbrResult             = mysqli_num_rows($resultCreditDejaEmis);

$queryCreditHistory    = "SELECT * FROM memo_credits_status_history  WHERE  mcred_memo_num = '$mcred_memo_num' order by update_time";
$resultCreditHistory   = mysqli_query($con,$queryCreditHistory)		or die ('Could not check already credited because: ' . mysqli_error($con));

$querySoldePoints      = "SELECT  lnc_reward_points   FROM accounts WHERE accounts.user_id='$DataOrder[user_id]' ";
$resultSoldePoints     = mysqli_query($con,$querySoldePoints)		or die ('Could not check already credited 2 because: ' . mysqli_error($con));
$DataSoldePoints       = mysqli_fetch_array($resultSoldePoints,MYSQLI_ASSOC);
$BalanceOptipoints     = $DataSoldePoints[lnc_reward_points];

$queryMemoCode         = "SELECT * FROM memo_codes WHERE mc_lab = $DataOrder[lab] and memo_code = '$DataCredit[mcred_memo_code]'";
$resultMemoCode        = mysqli_query($con,$queryMemoCode)		or die ('Could not check already credited 2 because: ' . mysqli_error($con));
$DataMemoCode          = mysqli_fetch_array($resultMemoCode,MYSQLI_ASSOC);



//Validations
$todayDate = date("Y-m-d");// current date
$currentTime = time($todayDate); //Change date into time
$timeAfterOneHour = $currentTime;
$datecomplete = date("Y-m-d",$timeAfterOneHour);

$erreur 	 	 = false;
$montantzero 	 = false;
$PasRaisonCredit = false;
$PasassezOptipoints=false;
//Si montant du credit est a 0
if ($DataCredit[mcred_abs_amount] <= 0){
//echo '<br>Montant a 0 ou negatif';
$montantzero = true;
$erreur	     = true;
}

//Si aucune raison de crédit n'est sélectionné
if ($DataCredit[mcred_memo_code] <= 0){
//echo '<br>Pas de justification pour le credit';
$PasRaisonCredit = true;
$erreur          = true;
}

//Solde optipoint inférieux au nombre a soustraire
if ($BalanceOptipoints < $DataCredit[optipoints_to_substract]){
//echo '<br>Pas assez d\'Opti-Points dans le compte';
$PasassezOptipoints  = true;
$erreur              = true;
}

//Si le credit a une date inférieur a la date du jour ou 0000-00-00
if (($DataCredit[mcred_date] < $datecomplete) || ($DataCredit[mcred_date] == '0000-00-00')){
//echo '<br>Date du credit incorrecte';
$DateIncorrecte    = true;
$erreur            = true;
}


?> 
                
<tr><td>&nbsp;</td></tr>                
 
 
 
<?php if ($nbrResult > 0 ){  ?>
<table width="85%" border="1" cellpadding="2" cellspacing="0" class="formField">              
   <tr bgcolor="#FF9900"><td  align="center" colspan="6"><h2>Credit <b>already given</b> on this order</h2></td></tr>
   
   <tr>   
       <th width="70px">Memo Credit Number</th>
       <th width="60px">Order Number</th>
       <th width="40px">Date</th>
       <th width="40px">Amount</th>
       <th width="300px">Detail</th>
       <th width="60px">Patient</th>
   </tr>
   
   <?php while ($DataCreditDejaEmis = mysqli_fetch_array($resultCreditDejaEmis,MYSQLI_ASSOC)){   ?>
            <tr>
                <td align="center"><?php echo $DataCreditDejaEmis[mcred_memo_num]; ?></td>
                <td align="center"><?php echo $DataCreditDejaEmis[mcred_order_num]; ?></td>
                <td align="center"><?php echo $DataCreditDejaEmis[mcred_date]; ?></td>
                <td align="center"><?php echo $DataCreditDejaEmis[mcred_abs_amount]; ?>$</td>
                <td align="center"><?php echo $DataCreditDejaEmis[mcred_detail]; ?>&nbsp;</td>
                <td align="center"><?php echo $DataCreditDejaEmis[patient_first_name] . ' ' . $DataCreditDejaEmis[patient_last_name]; ?>&nbsp;</td>
            </tr>
   <?php } //End while ?>       
</table> 
<br><br><br><br>
<?php }else{  ?> 
<table width="85%" border="1" cellpadding="2" cellspacing="0" class="formField">  
<tr><td bgcolor="#FF9900"><h2>Credit <b>Already given</b> on this order</h2></td></tr>
<tr><td><b>No credit were given for this order.</b></td></tr>
</table>
<br><br><br><br>
<?php }//End if result > 0 ?> 

  
  
  
    
<table width="85%" border="1" cellpadding="2" cellspacing="0" class="formField">  
    <tr bgcolor="#00CCFF"><th align="center" colspan="5"><h2>Order detail</h2></th></tr>
    
    <tr>
    <th width="65px">Order number</th>
    <th width="65px">Company</th>
    <th width="65px">Order total</th>
    <th width="45px">Date ordered</th>
    <th width="45px">Date shipped</th>
    </tr>


<tr>
    <td align="center"><?php echo $DataOrder[order_num]; ?></td>
    <td align="center"><?php echo $DataOrder[company]; ?></td>
    <td align="center"><?php echo $DataOrder[order_total]; ?></td>
    <td align="center"><?php echo $DataOrder[order_date_processed]; ?></td>
    <td align="center"><?php echo $DataOrder[order_date_shipped]; ?></td>
</tr>
</table>     

  <br><br>
  
   <?php if ($statusActuel =='Approved') 
   //echo '<a href="/admin/print_credit.php?memo_num="'. $mcred_memo_num.'>Print Credit </a>'; ?>
  
  
    <?php  if ($statusActuel =='Approved')  { ?>
    <script type="text/javascript">
	window.open( "/admin/print_credit.php?memo_num=<?php echo $mcred_memo_num?>" )
	</script>
	<?php } ?>
  
  
  <?php if ($statusActuel <> '') echo '<h2><div width="800px" style="color:#F00"  align="center">Credit:'.$statusActuel. '</div></h2>'; ?>
  <?php if ($erreur) echo '<h3><div width="800px" style="color:#F00"  align="center"><u>You need to correct all errors in red before updating the status</u></div></h3>'; ?>
  <br><br>
  
  
  
<table width="85%" border="1" cellpadding="2" cellspacing="0" class="formField">  
<form  method="post" name="edit_credit_request" id="edit_credit_request" method="post" action="edit_credit_request.php">                
<tr bgcolor="#999999"><th align="center" colspan="9"><h2>The <u>NEW</u> credit request detail</h2></th></tr> 

<tr>
    <th bgcolor="#CCCCCC" width="165px">Memo credit order number</th>
    <th bgcolor="#CCCCCC" width="165px">Order number</th>
    <th bgcolor="#CCCCCC">Date</th>
    <th bgcolor="#CCCCCC" width="95px">Credit amount</th>
    
    <th bgcolor="#CCCCCC">Patient</th>
    <th bgcolor="#CCCCCC">Ref num</th>
    <th bgcolor="#CCCCCC">Optipoints</th>
    <th bgcolor="#CCCCCC">Optipoints reason</th>
</tr>

<input type="hidden" name="mcred_memo_num" id="mcred_memo_num" value="<?php echo $DataCredit[mcred_memo_num]; ?>" />   
<tr>
    <td align="center"><?php echo $DataCredit[mcred_memo_num]; ?></td>
    <td align="center"><?php echo $DataCredit[mcred_order_num]; ?></td>
    <td <?php if($DateIncorrecte) echo 'style="color:#F00;"'; ?> align="center"><?php echo $DataCredit[mcred_date]; ?>&nbsp;</td>
    <td <?php if($montantzero) echo 'style="color:#F00;"'; ?> align="center"><?php if($montantzero) echo '<b>CREDIT AMOUNT CANNOT BE AT 0$</b>';else echo $DataCredit[mcred_abs_amount] ?>$</td>
    <td align="center"><?php echo $DataCredit[patient_first_name] . ' ' . $DataCredit[patient_last_name]; ?>&nbsp;</td>
    <td align="center"><?php echo $DataCredit[pat_ref_num]; ?>&nbsp;</td>
    <td <?php if($PasassezOptipoints) echo 'style="color:#F00;"'; ?> align="center"><?php echo $DataCredit[optipoints_to_substract]; ?>&nbsp;</td>
    <td <?php if($PasassezOptipoints) echo 'style="color:#F00;"'; ?> align="center"><?php if($PasassezOptipoints) echo '<b>NOT ENOUGH OPTI-POINTS</b>';else echo $DataCredit[optipoints_reason]; ?>&nbsp;</td>
   
</tr>

<tr>
    <th bgcolor="#CCCCCC" width="165px">Credit code</th>
    <th bgcolor="#CCCCCC" width="295px">Credit reason</th>
    <th bgcolor="#CCCCCC" colspan="6"> Credit detail</th>
</tr>

<tr>
    <td <?php if($PasRaisonCredit) echo 'style="color:#F00;"'; ?> align="center"><?php echo $DataCredit[mcred_memo_code]; ?> <?php if($PasRaisonCredit) echo '<b>NO CREDIT REASON SELECTED</b>'; ?>&nbsp;</td>
    <td <?php if($PasRaisonCredit) echo 'style="color:#F00;"'; ?> align="center"><?php echo $DataMemoCode[mc_description]; ?> <?php if($PasRaisonCredit) echo '<b>NO CREDIT REASON SELECTED</b>'; ?>&nbsp;</td>
    <td bordercolor="#FFFFFF" colspan="6" align="center"><?php echo $DataCredit[mcred_detail]; ?>&nbsp;</td>
</tr>

<tr>
<td bgcolor="#FFFF00" width="300px"><div align="center"><input  type="submit" <?php if ($EmpecherModification) echo 'disabled';?>  name="submit" value="Edit credit request"/></div></td>
</form>

<form  method="post" name="approve_credit" id="approve_credit" method="post" action="approve_credit.php">       
<input type="hidden" name="mcred_memo_num" id="mcred_memo_num" value="<?php echo $DataCredit[mcred_memo_num]; ?>" />
<td colspan="2" bgcolor="#99FF00" width="300px"><div align="center"><input type="submit" name="submit" <?php if ($erreur) echo 'disabled';  ?> <?php if ($EmpecherModification) echo 'disabled';?>  value="Approve"/></div></td>
</form>

<form  method="post" name="refuse_credit" id="refuse_credit" method="post" action="refuse_credit.php">       
<input type="hidden" name="mcred_memo_num" id="mcred_memo_num" value="<?php echo $DataCredit[mcred_memo_num]; ?>" />
<td colspan="5" bgcolor="#FF0000" width="300px"><div align="center">

<select <?php if ($erreur) echo 'disabled';  ?> <?php if ($EmpecherModification) echo 'disabled';?> id="refused_reason" name="refused_reason" class="formField">
				<?php $codeQuery="SELECT * FROM credit_refuse_reasons"; //get lab's memo codes
					  $codeResult=mysqli_query($con,$codeQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
					  while($codeData=mysqli_fetch_array($codeResult,MYSQLI_ASSOC)){
								echo "<option value=\"$codeData[reason] $codeData[description]\"";
								echo ">$codeData[reason] - $codeData[description]</option>";
							}
				?>
</select>

<input type="submit" name="submit" <?php if ($erreur) echo 'disabled';  ?> <?php if ($EmpecherModification) echo 'disabled';?> value="Refuse"/></div></td>
</form>

</tr>

</table>

 

<br><br>

<table width="85%" border="1" cellpadding="2" cellspacing="0" class="formField">              
   <tr bgcolor="#999999"><td  align="center" colspan="4"><h2>Status history of the <u>NEW</u> credit request</h2></td></tr>
   
   <tr>   
       <th width="150px">Status</th>
       <th width="40px">Update time</th>
       <th width="100px">IP</th>
   </tr>
   
   <?php while ($DataCreditHistory = mysqli_fetch_array($resultCreditHistory,MYSQLI_ASSOC)){   ?>
            <tr>
                <td align="center"><?php echo $DataCreditHistory[request_status]; ?>&nbsp;</td>
                <td align="center"><?php echo $DataCreditHistory[update_time]; ?>&nbsp;</td>
                <td align="center"><?php echo $DataCreditHistory[update_ip]; ?>&nbsp;</td>
            </tr>
   <?php } //End while ?>       
</table> 



<p>Customer Opti-Points balance:  <b><?php echo $BalanceOptipoints ;  ?></b> Opti-Points</p>
<br><br><br><p align=\"center\"><a href="credit_search.php">Back to credit search</a></p>
			
                		
</table>
</form>
        
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>