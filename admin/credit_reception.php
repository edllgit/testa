<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");
//include('../phpmailer_email_functions.inc.php');

session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if($_POST[rpt_search]=="Verify")
{
	
	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
	//On Cr�� l'array avec tous les order num qui ont �t� entr�s	

	$_POST[order_num] =  trim($_POST[order_num],"\n");
	$_POST[order_num] =  trim($_POST[order_num],"\r");
	$_POST[order_num] =  trim($_POST[order_num]," ");
	
	
	//Enlever la virgule de la fin s'il y en a une 
	if (substr($_POST["order_num"], -1) == ',') {
	$_POST["order_num"] = substr($_POST["order_num"],0,strlen($_POST["order_num"])-1);
	}

	$Array_OrderNum =  explode(",", $_POST["order_num"]);
	//Valider les num�ros de commandes pass�, longeur doit etre de 7, doit etre numeric
	$errorMessage = '';
	$PassValidation = true;
	$Array_OrderNum = array_filter(array_map('trim', $Array_OrderNum));
	
		foreach( $Array_OrderNum as $value ){
		$valueSansEspace = trim($value, " ");
		
			if (strlen($valueSansEspace)<> 7)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Invalid Order number (should be 7 caracters)";
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

			//Si la longeur est > a 7 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque num�ro
			if (($LongeurOrderNum >7) && ($CommaInOrderNum == '' )){
			$errorMessage .= "<br>Order numbers does not contain any comma (,)  please separate each order number with a comma";
			$PassValidation = false;
			}
			
		}//End for each
	  
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num]) 
			AND orders.user_id NOT IN ('garantieatoutcasser') GROUP BY order_num";
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
		
		//AJOUTER ESCAPE STRING afin  que les apostrophes ne soient plus  un probl�me
		$user_id    		 = mysqli_escape_string($con,$DataGetInfo[user_id]);
		$patient_ref_num     = mysqli_escape_string($con,$DataGetInfo[patient_ref_num]);
		$order_patient_first = mysqli_escape_string($con,$DataGetInfo[order_patient_first]);
		$order_patient_last  = mysqli_escape_string($con,$DataGetInfo[order_patient_last]);
		
		
		$querymemoCred  	= "SELECT count(mcred_primary_key) as nbrMemoCred FROM memo_credits WHERE mcred_order_num =  ". $the_order_num;
		$resultmemoCred 	= mysqli_query($con,$querymemoCred)		or die ('Could not check querymemoCred because: ' . mysqli_error($con));
		$DatamemoCred   	= mysqli_fetch_array($resultmemoCred,MYSQLI_ASSOC);
		$nbrMemoCred 	 	= $DatamemoCred[nbrMemoCred];
		
		$querymemoCredTemp  = "SELECT count(mcred_primary_key_temp) as nbrMemoCredTemp  FROM memo_credits_temp WHERE mcred_order_num =  ". $the_order_num;
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
		
		//We create the credit in memo_credits_temp table
		$InsertQuery = "INSERT INTO memo_credits_temp (
		mcred_acct_user_id,		mcred_order_num,		pat_ref_num,		patient_first_name,		patient_last_name,		mcred_memo_num,		mcred_cred_type,		mcred_date, mcred_approbation)
		VALUES (		'$user_id', 		$the_order_num, 		'$patient_ref_num', 		'$order_patient_first', '$order_patient_last',
		'$mcred_memo_num',		'credit',		'$datecomplete','pending')";		//Ces info viendront de la table ORDERS
		$UpdateDetails  .= "<br>Credit  request for order #$the_order_num has been set  to: Request received";
		$resultInsert=mysqli_query($con,$InsertQuery)		or die ('Could not insert because: ' . mysqli_error($con));

		//mcred_disc_type,   		//mcred_amount,
		//mcred_abs_amount,    		//mcred_memo_code,
		
		$todayDate = date("Y-m-d g:i a");// current date
		$ip	       = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		$acces_id  = $_SESSION["access_admin_id"];	
		
		//We add the credit status in credit_status_history table
		$InsertQueryHistory = "INSERT INTO memo_credits_status_history (
		 		 mcred_memo_num,       order_num,      request_status,   request_status_fr,   update_time,       update_type,  update_ip,  access_id)
		VALUES ('$mcred_memo_num', $the_order_num,    'Request received', 'Requ�te re�ue',     '$datecomplete',        'manual',     '$ip',     $acces_id)";
		
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
<script language='JavaScript'>
checked = false;
function checkedAll () {
if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('update_status').elements.length; i++) {
	document.getElementById('update_status').elements[i].checked = checked;
	}
}
</script>
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
<form  method="post" name="verification" id="verification" action="credit_reception.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Credit Request Reception</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your order numbers separated by a comma <br>(<strong>IMP:</strong> After the last order number, make sure there are no extra spaces or break line)
						<br><strong>P.S.</strong> No credits can be emitted on the account 'Garantieatoutcasser'</div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                		
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="credit_reception.php">
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysqli_query($con,$rptQuery)		or die  ('<strong>Errors occured during the process:  Please be sure that there are no extra spaces or break line after your last order number !</strong> '. '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'. $rptQuery . mysqli_error($con));
			$usercount=mysqli_num_rows($rptResult);
				$rptQuery="";}
					
if ($usercount == 0){
echo '</form>';
}
			
if ($usercount != 0){

echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr bgcolor=\"#000000\"></tr>";
	  echo "<tr>
				<td align=\"center\">Check all<br><input name=\"UpdateOrderNum[]\"  title=\"Check all orders\" onclick='checkedAll();' alt=\"Check all orders\" id=\"UpdateOrderNum[]\"  value=\"$listItem[order_num]\"  type=\"checkbox\"/></td>
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
				<td align=\"center\"><strong>Amount Already Credited</strong></td>
	            </tr>
				<tr>";		  
$OrderAbleToUpdate = 0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){

			
			$order_date=$listItem[order_date_processed];
			//$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
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
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";						break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
				}
		
	
          
			
			
			//Commande d�ja shipp�, on disable la case a cocher
			if ($list_order_status!='Shipped'){
				
				  echo "<td bgcolor=\"#FF0000\"d align=\"center\">&nbsp;</td><td style=\"font-size:16px;\"  bgcolor=\"#FF0000\" align=\"center\">$listItem[order_num]</td> <td style=\"font-size:16px;\" align=\"center\" bgcolor=\"#FF0000\">$order_date</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]</td>";
			}else{
				$OrderAbleToUpdate +=1;
			 echo "<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\"  alt=\"Check this order to update the status\" value=\"$listItem[order_num]\"  type=\"checkbox\"/>	</strong></td><td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> <td style=\"font-size:16px;\" align=\"center\">$order_date</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td   style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td><td  style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;</td><td  style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]&nbsp;</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]&nbsp;</td>";
			}
			
		
		 
				    
				if($ship_date!=0)
                echo "<td style=\"font-size:16px;\"    align=\"center\">$ship_date&nbsp;</td>";
				else
				if ($list_order_status=='Cancelled'){
				echo "<td  align=\"center\">&nbsp;</td>";
				}else{
				echo "<td align=\"center\">&nbsp;</td>";
				}
                	
		
		
		if ($list_order_status!='Shipped'){
		echo "<td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$list_order_status</td>";
		}else{
		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>";
		}
		
		
		$queryAlreadyCredited  = "SELECT SUM(mcred_abs_amount) as dejacredite FROM memo_credits WHERE mcred_order_num = $listItem[order_num]";
		$resultAlreadyCredited = mysqli_query($con,$queryAlreadyCredited)		or die ('Could not check already credited because: ' . mysqli_error($con));
		$DataAlreadyCredited   = mysqli_fetch_array($resultAlreadyCredited,MYSQLI_ASSOC);
		if ($DataAlreadyCredited[dejacredite] == '')
		{
			$dejaCredite = '0'. '$';
		}else{
			$dejaCredite = $DataAlreadyCredited[dejacredite]	. '$';
		}
		
		
		echo "<td style=\"font-size:16px;\"  align=\"center\">$dejaCredite&nbsp;</td>";
		
           
		echo	"</tr>";
}//END WHILE


				  
				  
echo "</table>";


 if ($OrderAbleToUpdate > 0){
 
				 if ($OrderAbleToUpdate > 0){
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }else{
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }				  
 }else{
 $noOrdertoUpdate =true;
 }
 

echo "</form><br><br><br>";
}



if($DisplayUpdateDetail){
//On bati le form pour passer les updates par un champ cach�
echo '<p align="center">Update saved</p>';

}

if (($noOrdertoUpdate) && ($_POST[rpt_search]=="Verify"))
echo '<div align="center" style="position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;">
None of these orders are shipped so you cannot create any credit for them.
</div>';
?>

            

</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>