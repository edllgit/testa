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


if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if($_POST[rpt_search]=="Verifier/Verify")
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
		
			/*if (strlen($valueSansEspace)<> 7)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Invalid Order number (should be 7 caracters)";
			$PassValidation = false;
			}*/
		
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
		
		$JourneeAInclure = $_POST[JourneeAInclure];
		$newDate       = mktime(0,0,0,date("m"),date("d")-$JourneeAInclure,date("Y"));
		$DateIlya2Mois = date("Y/m/d", $newDate);
	 	 
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, orders.prescript_lab, accounts.buying_group, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			AND orders.order_status NOT IN ('cancelled')
			WHERE orders.tray_num IN ($_POST[order_num])
			AND order_date_processed > '$DateIlya2Mois' ORDER BY tray_num";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Since there are errors, you need to re-enter <strong>all</strong> your  order numbers: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
	
}//End if Verify


//inclure les commandes jusqu'a un maximum de  2 mois

if(isset($_POST[UpdateOrderNum])){	
$NewOrderStatus =  $_POST["order_status"];
$UpdateDetails  = "";

foreach( $_POST[UpdateOrderNum] as $the_order_num ){	
	//First we need to insert in status_history to keep a track of what has been updated
	$todayDate = date("Y-m-d g:i a");// current date
	$order_date_shipped = date("Y-m-d");// current date
	$currentTime = time($todayDate); //Change date into time
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$acces_id = $_SESSION["access_admin_id"];
	$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (strlen($the_order_num)==7)
	{
		$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, access_id, update_ip2, provient_de, browser  ) 
										  VALUES($the_order_num,'$NewOrderStatus','Admin Tray search tool','$datecomplete','$ip',$acces_id, '$ip2', '$provient_de','$browser')";
					  						  
		$resultStatus=mysqli_query($con,$queryStatus)		or die ('Could not insert because: ' . mysqli_error($con));
		
		//Then, we update the status of these jobs in table orders
		//IF THE STATUS IS SHIPPED' WE ALSO NEED TO FILL IN THE SHIP DATE
		if ($NewOrderStatus=='filled'){
		$queryUpdate="UPDATE orders set order_status = '$NewOrderStatus', order_date_shipped = '$order_date_shipped' WHERE order_num = $the_order_num";
		$UpdateDetails  .= "<br>Order #$the_order_num has been updated to: Shipped";
		}else{
		$queryUpdate	 = "UPDATE orders set order_status = '$NewOrderStatus' WHERE order_num = $the_order_num";
		$UpdateDetails  .= "<br>Order #$the_order_num has been updated to: $NewOrderStatus";
		}
		//echo '<br>' . $queryUpdate;	
		$resultUpdate=mysqli_query($con,$queryUpdate)		or die ('Could not insert because: ' . mysqli_error($con));
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
<form  method="post" name="verification" id="verification" action="tray.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Tray search tool</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
                       
                      
                     <strong>1-</strong> Please enter your Tray numbers separated by a comma <br>(<strong>IMP:</strong> After the last tray, make sure there are no extra spaces or break line)
                        </div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verifier/Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea>
					 

				
					 
					<h2>In the result, please include orders that were processed in the last</h2>
					  <select name="JourneeAInclure" id="JourneeAInclure">
						 <option value="15"  <?php if ($JourneeAInclure== 15) echo ' selected';  ?></option>15 days</option>
						 <option value="30"  <?php if ($JourneeAInclure== 30) echo ' selected';  ?>>30 days</option>
						 <option value="45"  <?php if ($JourneeAInclure== 45) echo ' selected';  ?>>45 days</option>
						 <option value="60"  <?php if ($JourneeAInclure== 60) echo ' selected';  ?>>60 days</option>
						 <option value="75"  <?php if ($JourneeAInclure== 75) echo ' selected';  ?>>75 days</option>
						 <option value="90"  <?php if ($JourneeAInclure== 90) echo ' selected';  ?>>90 days</option>
						 <option value="105" <?php if ($JourneeAInclure==105) echo ' selected';  ?>>105 days</option>
						 <option value="120" <?php if ($JourneeAInclure==120) echo ' selected';  ?>>120 days</option>
					 </select>
					  </td>
	</tr>
			  
				  <tr>
					 <td><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verifier/Verify" class="formField"></div></td> 
					
					 
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                
    	
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="tray.php">
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
                <td align=\"center\"><strong>Supplier</strong></td>
				<td align=\"center\"><strong>Model</strong></td>
				<td align=\"center\"><strong>Company</strong></td>
				<td align=\"center\"><strong>Tray</strong></td>
				<td align=\"center\"><strong>Patient</strong></td>
				<td align=\"center\"><strong>Ref</strong></td>
                <td align=\"center\"><strong>Date Shipped</strong></td>
                <td align=\"center\"><strong>Order Status</strong></td>
				<td align=\"center\"><strong>Lab</strong></td>
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
						case 'central lab marking':		$list_order_status = "Central Lab Marking";			break;
						case 'order completed':			$list_order_status = "Order Completed";				break;
						case 'interlab':				$list_order_status = "Interlab P";				    break;
						case 'interlab vot':			$list_order_status = "Interlab P";				    break;
						case 'interlab qc':			    $list_order_status = "Interlab QC";				    break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";				break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";				break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";				break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";				break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";				break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";				break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";				break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";			break;
						case 'information in hand':		$list_order_status = "Info in Hand";				break;
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Redo";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'out for clip':			$list_order_status = "Out for clip";				break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'verifying':				$list_order_status = "Verifying";					break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";		break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
				}		
	
          $queryMonture = "SELECT supplier, temple_model_num FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category='Frame'";
		  $resultMonture=mysqli_query($con,$queryMonture)		or die ('Could not insert because: ' . mysqli_error($con));
		  $DataMonture=mysqli_fetch_array($resultMonture,MYSQLI_ASSOC);
			
			
			//Commande déja shippé, on disable la case a cocher
			if (($list_order_status=='Shipped') || ($list_order_status=='Cancelled')){
				  echo "
				  <td bgcolor=\"#FF0000\"d align=\"center\">&nbsp;</td>
				  <td style=\"font-size:16px;\"  bgcolor=\"#FF0000\" align=\"center\">$listItem[order_num]</td> 
				  <td style=\"font-size:16px;\"  bgcolor=\"#FF0000\"  align=\"center\">$DataMonture[supplier]</td>
				  <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$DataMonture[temple_model_num]</td>
				  <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
				  <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td>
				  <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
				  <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]</td>";
			}else{
			$OrderAbleToUpdate +=1;
			 echo "
		<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\" alt=\"Check this order to update the status\" value=\"$listItem[order_num]\" type=\"checkbox\"/></strong></td>	        <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
		<td style=\"font-size:16px;\"  align=\"center\">$DataMonture[supplier]</td>
		<td style=\"font-size:16px;\"  align=\"center\">$DataMonture[temple_model_num]</td>
		<td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
		<td style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]&nbsp;</td>
		<td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
		<td style=\"font-size:16px;\"  align=\"center\">$listItem[patient_ref_num]&nbsp;</td>";
			}
			
		
		 
				    
				if($ship_date!=0)
                	echo "<td style=\"font-size:16px;\"  bgcolor=\"#FF0000\"  align=\"center\">$ship_date&nbsp;</td>";
				else
				if ($list_order_status=='Cancelled'){
				echo "<td bgcolor=\"#FF0000\"  align=\"center\">&nbsp;</td>";
				}else{
				echo "<td align=\"center\">&nbsp;</td>";
				}
				
				switch($listItem[prescript_lab]){
					case 21: $PrescriptLab = 'TR'; 			     break;
					case 3:  $PrescriptLab = 'Saint-Catharines'; break;
					case 10: $PrescriptLab = 'Swisscoat'; 		 break;
					case 25: $PrescriptLab = 'Central Lab';	 	 break;
					case 69: $PrescriptLab = 'Essilor Lab #1';	 break;
					case 60: $PrescriptLab = 'CSC'; 			 break;
					case 72: $PrescriptLab = 'Quebec'; 			 break;
					default: $PrescriptLab = 'Erreur'; 			 
				}//End Switch
				

		if (($list_order_status=='Shipped')|| ($list_order_status=='Cancelled')){
		echo "<td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$list_order_status</td>
			  <td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$PrescriptLab</td>";
		}else{
		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>
			 <td style=\"font-size:16px;\"  align=\"center\">$PrescriptLab</td>";
		}
           
		echo	"</tr>";
}//END WHILE


 if ($OrderAbleToUpdate > 0){
	echo  "<tr> 
	<td align=\"center\" colspan=\"12\" nowrap=\"nowrap\">
                     <br><br><div style=\"font-size:14px;\">
					 <strong>2-</strong> Choisissez le status a appliquer sur ces commandes/
					  Select the Status that you want to apply to these orders</div>
                   <select style=\"font-size:14px;\" name=\"order_status\" id=\"order_status\" class=\"formField\">
					  <option value=\"central lab marking\">Marquage Central Lab/Central Lab Marking</option>
					  <option value=\"cancelled\">Annull&eacute;e/Cancelled</option>
					  <option value=\"on hold\">En attente/On Hold</option>
					  <option value=\"information in hand\">Information en main/Info in Hand</option>
					  <option value=\"in coating\">Au traitement/In Coating</option>
					  <option value=\"in mounting\">Au montage/In Mounting</option>
					  <option value=\"in edging\">Au Taillage/In Edging</option>
					  <option value=\"job started\">Surfacage/Surfacing</option>
					  <option value=\"in transit\">En transit/In Transit</option>
					  <option value=\"out for clip\">Sorti pour Clip/Out for clip</option>
					  <option value=\"order completed\">Termin&eacute;e/Order Completed</option>
					  <option value=\"order imported\">Import&eacute;e/Order Imported</option>
					  <option value=\"re-do\">Reprise/Redo</option>
					  <option value=\"filled\">Exp&eacute;di&eacute;e/Shipped</option>
					  <option value=\"waiting for frame\">Attente de la monture/Waiting for Frame</option>
					  <option value=\"waiting for lens\">Attente des verres/Waiting for Lens</option>
					  <option value=\"interlab qc\">Interlab QC</option>
					  <option value=\"waiting for shape\">Attente de la forme/Waiting for Shape</option>
					  <option value=\"verifying\">Verification/Verifying</option>
					  <option value=\"scanned shape to swiss\">Scanned shape to Swiss</option>
 </select><br><br><br>";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Mettre a jour/Update Status\" class=\"formField\"></tr>";
				  }else{
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Mettre a jour/Update Status\" class=\"formField\"></tr>";
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