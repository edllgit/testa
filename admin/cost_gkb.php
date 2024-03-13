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
			WHERE prescript_lab=69 AND orders.order_num IN ($_POST[order_num])";
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
<form  method="post" name="verification" id="verification" action="cost_gkb.php">
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

<form  method="post" name="update_status" id="update_status" action="cost_gkb.php">
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
				<td align=\"center\"><strong>Qty</strong></td>
				<td align=\"center\"><strong>Amount per Pc(USD)</strong></td>
				<td align=\"center\"><strong>Total Amount (USD)</strong></td>
                <td align=\"center\"><strong>Fabriquant</strong></td>
	            </tr>
				<tr>";		
			
				  
$OrderAbleToUpdate = 0;
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$queryOrderFrom  = "SELECT order_product_id, order_from, order_product_name, prescript_lab, eye, order_product_coating, uv400 FROM orders WHERE order_num =" . $listItem[order_num];
			$resultOrderFrom = mysqli_query($con,$queryOrderFrom)		or die  ('<strong>Errors occured during the process: '. $rptQuery . mysqli_error($con));
			$DataOrderFrom   = mysqli_fetch_array($resultOrderFrom,MYSQLI_ASSOC);
			
	
	
switch ($DataOrderFrom[order_from]){
	case 'ifcclubca':  	 $queryCost = "SELECT cost_us, product_code, product_name, primary_key,coating,index_v, collection, cost_gkb_backup FROM ifc_ca_exclusive WHERE primary_key = " . $DataOrderFrom[order_product_id]; break;
	case 'safety':       $queryCost = "SELECT cost_us, product_code, product_name, primary_key,coating,index_v, collection, cost_gkb_backup  FROM safety_exclusive WHERE primary_key = " . $DataOrderFrom[order_product_id]; break; 
	case 'directlens':   $queryCost = "SELECT cost_us, product_code, product_name, primary_key ,coating,index_v, collection, cost_gkb_backup FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'lensnetclub':  $queryCost = "SELECT cost_us, product_code, product_name, primary_key,coating,index_v, collection, cost_gkb_backup  FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'aitlensclub':  $queryCost = "SELECT cost_us, product_code, product_name, primary_key,coating ,index_v, collection, cost_gkb_backup FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	case 'eye-recommend':$queryCost = "SELECT cost_us, product_code, product_name, primary_key,coating,index_v, collection, cost_gkb_backup  FROM exclusive WHERE primary_key = " 		. $DataOrderFrom[order_product_id]; break; 
	default: $queryCost = "" ; break; 
}
			
			if ($queryCost <> ""){
				//echo '<br>queryCost:'. $queryCost . '<br>';
				$resultCost = mysqli_query($con,$queryCost)		or die  ('<strong>Errors occured during the process: '. $queryCost . mysqli_error($con));
				$DataCost   = mysqli_fetch_array($resultCost,MYSQLI_ASSOC);
			}
			
	
		//Le produit est maintenant redirig� ailleur que vers GKB. on doit le laisser savoir � l'utilisateur	
		switch($DataCost[collection]){
			case 'Entrepot Sky':  		$Collection='GKB';	break;
			case 'Entrepot Promo':  	$Collection='GKB';	break;
			case 'Entrepot HKO':  		$Collection='HKO';	break;
			case 'NURBS sunglasses':  	$Collection='HKO';	break;
			case 'Entrepot Swiss':  	$Collection='SWISS';break;
			case 'Entrepot STC':  		$Collection='STC';	break;
			case 'Entrepot FT':  		$Collection='STC';	break;
			case 'Entrepot CSC':  		$Collection='STC';	break;
			case 'Entrepot KNR':  		$Collection='KNR';	break;	
			case 'Safety HKO':			$Collection='HKO';	break;
			case 'Safety STC':			$Collection='STC';	break;
		}

	
			
			if ($Collection<>'GKB'){
			 $DataCost[cost_us]=	$DataCost[cost_gkb_backup];
				//echo '<br>UTILISE COST GKB BACKUP';
			}else{
				//echo '<br>collection LAB:'. 	$Collection;
			}//END IF
	
			
			
			if (($DataCost[product_code] <> '') && ($DataOrderFrom[order_from]=="ifcclubca")){
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product_ifc.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}elseif (($DataCost[product_code] <> '') && ($DataOrderFrom[order_from]=="safety")){
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product_safety.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}else{
				$Code = '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/update_exclusive_product.php?pkey='.$DataCost[primary_key].'">' . $DataCost[product_code] . '</a>';	
			}
			
					
			$queryTint  = "SELECT tint FROM extra_product_orders WHERE category='Tint' AND order_num=".$listItem[order_num];
			$resultTint = mysqli_query($con,$queryTint) or die  ('<strong>Errors occured during the process: '. $queryTint . mysqli_error($con));
			$DataTint   = mysqli_fetch_array($resultTint,MYSQLI_ASSOC);
			$tint 		= $DataTint[tint];
	
	
			//Pour obtenir le prix par verre, on doit prendre le cost US, soustraire le traitement <br>
			//et diviser le r�sultat par 2,(SI la commande inclus les deux verres).
			
	//Si UV400 = On rajoute 1$ par verre
	
	//Prix des teintes
	//1.50   SOLID: 1.50$/verre GRADIENT:2.00$/verre
	//1.60   SOLID: 1.75$/verre GRADIENT:2.00$/verre
	//1.67   SOLID: 1.75$/verre GRADIENT:2.00$/verre
	//1.74   SOLID: 1.75$/verre GRADIENT:2.00$/verre
	
	//V�rification de s'il y a une teinte	Solid
	if (($tint=='Solid')&&($DataCost[index_v]=='1.50')){
		$ExtraTeinteParVerre = 1.50;
	}
	
	if (($tint=='Solid')&&($DataCost[index_v]=='1.60')){
		$ExtraTeinteParVerre = 	1.75;
	}
	
	if (($tint=='Solid')&&($DataCost[index_v]=='1.67')){
		$ExtraTeinteParVerre = 	1.75;
	}
	
	if (($tint=='Solid')&&($DataCost[index_v]=='1.74')){
		$ExtraTeinteParVerre = 	1.75;
	}
	
	//V�rification de s'il y a une teinte Gradient
	if (($tint=='Gradient')&&($DataCost[index_v]=='1.50')){
		$ExtraTeinteParVerre = 2.00;
	}
	
	if (($tint=='Gradient')&&($DataCost[index_v]=='1.60')){
		$ExtraTeinteParVerre = 	2.00;
	}
	
	if (($tint=='Gradient')&&($DataCost[index_v]=='1.67')){
		$ExtraTeinteParVerre = 	2.00;
	}
	
	if (($tint=='Gradient')&&($DataCost[index_v]=='1.74')){
		$ExtraTeinteParVerre = 	2.00;
	}
	
	
	
	
	elseif ($tint=='Gradient')
		$ExtraTeinte =	3.60;
	
			if ($DataOrderFrom[eye] == 'Both'){
				$Quantity  	  = 2; 	
				$PrixparVerre = $DataCost[cost_us]/2;
				//Partie UV400
				if ($DataOrderFrom[uv400]<>'')
				$PrixparVerre = $PrixparVerre+1;
				//Partie Teinte
				if ($ExtraTeinteParVerre<>0){
					$PrixparVerre = $PrixparVerre+$ExtraTeinteParVerre;	
				}
				
				$TotalVerres  = $PrixparVerre *  $Quantity;;
				$PrixparVerre = money_format('%.2n',$PrixparVerre);	
				$TotalVerres  = money_format('%.2n',$TotalVerres);	
			}else{
				$Quantity  = 1; 	
				$PrixparVerre = $DataCost[cost_us]/2;
				//Partie UV400
				if ($DataOrderFrom[uv400]<>'')
				$PrixparVerre = $PrixparVerre+1;
				//Partie Teinte
				if ($ExtraTeinteParVerre<>0){
					$PrixparVerre = $PrixparVerre+$ExtraTeinteParVerre;	
				}
				
				$TotalVerres  = $PrixparVerre * $Quantity;
				$PrixparVerre = money_format('%.2n',$PrixparVerre);	
				$TotalVerres  = money_format('%.2n',$TotalVerres);		
			}
	
	
	$CoutTaillage=0;//Prix par d�faut
	//V�rifier s'il y a eu du taillage fait par GKB
	//A)Est-ce que le nom du produit contient 'K-ONE'
	if (substr($listItem[order_product_name],0,5)=='K-ONE')
	{
	
		//B) Si le supplier= 'KUBIK ONE-CA'
		$querySupplier  = "SELECT supplier, job_type FROM extra_product_orders WHERE order_num=$listItem[order_num] AND category='Frame'";
		//echo '<br>'.$querySupplier.'<br>';
		$resultSupplier = mysqli_query($con,$querySupplier)		or die  ('<strong>Errors occured during the process: '. $querySupplier . mysqli_error($con));
		$DataSupplier   = mysqli_fetch_array($resultSupplier,MYSQLI_ASSOC);
		$Supplier 		= $DataSupplier[supplier]; 
		$JobType 		= $DataSupplier[job_type]; 
		
		//echo '<br>Supplier:' .$Supplier.'<br>'; 
		//C si le type de commande = 'Edge and Mount'
		if ($JobType =='Edge and Mount'){
			//GKB a fait le taillage, on doit afficher leur prix de taillage.
			$CoutTaillage = 2;	
		}//END IF
		
		
	}//END IF
	
	
	switch($listItem[collection]){
			
	
			
	}//END SWITCH
		
	


	if ($Collection=='GKB'){
		 echo "	<td style=\"font-size:16px;\" align=\"center\">$listItem[order_num]</td>
			   	<td style=\"font-size:16px;\" align=\"center\">$Code</td>
				<td style=\"font-size:16px;\" align=\"center\">$listItem[order_product_name]</td>
				<td style=\"font-size:16px;\" align=\"center\">$Quantity</td>
				<td style=\"font-size:16px;\" align=\"center\">$PrixparVerre$</td>
				<td style=\"font-size:16px;\" align=\"center\">$TotalVerres$</td>
				<td style=\"font-size:16px;\" align=\"center\">$Collection</td>
				</tr>";
	}else{
	 echo "		<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$listItem[order_num]</td>
			   	<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$Code</td>
				<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$listItem[order_product_name]</td>
				<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$Quantity</td>
				<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$PrixparVerre$</td>
				<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$TotalVerres$</td>
				<td style=\"font-size:16px;background-color:#FF0000\" align=\"center\">$Collection</td>
				</tr>";
		
	}//END IF			
	 



		
	
$TotalCostUS = $TotalCostUS +$TotalVerres ;	
		
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
$TotalCostUS = money_format('%.2n',$TotalCostUS);	
echo "<tr><td colspan=\"3\" align=\"right\" style=\"font-size:16px;\"><b>Total US:</b></td><td colspan=\"5\" align=\"center\" style=\"font-size:16px;\"><b>$TotalCostUS$</b></td></tr>"	;
   
					  
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