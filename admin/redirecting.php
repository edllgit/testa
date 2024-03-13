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

session_start();

//include('includes/fax_confirm_functions_lab.inc.php');

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
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group,orders.prescript_lab, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.tray_num, orders.order_patient_last,orders.order_patient_first, orders.patient_ref_num, orders.order_status,  labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num])";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
	
	
	
	
	
	
	
	
	
	if($_POST["grm_reference"]!=""){//search for grm reference only and ignore all other form settings
	//On Cr�� l'array avec toutes les r�f�rences GRM qui ont �t� entr�s	

	$_POST[grm_reference] =  trim($_POST[grm_reference],"\n");
	$_POST[grm_reference] =  trim($_POST[grm_reference],"\r");
	$_POST[grm_reference] =  trim($_POST[grm_reference]," ");
	
	
	//Enlever la virgule de la fin s'il y en a une 
	if (substr($_POST["grm_reference"], -1) == ',') {
	$_POST["grm_reference"] = substr($_POST["grm_reference"],0,strlen($_POST["grm_reference"])-1);
	}

	$Array_GrmReference =  explode(",", $_POST["grm_reference"]);
	//Valider les num�ros de commandes pass�, longeur doit etre de 7, doit etre numeric
	$errorMessage = '';
	$PassValidation = true;
	$Array_GrmReference = array_filter(array_map('trim', $Array_GrmReference));
	
		foreach( $Array_GrmReference as $value ){
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
			$CommaInOrderNum = strpos($_POST["grm_reference"], $comma);
			$LongeurOrderNum = strlen(trim($_POST["grm_reference"], " "));

			//Si la longeur est > a 7 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque num�ro
			if (($LongeurOrderNum >7) && ($CommaInOrderNum == '' )){
			$errorMessage .= "<br>Order numbers does not contain any comma (,)  please separate each order number with a comma";
			$PassValidation = false;
			}
			
		}//End for each
	  
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group,orders.prescript_lab, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.tray_num, orders.order_patient_last,orders.order_patient_first, orders.patient_ref_num, orders.order_status,  labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_patient_last IN ($_POST[grm_reference])";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if GRM REFERENCE is not empty
	
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
	//Then, we update the status of these jobs in table orders
	//IF THE STATUS IS SHIPPED' WE ALSO NEED TO FILL IN THE SHIP DATE
	$queryUser  = "SELECT user_id from orders WHERE order_num = $the_order_num";
	$resultUser = mysqli_query($con,$queryUser)		or die ('Could not insert because: ' . mysqli_error($con));	
	$DataUser   = mysqli_fetch_array($resultUser,MYSQLI_ASSOC);
	
		$queryUpdate = "UPDATE orders set prescript_lab = '$_POST[lab]' WHERE order_num = $the_order_num";
		//echo '<br>'. $queryUpdate;
		$UpdateDetails  .= "<br>Order #$the_order_num has redirected to lab #: $_POST[lab]";
		$resultUpdate=mysqli_query($con,$queryUpdate)		or die ('Could not insert because: ' . mysqli_error($con));
		$DisplayUpdateDetail = true;
		
		
		//Save in status history of this order the change of prescript lab that has been done with the details of WHO did it
		$todayDate = date("Y-m-d g:i a");// current date
		$currentTime = time($todayDate); //Change date into time
		//Add one hour equavelent seconds 60*60
		$timeAfterOneHour = $currentTime-((60*60)*4);	
		$datecomplete     = date("Y-m-d H:i:s",$timeAfterOneHour);
		$dateShort        = date("Y-m-d",$timeAfterOneHour);
		$access_id        = $_SESSION["accessid"];
		$ip				  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
		$update_ip2 	  = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$provient_de      = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client � la page courante. 
		$browser          = $_SERVER['HTTP_USER_AGENT'];//Cha�ne qui d�crit le client HTML utilis� pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	

        $queryLabName  = "SELECT lab_name FROM labs WHERE primary_key = $_POST[lab]" ;
		$resultLabName = mysqli_query($con,$queryLabName)		or die ('Could not get info because: ' . mysqli_error($con));
		$DataLabName   = mysqli_fetch_array($resultLabName,MYSQLI_ASSOC);

	
		$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type, update_ip, access_id, update_ip2, provient_de, browser)VALUES ('Order redirected to $DataLabName[lab_name] on $dateShort ','$the_order_num', '$datecomplete','Fast Redirecting Tool', '$ip', '$access_id','$update_ip2', '$provient_de', '$browser')";
		$resultStatusHistory = mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
		
		
		
		
				
		//If the fax notify is checked for this prescript lab, we send a fax to the prescript lab
		$queryFax  = "SELECT fax_notify, logo_file, fax  from labs WHERE primary_key = " . $_POST[lab];
		$ResultFax = mysqli_query($con,$queryFax)		or die ('Could not insert because: ' . mysqli_error($con));
		$DataFax   = mysqli_fetch_array($ResultFax,MYSQLI_ASSOC);
			
		if($DataFax[fax_notify] =='yes'){
			$Fax_Notification = 'yes ';
		}else{
			$Fax_Notification = 'no';
		}
		
		//echo '<br>Fax Notify:'. $Fax_Notification;
		
		if ($Fax_Notification=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
			$faxNumArray=str_split($DataFax[fax]);
			$numCount=count($faxNumArray);
			$faxNum="";
				for ($i=0;$i<$numCount;$i++){
					if (is_numeric($faxNumArray[$i])) {
						$faxNum.=$faxNumArray[$i];
					}
				}
				echo "<br>Order $the_order_num Has been Faxed to number: ". $faxNum; 
				sendFaxPrescriptionConfirmation("donotreply@entrepotdelalunette.com",$DataFax[logo_file],$the_order_num,"directl-config@interpage.net",$DataUser[user_id],"true",$faxNum);
		}else{
			//echo 'Pas de fax envoy� pcq: '. $Fax_Notification;
		}		
		
		
		}	//End IF  (strlen($the_order_num)==7)
		
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
		
		$Ref_GRM_Sans_Espace =  $_POST[grm_reference];
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\n");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\r");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace," ");
		
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="redirecting.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Fast Redirecting Tool</font></b></td>
            	</tr>
                
				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your order numbers separated by a comma <br>(<strong>IMP:</strong> After the last order number, make sure there are no extra spaces or break line)</div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                
                
                
                
                 <tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your <b>GRM REFERENCE</b>(Patient Last Name) separated by a comma <br>(<strong>IMP:</strong> After the last reference, make sure there are no extra spaces or break line)</div>					<textarea cols="20" name="grm_reference" style="font-size:16px;"  rows="10" id="grm_reference" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Ref_GRM_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
				
                		
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="redirecting.php">
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
				<td align=\"center\"><strong>Prescript Lab</strong></td>
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
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
				}
		
	
          
			
			$queryPrescriptlab  = "SELECT lab_name from labs WHERE primary_key = " . $listItem[prescript_lab];
			$ResultPrescriptLab = mysqli_query($con,$queryPrescriptlab)		or die ('Could not insert because: ' . mysqli_error($con));
			$DataPrescriptLab   = mysqli_fetch_array($ResultPrescriptLab,MYSQLI_ASSOC);
			
			
			//Commande d�ja shipp�, on disable la case a cocher
			if (($list_order_status=='Shipped') || ($list_order_status=='Cancelled')){
				echo "
				<td bgcolor=\"#FF0000\" align=\"center\">&nbsp;</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$order_date</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td>
			 	<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;</td>
			 	<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]&nbsp;</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]&nbsp;</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$DataPrescriptLab[lab_name]</td>";
			}else{
			$OrderAbleToUpdate +=1;
			 echo "
			 <td align=\"center\">
			 <strong><input name=\"UpdateOrderNum[]\" id=\"UpdateOrderNum[]\" alt=\"Check this order to update the status\" value=\"$listItem[order_num]\"  type=\"checkbox\"/>	</strong>
			 </td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
			 <td style=\"font-size:16px;\"  align=\"center\">$order_date</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_last]&nbsp;</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]&nbsp;</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$DataPrescriptLab[lab_name]</td>";
			}
			    	
		
		
		if (($list_order_status=='Shipped')|| ($list_order_status=='Cancelled')){
		echo "<td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$list_order_status</td>";
		}else{
		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>";
		}
           
		echo	"</tr>";
}//END WHILE


 if ($OrderAbleToUpdate > 0){
	echo  "<tr> 
	<td align=\"center\" colspan=\"11\" nowrap=\"nowrap\">
                     <br><br><div style=\"font-size:14px;\"><strong>2-</strong> Select the Lab where you want to send these order(s)</div>
                   <select style=\"font-size:14px;\" name=\"lab\" id=\"lab\" class=\"formField\">
				      <option value=\"69\">GKB/Essilor #1 Lab</option>
					  <option value=\"60\">CSC</option>
					  <option value=\"10\">Direct-Lens Exclusive #1 (Swisscoat)</option>
					  <option value=\"25\">Direct-Lens Exclusive #2 (HKO aka Central Lab)</option>
					  <option value=\"3\">Directlab St.Catharines</option>
					  <option value=\"73\">KnR</option>
					  <option value=\"58\">US Optical</option>
					  <option value=\"54\">Vision-Ease</option>
 					</select>
					<br><br><br>";

	 
 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Redirect those orders</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Redirect jobs\" class=\"formField\"></tr>";
				  }else{
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Redirect those orders</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update Status\" class=\"formField\"></tr>";
				  }
				  
 }else{
 $noOrdertoUpdate =true;
 }				  
				  
echo "</table></form>";
}

if($DisplayUpdateDetail){
//On bati le form pour passer les updates par un champ cach�
echo  '<form action="print_redirecting_tool_updates.php" name="print_updates" id="print_updates" method="post" target="_blank" >';
echo "<div align=\"center\" style=\"position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;\">$UpdateDetails
<br></div>";
echo "<input type=\"hidden\" value=\"$UpdateDetails\" name=\"theupdates\" id=\"theupdates\">"; 
echo '</form>';
}


if ($noOrdertoUpdate)
echo '<div align="center" style="position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;">
All these orders are either cancelled or shipped, so their status cannot be updated
</div>';
?>
         
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>