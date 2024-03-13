<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



if($_POST[rpt_search]=="Verify"){
	
	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
	//On Créé l'array avec tous les order num qui ont été entrés		
	
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
		//echo $valueSansEspace . '<br />'; 
		
			if (strlen($valueSansEspace)<> 7)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Num&eacute;ro de commande invalide (devrait avoir une longeur de 7 caracteres)";
			$PassValidation = false;
			}
		
			if (is_numeric($valueSansEspace)==false)
			{
			$errorMessage .= "<br><strong>$valueSansEspace</strong>: Num&eacute;ro de commande contient des caracteres autres que des chiffres";
			$PassValidation = false;
			}
			
			

$comma  = ',';
$CommaInOrderNum = strpos($_POST["order_num"], $comma);
$LongeurOrderNum = strlen(trim($_POST["order_num"], " "));

			//Si la longeur est > a 7 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque numéro
			if (($LongeurOrderNum >7) && ($CommaInOrderNum == '' )){
			$errorMessage .= "<br>Les num&eacute;ros de commande ne contiennent pas de virgule (,)  svp veuillez s&eacute;parer chaque num&eacute;ro de commande par une virgule.";
			$PassValidation = false;
			}
			
			
		}//End for each
	  
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num])
			AND orders.lab = 37";
			echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:520px;border:1px solid black;background-color:#FF0033;" >Puisqu\'il y a des erreurs,  vous devez les corriger avant de valider: ' . $errorMessage . '</div>';
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
		$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, access_id ) 
										  VALUES($the_order_num,'$NewOrderStatus','Admin fast shipping page','$datecomplete','$ip',$acces_id)";
					  						  
		$resultStatus=mysqli_query($con,$queryStatus)		or die ('Could not insert because: ' . mysqli_error($con));
		
		//Then, we update the status of these jobs in table orders
		//IF THE STATUS IS SHIPPED' WE ALSO NEED TO FILL IN THE SHIP DATE
		if ($NewOrderStatus=='filled'){
		$queryUpdate="UPDATE orders set order_status = '$NewOrderStatus', order_date_shipped = '$order_date_shipped' WHERE order_num = $the_order_num";
		$UpdateDetails  .= "<br>La commande  #$the_order_num a ete mise au status: Livre";
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
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="shipping_ifc.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">IFC Fast Shipping Tool</font></b></td>
            	</tr>
                
                <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr>  <tr><td>&nbsp;</td></tr> <tr><td>&nbsp;</td></tr>

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
						<strong>1-</strong>Entrer vos num&eacute;ro(s) de commande, séparé par une virgule Ex(1201234,1205468,1204985)
					<textarea cols="20" name="order_num" style="font-size:16px;"  rows="20" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verify"){ echo $_POST[order_num];}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verify" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
				
                		
			</table>
</form>

<form  method="post" name="update_status" id="update_status" action="shipping_ifc.php">
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysqli_query($con ,$rptQuery)		or die  ('<strong>Des erreurs sont apparus durant le processus: SVP assurez vous qu\'il n\'y a pas d\'espaces ni de saut de ligne apr&egrave;s votre dernier num&eacute;ro de commande.</strong> '. '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>'. $rptQuery . mysqli_error($con));
			$usercount=mysqli_num_rows($rptResult);
				$rptQuery="";}
	
	
if ($usercount == 0){	
echo 'Erreur: Aucune num&eacute;ro de commande IFC valide n\'a &eacute;t&eacute; entr&eacute;';
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
				<td align=\"center\"><strong>User ID</strong></td>
                <td align=\"center\"><strong>Date Shipped</strong></td>
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
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
				}
		
	
          
			
			
			//Commande déja shippé, on disable la case a cocher
			if (($list_order_status=='Shipped') || ($list_order_status=='Cancelled')){
				  echo "<td bgcolor=\"#FF0000\"d align=\"center\">&nbsp;</td><td style=\"font-size:16px;\"  bgcolor=\"#FF0000\" align=\"center\">$listItem[order_num]</td> <td style=\"font-size:16px;\" align=\"center\" bgcolor=\"#FF0000\">$order_date</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[user_id]</td>";
			}else{
			$OrderAbleToUpdate +=1;
			 echo "<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\"  alt=\"Check this order to update the status\" value=\"$listItem[order_num]\"  type=\"checkbox\"/>	</strong></td><td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> <td style=\"font-size:16px;\" align=\"center\">$order_date</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td><td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td><td   style=\"font-size:16px;\"  align=\"center\">$listItem[user_id]</td>";
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
	<td align=\"center\" colspan=\"8\" nowrap=\"nowrap\">
                     <br><br><div style=\"font-size:14px;\"><strong>2-</strong> S&eacute;lectionner le status que vous voulez appliquer &agrave; ces commandes Select the Status that you want to apply to these orders</div>
                   <select style=\"font-size:14px;\" name=\"order_status\" id=\"order_status\" class=\"formField\">
					  <option value=\"filled\">Shipped</option>
				   </select>
				   <br><br><br>";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Update Status\" class=\"formField\"></tr>";
				  }else{
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update Status\" class=\"formField\"></tr>";
				  }
				  
 }else{
 $noOrdertoUpdate =true;
 }				  
				  
echo "</table>";
}

if($DisplayUpdateDetail){
echo "<div align=\"center\" style=\"position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;\">$UpdateDetails</div>";
}


if ($noOrdertoUpdate)
echo '<div align="center" style="position:absolute;left:560px;top:25px;width:520px;border:1px solid black;background-color:#FF0033;" >Toutes ces commandes sont soit cancell&eacute;s, soit livr&eacute;s. Donc leur status ne peut pas etre modifi&eacute;.</div>';
?>

            
</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>