<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include('../includes/fax_confirm_functions.inc.php');
//include('includes/fax_confirm_functions_lab.inc.php');
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
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group,orders.prescript_lab, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num])";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
	
}//End if Verify






if(isset($_POST[UpdateOrderNum])){	
$NewOrderStatus =  $_POST["order_status"];
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
		//$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, access_id ) 
										  //VALUES($the_order_num,'$NewOrderStatus','Admin fast redirecting page','$datecomplete','$ip',$acces_id)";
					  						  
		//$resultStatus=mysql_query($queryStatus)		or die ('Could not insert because: ' . mysql_error());
		
		//Then, we update the status of these jobs in table orders
		//IF THE STATUS IS SHIPPED' WE ALSO NEED TO FILL IN THE SHIP DATE
	$queryUser  = "SELECT user_id from orders WHERE order_num = $the_order_num";
	$resultUser = mysql_query($queryUser)		or die ('Could not insert because: ' . mysql_error());	
	$DataUser   = mysql_fetch_array($resultUser);
	
		$queryUpdate = "UPDATE orders set prescript_lab = '$_POST[lab]' WHERE order_num = $the_order_num";
		//echo '<br>'. $queryUpdate;
		$UpdateDetails  .= "<br>Order #$the_order_num has redirected to lab #: $_POST[lab]";
		$resultUpdate=mysql_query($queryUpdate)		or die ('Could not insert because: ' . mysql_error());
		$DisplayUpdateDetail = true;
				
		//If the fax notify is checked for this prescript lab, we send a fax to the prescript lab
		$queryFax  = "SELECT fax_notify, logo_file, fax  from labs WHERE primary_key = " . $_POST[lab];
		$ResultFax = mysql_query($queryFax)		or die ('Could not insert because: ' . mysql_error());
		$DataFax   = mysql_fetch_array($ResultFax);
			
		if($DataFax[fax_notify] =='yes'){
			$Fax_Notification = 'yes';
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
			echo '<br>Order $the_order_num Has been Faxed to number: '. $faxNum; 
			sendFaxPrescriptionConfirmation("donotreply@entrepotdelalunette.com",$DataFax[logo_file],$the_order_num,"directl-config@interpage.net",$DataUser[user_id],"true",$faxNum);
			}else{
			echo 'Pas de fax envoy� pcq: '. $Fax_Notification;
			}
			//exit();
		
		
		
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
<form  method="post" name="verification" id="verification" action="redirecting_temp.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Fast Redirecting Tool</font></b></td>
            	</tr>
                
                <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr>  <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr>

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your order numbers separated by a comma (<br><strong>IMP:</strong> After the last order number, make sure there are no extra spaces or break line)</div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="20" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
				
                		
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="redirecting_temp.php">
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
				<td align=\"center\">Check all<br><input name=\"UpdateOrderNum[]\"  title=\"Check all orders\" onclick='checkedAll();' alt=\"Check all orders\" id=\"UpdateOrderNum[]\"  value=\"$listItem[order_num]\"  type=\"checkbox\"/></td>
                <td align=\"center\"><strong>Order Number</strong></td>
                <td align=\"center\"><strong>Order Date</strong></td>
				<td align=\"center\"><strong>Main Lab</strong></td>
				
				<td align=\"center\"><strong>Company</strong></td>
				<td align=\"center\"><strong>Prescript Lab</strong></td>
                <td align=\"center\"><strong>Date Shipped</strong></td>
                <td align=\"center\"><strong>Order Status</strong></td>
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
						case 'job started':				$list_order_status = "Surfacing";				    break;
						case 'in coating':				$list_order_status = "In Coating";					break;
						case 'in mounting':				$list_order_status = "In Mounting";					break;
						case 'in edging':				$list_order_status = "In Edging";					break;
						case 'order completed':			$list_order_status = "Order Completed";				break;
						case 'interlab':				$list_order_status = "Interlab P";				break;
						case 'interlab qc':				$list_order_status = "Interlab QC";				break;
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
		
	
          
			
			$queryPrescriptlab  = "SELECT lab_name from labs WHERE primary_key = " . $listItem[prescript_lab];
			$ResultPrescriptLab = mysql_query($queryPrescriptlab)		or die ('Could not insert because: ' . mysql_error());
			$DataPrescriptLab   = mysql_fetch_array($ResultPrescriptLab);
			
		
			
			
			//Commande d�ja shipp�, on disable la case a cocher
			if (($list_order_status=='Shipped') || ($list_order_status=='Cancelled')){
				echo "
				<td bgcolor=\"#FF0000\" align=\"center\">&nbsp;</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\" align=\"center\">$listItem[order_num]</td> 
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\" align=\"center\">$order_date</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\" align=\"center\">$listItem[lab_name]</td>

				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\" align=\"center\">$listItem[company]</td>
				<td bgcolor=\"#FF0000\" style=\"font-size:16px;\" align=\"center\">$DataPrescriptLab[lab_name]</td>";
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
			 <td style=\"font-size:16px;\"  align=\"center\">$DataPrescriptLab[lab_name]</td>";
			}
			
		
		 
				    
				if($ship_date!=0)
                	echo "<td style=\"font-size:16px;\"  bgcolor=\"#FF0000\"  align=\"center\">$ship_date</td>";
				else
				if ($list_order_status=='Cancelled'){
				echo "<td bgcolor=\"#FF0000\"  align=\"center\">&nbsp;</td>";
				}else{
				echo "<td align=\"center\">&nbsp;</td>";
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
	<td align=\"center\" colspan=\"9\" nowrap=\"nowrap\">
                     <br><br><div style=\"font-size:14px;\"><strong>2-</strong> Select the Lab where you want to send these order(s)</div>
                   <select style=\"font-size:14px;\" name=\"lab\" id=\"lab\" class=\"formField\">
				      <option value=\"58\">US LAB</option>
					  <option value=\"57\">Crystal Optic</option>
					  <option value=\"10\">Direct-Lens Exclusive #1 (Swisscoat)</option>
					  <option value=\"25\">Direct-Lens Exclusive #2 (HKO)</option>
					  <option value=\"26\">Direct-Lens Exclusive #3 (Charles)</option>
					  <option value=\"36\">Directlab Atlantic</option>
					  <option value=\"22\">Directlab Drummondville</option>
					  <option value=\"50\">Directlab Eagle</option>
					  <option value=\"46\">Directlab Illinois</option>
					  <option value=\"43\">Directlab Pacific</option>
					  <option value=\"39\">Directlab Rive-Sud</option>
					  <option value=\"3\">Directlab St.Catharines</option>
					  <option value=\"21\">Directlab Trois-Rivieres</option>
					  <option value=\"41\">Directlab USA</option>
					  <option value=\"55\">Precision Optical Group</option>
					  <option value=\"35\">Somo CLEARi</option>
					  <option value=\"52\">Somo STOCK</option>
					  <option value=\"1\">Vision Optics Technologies</option>
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