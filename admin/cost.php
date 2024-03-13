<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");


session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

$TotalCostCAD = 0;

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
			$rptQuery="SELECT accounts.user_id as user_id, accounts.company, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.order_date_processed, orders.order_date_shipped, orders.lab, orders.order_status,  orders.tray_num, orders.patient_ref_num, orders.order_patient_last, orders.order_patient_first, labs.primary_key as lab_key, labs.lab_name, orders.order_product_name, orders.prescript_lab FROM ORDERS
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
<form  method="post" name="verification" id="verification" action="cost.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Cost Verifying Tool</font></b></td>
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

<form  method="post" name="update_status" id="update_status" action="cost.php">
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
                <td align=\"center\"><strong>Order Number</strong></td>
				<td align=\"center\"><strong>Code</strong></td>
				<td align=\"center\"><strong>Product</strong></td>
				<td align=\"center\"><strong>Cost R.E. (US)</strong></td>
				<td align=\"center\"><strong>Cost L.E. (US)</strong></td>
                <td align=\"center\"><strong>Manufacturer</strong></td>
	            </tr>
				<tr>";		
			
				  
$OrderAbleToUpdate = 0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$queryOrderFrom  = "SELECT order_product_id, order_from, order_product_name, prescript_lab, eye FROM orders WHERE order_num =" . $listItem[order_num];
			$resultOrderFrom = mysqli_query($con,$queryOrderFrom)		or die  ('<strong>Errors occured during the process: '. $rptQuery . mysqli_error($con));
			$DataOrderFrom   = mysqli_fetch_array($resultOrderFrom,MYSQLI_ASSOC);
			
switch ($DataOrderFrom[order_from]){
	case 'ifcclubca':  	 $queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM ifc_ca_exclusive WHERE primary_key = " . $DataOrderFrom[order_product_id]; break;
	case 'safety':       $queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM safety_exclusive WHERE primary_key = " . $DataOrderFrom[order_product_id]; break; 
	case 'directlens':   $queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'lensnetclub':  $queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'aitlensclub':  $queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'eye-recommend':$queryCost = "SELECT cost,cost_us, product_code, product_name, primary_key FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	default: $queryCost = "" ; break; 
}
			
			if ($queryCost <> ""){
				$resultCost = mysqli_query($con,$queryCost)		or die  ('<strong>Errors occured during the process: '. $queryCost . mysqli_error($con));
				$DataCost   = mysqli_fetch_array($resultCost,MYSQLI_ASSOC);
			}
			
			switch($DataOrderFrom[prescript_lab]){
				case '10': $PrescriptLab = "Swiss";	 		 break;
				case  '3': $PrescriptLab = "Dlab";	 		 break;
				case '69': $PrescriptLab = "Essilor #1 Lab"; break;
				case '25': $PrescriptLab = "Central Lab";	 break;	
				case '57': $PrescriptLab = "Crystal";	     break;	
				default:   $PrescriptLab = " ";      	     break;
			}
			
			
			if (($DataCost[product_code] <> '') && ($DataOrderFrom[order_from]=="ifcclubca")){
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product_ifc.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}elseif (($DataCost[product_code] <> '') && ($DataOrderFrom[order_from]=="safety")){
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product_safety.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}else{
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}
			
			if ($DataOrderFrom[eye] <> 'Both'){
				$DataCost[cost] =$DataCost[cost] / 4;//Un seul oeil	
			}
		
			if ($DataOrderFrom[eye] == 'Both'){
				$CostUS_RE = $DataCost[cost_us]/2;
				$CostUS_RE = money_format('%.2n',$CostUS_RE);	
				
				$CostUS_LE = $DataCost[cost_us]/2;
				$CostUS_LE = money_format('%.2n',$CostUS_LE);	
			}
				
		
			 echo "<td style=\"font-size:16px;\" align=\"center\">$listItem[order_num]</td>
			   	   <td style=\"font-size:16px;\" align=\"center\">$Code</td>
				   <td style=\"font-size:16px;\" align=\"center\">$listItem[order_product_name]</td>
				   <td style=\"font-size:16px;\" align=\"center\">$CostUS_RE</td>
				   <td style=\"font-size:16px;\" align=\"center\">$CostUS_LE</td>
			 	   <td style=\"font-size:16px;\" align=\"center\">$PrescriptLab</td>";

		echo	"</tr>";
	
$TotalCostUS = $TotalCostUS +$DataCost[cost_us] ;	
		
}//END WHILE


 if ($OrderAbleToUpdate > 0){
	echo  "<tr> 
	<td align=\"center\" colspan=\"11\" nowrap=\"nowrap\">
                     <br><br><div style=\"font-size:14px;\"><strong>2-</strong> Select the Status that you want to apply to these orders</div>
                   <select style=\"font-size:14px;\" name=\"order_status\" id=\"order_status\" class=\"formField\">
					  <option value=\"processing\">Confirmed</option>
					  <option value=\"cancelled\">Cancelled</option>
					  <option value=\"on hold\">On Hold</option>
					  <option value=\"information in hand\">Info in Hand</option>
					  <option value=\"in coating\">In Coating</option>
					  <option value=\"in mounting\">In Mounting</option>
					  <option value=\"in edging\">In Edging</option>
					  <option value=\"interlab\">Interlab P</option>
					   <option value=\"interlab qc\">Interlab QC</option>
					  <option value=\"job started\">Surfacing</option>
					  <option value=\"in transit\">In Transit</option>
					  <option value=\"open\">Open</option>
					  <option value=\"order completed\">Order Completed</option>
					  <option value=\"order imported\">Order Imported</option>
					  <option value=\"re-do\">Redo</option>
					  <option value=\"filled\">Shipped</option>
					  <option value=\"waiting for frame\">Waiting for Frame</option>
					  <option value=\"waiting for lens\">Waiting for Lens</option>
					  <option value=\"waiting for shape\">Waiting for Shape</option>
					   <option value=\"scanned shape to swiss\">Scanned shape to Swiss</option>
					     <option value=\"verifying\">Verifying</option>
						   <option value=\"scanned shape to swiss\">Scanned shape to Swiss</option>
 </select><br><br><br>";

 
 
				  if ($OrderAbleToUpdate > 0){
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Update Status\" class=\"formField\"></tr>";
				  }else{
				  echo "<div style=\"font-size:14px;\"><strong>3-</strong>Update the status</div><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update Status\" class=\"formField\"></tr>";
				  }
				  
 }else{
 $noOrdertoUpdate =true;
 }		
 
 //Afficher totaux
echo "<tr><td colspan=\"3\" align=\"right\" style=\"font-size:16px;\"><b>Total US:</b></td><td colspan=\"3\" align=\"center\" style=\"font-size:16px;\"><b>$TotalCostUS$</b></td></tr>"	;
$TotalCostUS = money_format('%.2n',$TotalCostUS);	   
					  
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

?>

            

</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>