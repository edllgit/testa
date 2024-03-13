<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



if($_POST[rpt_search]=="Verify")
{
	
	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
	//On Créé l'array avec tous les order num qui ont été entrés	

	$_POST[order_num] =  trim($_POST[order_num],"\n");
	$_POST[order_num] =  trim($_POST[order_num],"\r");
	$_POST[order_num] =  trim($_POST[order_num]," ");
	
	
	//Enlever la virgule de la fin s'il y en a une 
	if (substr($_POST["order_num"], -1) == ',') {
	$_POST["order_num"] = substr($_POST["order_num"],0,strlen($_POST["order_num"])-1);
	}

	$Array_OrderNum =  explode(",", $_POST["order_num"]);
	//Valider les numéros de commandes passé, longeur doit etre de 7, doit etre numeric
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

			//Si la longeur est > a 7 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque numéro
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
			WHERE orders.order_num IN ($_POST[order_num]) GROUP BY order_num ORDER BY orders.user_id, orders.prescript_lab";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
	
 
	
}//End if Verify






if(isset($_POST[UpdateOrderNum])){	
//$NewOrderStatus =  $_POST["order_status"];
$UpdateDetails  = "";

foreach( $_POST[UpdateOrderNum] as $the_order_num ){	
	//First we need to insert in status_history to keep a track of what has been updated
	$todayDate 			= date("Y-m-d g:i a");// current date
	$order_date_shipped = date("Y-m-d");// current date
	$currentTime 		= time($todayDate); //Change date into time
	$timeAfterOneHour 	= $currentTime;
	$datecomplete 		= date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip					= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$acces_id 			= $_SESSION["access_admin_id"];
	$provient_de  	    = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser      		= $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	$ip2 		  		= $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (strlen($the_order_num)==7)
	{
		$nomTray = 'tray_num'. '/'. 	$the_order_num;	
		$TrayValue = $_POST[$nomTray];
		
				
		 ?>
   
    <script type="text/javascript">
	window.open( "/admin/fastPrint.php?order_num=<?php echo $the_order_num?>" )
	</script>
	
	<?php
		
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
		
		
		$Ref_GRM_Sans_Espace =  $_POST[grm_reference];
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\n");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\r");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace," ");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="eyelation_fast_print.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Eyelation Fast Printing Tool</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div><strong>1-</strong> Please enter your order numbers separated by a comma <br>(<strong>IMP:</strong> After the last order number, make sure there are no extra spaces or break line)</div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
	
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="eyelation_fast_print.php">
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
				<td align=\"center\"><strong>Tray</strong></td>
                <td align=\"center\"><strong>Order Date</strong></td>
				<td align=\"center\"><strong>Main Lab</strong></td>
				<td align=\"center\"><strong>Company</strong></td>
				<td align=\"center\"><strong>Pat. First</strong></td>


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
						case 'job started':				$list_order_status = "Surfacing";			    	break;
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
						case 'information in hand':		$list_order_status = "Info in Hand";				break;
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Redo";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'verifying':				$list_order_status = "Verifying";					break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";		break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
				}
		
	
          
			
			
			//Commande déja shippé, on disable la case a cocher
			
			$OrderAbleToUpdate +=1;
			 echo "<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\"  alt=\"Check this order to update the status\" value=\"$listItem[order_num]\"  type=\"checkbox\"/></strong></td>                   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
			       <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td>
			       <td style=\"font-size:16px;\"  align=\"center\">$order_date</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;</td>";
			

		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>";
		
           
		echo	"</tr>";
}//END WHILE


 if ($OrderAbleToUpdate > 0){
	echo  "<tr> 
	<td align=\"center\" colspan=\"11\" nowrap=\"nowrap\">";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Print checked orders\" class=\"formField\"></tr>";
				  }else{
				  echo "<input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Save and Print checked orders\" class=\"formField\"></tr>";
				  }
				  
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


?>

            

</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>