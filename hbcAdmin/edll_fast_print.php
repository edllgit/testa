<?php 
include "Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if ($_SESSION[labAdminData][primary_key]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
//include("admin_functions.inc.php");
include("Connections/sec_connect.inc.php");


switch($_SESSION["accessid"]){
		case 1: 	$User_ID_IN = " ('88666') ";	break; //Griffé Lunetier   
		case 2: 	$User_ID_IN = " ('88666','88433','88430','88444','88439','88438','88416','88429','88441','88435','88434','88432','88431','88442','88440','88414','88411','88408','88403','88449','88405','88409','redo_hbc') ";	break; //Griffé Lunetier   
		case 26: 	$User_ID_IN = " ('88433') ";	break;	 		
		case 25: 	$User_ID_IN = " ('88430') ";	break;	 			
		case 24: 	$User_ID_IN = " ('88444') ";	break;	  	 
		case 23: 	$User_ID_IN = " ('88439') "; 	break;	 					 
		case 22: 	$User_ID_IN = " ('88438') ";  	break;	 			  
		case 21: 	$User_ID_IN = " ('88416') ";  	break;	 
		case 20: 	$User_ID_IN = " ('88429') ";	break;	 			  
		case 19: 	$User_ID_IN = " ('88441') ";  	break;	
		case 18: 	$User_ID_IN = " ('88435') ";  	break;				 
		case 17: 	$User_ID_IN = " ('88434') ";	break;			
		case 16: 	$User_ID_IN = " ('88432') "; 	break;				 
		case 15: 	$User_ID_IN = " ('88431') "; 	break;				
		case 14: 	$User_ID_IN = " ('88442') "; 	break;					 
		case 13: 	$User_ID_IN = " ('88440') ";  	break;				 
		case 12: 	$User_ID_IN = " ('88414') "; 	break;				 
		case 11: 	$User_ID_IN = " ('88411') ";  	break;	 				
		case 10: 	$User_ID_IN = " ('88408') ";  	break;					 
		case 9: 	$User_ID_IN = " ('88403') ";  	break;				 
		case 8: 	$User_ID_IN = " ('88449') ";  	break;				 
		case 7: 	$User_ID_IN = " ('88405') ";  	break;			
		case 6: 	$User_ID_IN = " ('88409') ";  	break;
		
		default: 
}	

//echo '<br>'.  $User_ID_IN;




if($_POST[rpt_search]=="Verifier")
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
		
			if (strlen($valueSansEspace)<> 5)
			{
			$errorMessage .= "<br>Invalid Order number (should be 5 caracters)";
			$PassValidation = false;
			}
		
			if (is_numeric($valueSansEspace)==false)
			{
			//$errorMessage .= "<br><strong>$valueSansEspace</strong>: Order number contains illegal caracters";
			$errorMessage .= "<br>Order number contains illegal caracters";
			$PassValidation = false;
			}
			
			$comma  = ',';
			$CommaInOrderNum = strpos($_POST["order_num"], $comma);
			$LongeurOrderNum = strlen(trim($_POST["order_num"], " "));

			//Si la longeur est > a 7 et qu'il n'y a pas de virgule, l'usager ne sait surement pas qu'il doit entrer des virgule entre chaque numéro
			if (($LongeurOrderNum >5) && ($CommaInOrderNum == '' )){
			$errorMessage .= "<br>Séparer chaque numéro de commande par une virgule.";
			$PassValidation = false;
			}
			
		}//End for each
	  
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num IN ($_POST[order_num]) AND LAB  IN(1)
			AND accounts.user_ID IN $User_ID_IN  GROUP BY order_num ORDER BY orders.user_id, orders.prescript_lab";
			//echo '<br><br>'. $rptQuery;
			}else{
			echo '<div align="center" style="position:absolute;left:500px;width:550px;border:1px solid black;background-color:#FF0033;" >Il y a des erreurs: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
}//End if Verify






//Impression par Batch-->Demande d'Halifax Juin 2017
 if($_POST[rpt_search_batch]=="Verifier")
{
	$PassValidation = true;
	if($_POST["premier_order_num"]!="" && $_POST["dernier_order_num"]!=""){//search for order number only and ignore all other form settings
	//On Créé l'array avec tous les order num qui ont été entrés	

	$_POST[premier_order_num] =  trim($_POST[premier_order_num],"\n");
	$_POST[premier_order_num] =  trim($_POST[premier_order_num],"\r");
	$_POST[premier_order_num] =  trim($_POST[premier_order_num]," ");
	$_POST[dernier_order_num] =  trim($_POST[dernier_order_num],"\n");
	$_POST[dernier_order_num] =  trim($_POST[dernier_order_num],"\r");
	$_POST[dernier_order_num] =  trim($_POST[dernier_order_num]," ");

	//Enlever la virgule de la fin s'il y en a une 
	if (substr($_POST["premier_order_num"], -1) == ',') {
	$_POST["premier_order_num"] = substr($_POST["premier_order_num"],0,strlen($_POST["premier_order_num"])-1);
	}
	if (substr($_POST["dernier_order_num"], -1) == ',') {
	$_POST["dernier_order_num"] = substr($_POST["dernier_order_num"],0,strlen($_POST["dernier_order_num"])-1);
	}

		
	if (strlen($_POST["dernier_order_num"])<> 5)
	{
		//$errorMessage .= "<br><strong>$valueSansEspace</strong>: Invalid Order number (should be 7 caracters)";
		$errorMessage .= "<br>Invalid Order number (should be 5 caracters)";
		$PassValidation = false;
	}
			
	if (strlen($_POST["premier_order_num"])<> 5)
	{
		//$errorMessage .= "<br><strong>$valueSansEspace</strong>: Invalid Order number (should be 7 caracters)";
		$errorMessage .= "Invalid Order number (should be 5 caracters)";
		$PassValidation = false;
	}
		
		
	if (is_numeric($_POST["premier_order_num"])==false)
	{
		//$errorMessage .= "<br><strong>".$_POST["premier_order_num"]."</strong>: Order number contains illegal caracters";
		$errorMessage .= "<br>Order number contains illegal caracters";
		$PassValidation = false;
	}
			
	if (is_numeric($_POST["dernier_order_num"])==false)
	{
		//$errorMessage .= "<br><strong>".$_POST["dernier_order_num"]."</strong>: Order number contains illegal caracters";
		$errorMessage .= "<br>Order number contains illegal caracters";
		$PassValidation = false;
	}
	
	$PremierOrderNum = $_POST["premier_order_num"];
	$DernierOrderNum = $_POST["dernier_order_num"];
			
	
			
//Verifier que le range ne contient pas plus de 15 commandes
$NombredeCommande = $_POST["dernier_order_num"] - $_POST["premier_order_num"] + 1;
if ($NombredeCommande > 15){
	$errorMessage .= "<br> Too many orders in the range (max: 15 orders, currently: $NombredeCommande ) Please reduce the range to fetch less than 16 orders.";
	$PassValidation = false;	
}else{
//echo '<br>Nbr commande dans le range:'. 	$NombredeCommande;
}



	 
			if ($PassValidation){
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name from orders
			LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
			LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
			WHERE orders.order_num BETWEEN $_POST[premier_order_num] AND $_POST[dernier_order_num] AND LAB  IN(59,66,67) GROUP BY order_num ORDER BY order_num";
			}else{
			echo '<div align="center" style="position:absolute;left:550px;width:470px;border:1px solid black;background-color:#FF0033;" >Il y a des erreurs: ' . $errorMessage . '</div>';
			}
	}//End if order num is not empty
		
}//End if Verify Batch






if(isset($_POST[UpdateOrderNum])){	
//$NewOrderStatus =  $_POST["order_status"];
$UpdateDetails  = "";

foreach( $_POST[UpdateOrderNum] as $the_order_num ){	
	//First we need to insert in status_history to keep a track of what has been updated
	$todayDate 			= date("Y-m-d g:i a");// current date
	$order_date_shipped = date("Y-m-d");// current date
	$datedujour 		= date("Y-m-d");// current date
	$currentTime 		= time($todayDate); //Change date into time
	$timeAfterOneHour 	= $currentTime-((60*60)*4);
	$datecomplete 		= date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip					= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$acces_id 			= $_SESSION["access_admin_id"];
	$provient_de  	    = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser      		= $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
	$ip2 		  		= $_SERVER['HTTP_X_FORWARDED_FOR'];
	if (strlen($the_order_num)==5)
	{
		$nomTray = 'tray_num'. '/'. 	$the_order_num;	
		$TrayValue = $_POST[$nomTray];
		
		//Vérifier si la  commande est demandée 'Remote Edging' Si oui, on ne la note pas comme 'envoyée au laboratoire de Saint-Catharines'
		$queryJobType  = "SELECT job_type FROM extra_product_orders WHERE category='Edging' AND order_num =  $the_order_num";
		$resultJobType = mysql_query($queryJobType)		or die ('Could not insert because: ' . mysql_error());
		$DataJobType   = mysql_fetch_array($resultJobType);
		$JobType       = $DataJobType[job_type];
		
		//echo '<br>Job type: '.$JobType;
		
		if ($JobType <>'remote edging'){
			if($_SESSION["accessid"]<>2){
				$queryUpdate	 = "UPDATE orders SET frame_sent_saintcath ='yes', date_frame_sent_saintcath='$datedujour' WHERE order_num = $the_order_num";
				$resultUpdate=mysql_query($queryUpdate)		or die ('Could not insert because: ' . mysql_error());
			}
		
		//$DisplayUpdateDetail = true;
		}
		 ?>
   
    <script type="text/javascript">
	window.open( "/admin/fastPrintHbc.php?order_num=<?php echo $the_order_num; ?>" )
	</script>
	
	<?php
		
	}
	
		
}//End for each
	
	
}//End if Update Status
?>

<html>
<head>
<title>HBC Fast Printing Tool</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
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
           

<form  method="post" name="verification" id="verification" action="edll_fast_print.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">HBC Fast Printing Tool</font></b></td>
            	</tr>
                
                

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
                        
                        <br><br><br><br><div><strong>1-</strong>Please type your order number separated by a comma</div>					
						<textarea cols="20" name="order_num" style="font-size:16px;"  rows="10" id="order_num" class="formField"><?php if($_POST[rpt_search]=="Verifier"){
					 echo $Order_Num_Sans_Espace ;}  ?></textarea><div align="center"><input style="font-size:14px;" name="rpt_search" type="submit" id="rpt_search" value="Verifier" class="formField"></div></td>					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
	
			</table>
</form>





<form  method="post" name="update_status" id="update_status" action="edll_fast_print.php">
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
<td align=\"center\">&nbsp;</td>
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
						case 'job started':				$list_order_status = "Surfacing";				    break;
						case 'in coating':				$list_order_status = "In Coating";					break;
						case 'in mounting':				$list_order_status = "In Mounting";					break;
						case 'in edging':				$list_order_status = "In Edging";					break;
						case 'order completed':			$list_order_status = "Order Completed";				break;
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
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Redo";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";		break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";break;
				}		
	
          
			
			
			//Commande déja shippé, on disable la case a cocher
			if ($list_order_status=='Cancelled'){
				 echo "<td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">&nbsp;</td>
				       <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$order_date</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
					   <td  bgcolor=\"#FF0000\" style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>";
			}else{
			$OrderAbleToUpdate +=1;
			 echo "<td align=\"center\"><strong><input name=\"UpdateOrderNum[]\"  id=\"UpdateOrderNum[]\"  alt=\"Check this order to update the status\" value=\"$listItem[order_num]\" checked=\"checked\"  type=\"checkbox\"/></strong></td>                   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_num]</td> 
			       <td style=\"font-size:16px;\"  align=\"center\">$listItem[tray_num]</td>
			       <td style=\"font-size:16px;\"  align=\"center\">$order_date</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[lab_name]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[company]</td>
				   <td style=\"font-size:16px;\"  align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>";
			}
			
		

		if ($list_order_status=='Cancelled'){
		echo "<td style=\"font-size:16px;\"   bgcolor=\"#FF0000\" align=\"center\">$list_order_status</td>";
		}else{
		echo "<td style=\"font-size:16px;\"  align=\"center\">$list_order_status</td>";
		}
           
		echo	"</tr>";
}//END WHILE


 if ($OrderAbleToUpdate > 0){
	echo  "<tr> 
	<td align=\"center\" colspan=\"11\" nowrap=\"nowrap\">";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Imprimer les commandes sélectionnées\" class=\"formField\"></tr>";
				  }else{
				  echo "<input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Imprimer les commandes sélectionnées\" class=\"formField\"></tr>";
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

</body>
</html>
