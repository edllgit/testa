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
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, orders.prescript_lab, accounts.buying_group, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name, orders.order_product_id from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num]) AND lab IN (66,67) AND prescript_lab = 73";
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
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$acces_id = $_SESSION["access_admin_id"];
	$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client � la page courante. 
	$browser      = $_SERVER['HTTP_USER_AGENT'];//Cha�ne qui d�crit le client HTML utilis� pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (strlen($the_order_num)==7)
	{
		$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, access_id, update_ip2, provient_de, browser  ) 
										  VALUES($the_order_num,'$NewOrderStatus','Admin fast shipping page','$datecomplete','$ip',$acces_id, '$ip2', '$provient_de','$browser')";
					  						  
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
		
		
		$Ref_GRM_Sans_Espace =  $_POST[grm_reference];
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\n");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace,"\r");
		$Ref_GRM_Sans_Espace =  trim($Ref_GRM_Sans_Espace," ");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="cost_validation_knr.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">KNR: Cost validation tool</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<?php /*?><div style="font-size:24px;"><strong>THIS TOOL IS IN MAINTENANCE , PLEASE COME BACK IN AN HOUR</strong> <br>
                        <strong>CET OUTIL EST EN  MAINTENANCE , SVP REVENEZ DANS UNE HEURE</strong> </div>	<?php */?>
                        
                        <div> <br><strong>1-</strong> Entrer vos num&eacute;ros de commandes, avec une virgule entre chaque num&eacute;ro
                       <br><br><strong>1-</strong> Please enter your order numbers separated by a comma <br>(<strong>IMP:</strong> After the last order number, make sure there are no extra spaces or break line)
                        </div>					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verifier/Verify"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verifier/Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                
    	
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="cost_validation_knr.php">
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
				<th align=\"center\">Check all<br><input name=\"UpdateOrderNum[]\"  title=\"Check all orders\" onclick='checkedAll();' alt=\"Check all orders\" id=\"UpdateOrderNum[]\"  value=\"$listItem[order_num]\"  type=\"checkbox\"/></th>
                <th align=\"center\"><strong>Order Number</strong></th>
				<th align=\"center\"><strong>Product Code</strong></th>
                <th align=\"center\"><strong>Product</strong></th>
				<th align=\"center\"><strong>Lenses+Coating</strong></th>
				<th align=\"center\"><strong>Edging</strong></th>
				<th align=\"center\"><strong>TOTAL (CAD)</strong></th>
	            </tr>
				<tr>";		  
$OrderAbleToUpdate = 0;
$TotalCostCAD=0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){

	
		$queryProduct = "SELECT product_code, product_name, cost_us FROM ifc_ca_exclusive WHERE primary_key= $listItem[order_product_id]";
		//echo '<br>'.$queryProduct;
		$resultProduct = mysqli_query($con,$queryProduct)		or die ('Could not insert because: ' . mysqli_error($con));
		$DataProduct   = mysqli_fetch_array($resultProduct,MYSQLI_ASSOC);
	
		$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
		$order_date=$listItem[order_date_processed];
		$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
		$ship_date=$listItem[order_date_shipped];
		//Commande d�ja shipp�, on disable la case a cocher
		$OrderAbleToUpdate +=1;
	
		$TotalinludingEdging = $DataProduct[cost_us] + 5.4;
		$TotalinludingEdging=money_format('%.2n',$TotalinludingEdging);
	
		$TotalCostCAD = $TotalCostCAD + $TotalinludingEdging;
	
	
		echo "
		<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\" alt=\"Check this order to update the status\" value=\"$listItem[order_num]\" type=\"checkbox\"/></strong></td>	       
		<td style=\"font-size:14px;\" align=\"center\">$listItem[order_num]</td>
		<td style=\"font-size:14px;\" align=\"center\">$DataProduct[product_code]</td>
		<td style=\"font-size:13px;\" align=\"center\">$DataProduct[product_name]</td>
		<td style=\"font-size:14px;\" align=\"center\">$DataProduct[cost_us]$</td>
		<td style=\"font-size:14px;\" align=\"center\">5.40$</td>
		<td style=\"font-size:16px;\" align=\"center\">$TotalinludingEdging$</td>
		</tr>";

}//END WHILE

	
	//Afficher le total dans le bas des factures affich�es
	$TotalCostUS = money_format('%.2n',$TotalCostUS);		
	echo "<tr><td colspan=\"4\" align=\"right\" style=\"font-size:16px;\"><b>Total CAD:</b></td><td colspan=\"3\" align=\"center\" style=\"font-size:16px;\"><b>$TotalCostCAD$</b></td></tr>"	;
	
	

 /*if ($OrderAbleToUpdate > 0){
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
					  <option value=\"verifying\">Verification/Verifying</option>
					  <option value=\"waiting for frame\">Attente de la monture/Waiting for Frame</option>
					  <option value=\"waiting for lens\">Attente des verres/Waiting for Lens</option>
					  <option value=\"interlab qc\">Interlab QC</option>
					  <option value=\"waiting for shape\">Attente de la forme/Waiting for Shape</option>
 </select><br><br><br>";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Mettre a jour/Update Status\" class=\"formField\"></tr>";
				  }else{
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Mettre a jour/Update Status\" class=\"formField\"></tr>";
				  }
				  
 }else{
 $noOrdertoUpdate =true;
 }		*/		  
				  
echo "</table></form>";
}

if($DisplayUpdateDetail){
//On bati le form pour passer les updates par un champ cach�
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