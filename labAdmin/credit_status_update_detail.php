<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



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
		$resultGetInfo 		= mysql_query($queryGetInfo)		or die ('Could not check already credited because: ' . mysql_error());
		$DataGetInfo   		= mysql_fetch_array($resultGetInfo);
		
		$querymemoCred  	= "SELECT count(mcred_primary_key) as nbrMemoCred FROM memo_credits WHERE mcred_order_num =  ". $the_order_num;
		$resultmemoCred 	= mysql_query($querymemoCred)		or die ('Could not check querymemoCred because: ' . mysql_error());
		$DatamemoCred   	= mysql_fetch_array($resultmemoCred);
		$nbrMemoCred 	 	= $DatamemoCred[nbrMemoCred];
		
		$querymemoCredTemp  = "SELECT count(mcred_primary_key_temp) as nbrMemoCredTemp  FROM memo_credits_temp WHERE mcred_order_num =  ". $the_order_num;
		$resultmemoCredTemp = mysql_query($querymemoCredTemp)		or die ('Could not check querymemoCredTemp because: ' . mysql_error());
		$DatamemoCredTemp   = mysql_fetch_array($resultmemoCredTemp);
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
		////$InsertQuery = "INSERT INTO memo_credits_temp (
		//mcred_acct_user_id,		mcred_order_num,		pat_ref_num,		patient_first_name,		patient_last_name,		mcred_memo_num,		mcred_cred_type,		mcred_date)
		//VALUES (		'$DataGetInfo[user_id]', 		$the_order_num, 		'$DataGetInfo[patient_ref_num]', 		'$DataGetInfo[order_patient_first]', 		'$DataGetInfo[order_patient_last]',
		//'$mcred_memo_num',		'credit',		'$datecomplete')";		//Ces info viendront de la table ORDERS
		$UpdateDetails  .= "<br>Credit  request for order #$the_order_num has been set  to: Request received";
		//$resultInsert=mysql_query($InsertQuery)		or die ('Could not insert because: ' . mysql_error());

		//mcred_disc_type,   		//mcred_amount,
		//mcred_abs_amount,    		//mcred_memo_code,
		
		$todayDate = date("Y-m-d g:i a");// current date
		$ip	       = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		$acces_id  = $_SESSION["access_admin_id"];	
		
		//We add the credit status in credit_status_history table
		//$InsertQueryHistory = "INSERT INTO memo_credits_status_history (
		// 		 mcred_memo_num,       order_num,      request_status,        update_time,       update_type,  update_ip,  access_id)
		//VALUES ('$mcred_memo_num', $the_order_num,    'Request received',    '$datecomplete',        'manual',     '$ip',     $acces_id)";
		
		//$resultInsertHistory=mysql_query($InsertQueryHistory)		or die ('Could not insert because: ' . mysql_error()); 	  
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
<form  method="post" name="verification" id="verification" action="credit_status_update.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Credit Status Update</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your order numbers separated by a comma</div>					
                        <textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                		
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="credit_status_update">
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('<strong>Errors occured during the process:  Please be sure that there are no extra spaces or break line after your last order number !</strong> '. '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'. $rptQuery . mysql_error());
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}
					
if ($usercount == 0){
echo '</form>';
}
			
if ($usercount != 0){

echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr bgcolor=\"#000000\"></tr>";
	  echo "<tr>
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
while ($listItem=mysql_fetch_array($rptResult)){

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
				
				switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";					break;
						case 'order imported':			$list_order_status = "Order Imported";				break;
						case 'job started':				$list_order_status = "Surfacing";					break;
						case 'in coating':				$list_order_status = "In Coating";					break;
						case 'profilo':					$list_order_status = "Profilo";					    break;
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
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";			break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";			break;
						case 'information in hand':		$list_order_status = "Info in Hand";				break;
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Redo";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'out for clip':			$list_order_status = "Out for Clip";				break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'verifying':				$list_order_status = "Verifying";					break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";		break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";			break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";			break;
				}
		
	
          
			
			
			//Commande déja shippé, on disable la case a cocher
			//if ($list_order_status!='Shipped'){
				
				 /*?> echo "<td style=\"font-size:16px;\"  bgcolor=\"#FF0000\" align=\"center\">$listItem[order_num]</td> <td style=\"font-size:16px;\" align=\"center\" bgcolor=\"#FF0000\">$order_date</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]</td>";<?php */
			//}else{
				
				$queryMcredMemoNum     = "SELECT mcred_memo_num FROM memo_credits_temp WHERE mcred_order_num = ". $listItem[order_num];
				$resultMcredMemoNum    = mysql_query($queryMcredMemoNum)		or die ('Could not check already credited because: ' . mysql_error());
				$DataMcredMemoNum      = mysql_fetch_array($resultMcredMemoNum);
				
				$OrderAbleToUpdate +=1;
			 echo "<td style=\"font-size:16px;\"  align=\"center\"><a href=\"credit_status_update_detail.php?mcred_memo_num=". $DataMcredMemoNum[mcred_memo_num]. "\">$listItem[order_num]</a></td> <td style=\"font-size:16px;\" align=\"center\">$order_date</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td   style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td><td  style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;</td><td  style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]&nbsp;</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]&nbsp;</td>";
			//}
			
		
		 
				    
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
		$resultAlreadyCredited = mysql_query($queryAlreadyCredited)		or die ('Could not check already credited because: ' . mysql_error());
		$DataAlreadyCredited   = mysql_fetch_array($resultAlreadyCredited);
		if ($DataAlreadyCredited[dejacredite] == '')
		{
			$dejaCredite = '0'. '$';
		}else{
			$dejaCredite = $DataAlreadyCredited[dejacredite]	. '$';
		}
		
		if ($dejaCredite == '0$')
		echo "<td style=\"font-size:16px;\"  align=\"center\">$dejaCredite&nbsp;</td>";
		else 
		echo "<td style=\"font-size:16px;\"  align=\"center\">$dejaCredite&nbsp;</td>";
           
		echo	"</tr>";
}//END WHILE

 if ($OrderAbleToUpdate > 0){
 
				 /*?> if ($OrderAbleToUpdate > 0){
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }else{
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }	<?php */		  
 }else{
 $noOrdertoUpdate =true;
 }
				  
				  
echo "</table></form>";
}

if($DisplayUpdateDetail){
//On bati le form pour passer les updates par un champ caché
echo  '<form action="print_shipping_tool_updates.php" name="print_updates" id="print_updates" method="post" target="_blank" >';
echo "<div align=\"center\" style=\"position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;\">$UpdateDetails
<br><input style=\"font-size:14px;\" name=\"print_status_update\" type=\"submit\" id=\"print_status_update\" value=\"Print Updates\" class=\"formField\"</div>";
echo "<input type=\"hidden\" value=\"$UpdateDetails\" name=\"theupdates\" id=\"theupdates\">"; 
echo '</form>';
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