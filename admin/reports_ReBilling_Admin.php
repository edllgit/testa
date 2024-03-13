<?php 
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
//include("../labAdmin/export_functions.inc.php");

session_start();

include("admin_functions.inc.php");
include("../labAdmin/re_billing_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];
?>
<html>
<head>
<title>Direct Lens Admin Area Re-billing Statement</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["end_date", "start_date"]);
}

</script>

</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="form" action="reports_ReBilling_Admin.php" >
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="3"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo ' Re-billing statement'; ?> </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="30%"><div align="right">
						<?php echo $adm_datefr_txt; ?></div></td>
					<td width="45%"><input name="start_date" type="text" class="formField" id="start_date" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>size="11">
						<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="end_date" type="text" class="formField" id="end_date" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>size="11">
						</td>
					<td width="25%">&nbsp;</td>
				</tr>
                
                <tr bgcolor="#DDDDDD">
					<td nowrap><div align="right">
						<?php echo $adm_selectlab_txt; ?></div></td>
					<td align="left" nowrap><select name="main_lab" class="formField3" id="main_lab" >
					  <?php
	$query="SELECT * from labs		 GROUP BY lab_name";
	
	$result=mysql_query($query)
		or die ("Could not find lab list");
		echo "<option value=\"\"";
		echo " selected>Select a Main Lab</option>";
		if (mysql_num_rows($result)==0){
			echo " <option value=\"\" disabled>none available</option>";
		}
		else{
	while ($labList=mysql_fetch_array($result))
		{
		echo "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
		}//END WHILE
		mysql_free_result($result);
		}//END ELSE
?>
		        </select>
              
                &nbsp;&nbsp;Report type: 
              
                  <label>
                    <input type="radio" name="report_type" checked="true"  value="all" id="report_type_0">
                    All</label>
                  
                  <label>
                    <input type="radio" name="report_type" value="separate" id="report_type_1">
                    <a href="reports_ReBilling_Admin_separate.php">Separate</a></label>
                  <br>
                   
                           </td>
					<td align="left" nowrap>&nbsp;</td>
					</tr>
                    
                    	<tr>
					<td colspan="3"><div align="center">
					  <input name="submit" type="submit" id="submit" value="<?php echo $btn_preparestate_txt; ?>" class="formField3"><input name="stmt_search" type="hidden" id="stmt_search" value="Prepare Statements" class="formField3">
					  
				  </div></td>
					</tr>

			</table>
</form>
   <?php
      if($_POST[stmt_search]=="Prepare Statements"){
	
		$main_lab=$_POST[main_lab];
		
		$mlQuery="SELECT labs.lab_email,labs.lab_name,buying_levels.amount from labs
						LEFT JOIN (buying_levels) ON (buying_levels.buying_level=labs.buying_level) 
						WHERE labs.primary_key='$main_lab' ";
						
		$mlResult=mysql_query($mlQuery);
		$mlItem=mysql_fetch_array($mlResult);
		mysql_free_result($mlResult);
	
		$init_start_date=$_POST[start_date];
		$init_end_date=$_POST[end_date];
		
		$date=array();
		$date2=array();
		

		$start_date = $_POST[start_date];
		$end_date  = $_POST[end_date];
		
		if ($_POST[start_date]=="All")
			$start_date="1900-1-1";
		if ($_POST[end_date]=="All")
			$end_date="2100-1-1";
		
	if ($_POST[report_type]=='separate') {
	//Redirection vers une autre page qui ne traiterra que le cas 'Separate'
	$redir= "d1=" .$start_date . '&d2=' .$end_date . '&main_lab='.$_POST[main_lab];  
	?> 
	<script type="text/javascript">
<!--
window.location = "<?php echo constant('DIRECT_LENS_URL'); ?>/admin/reports_ReBilling_Admin_separate.php?<?php echo $redir; ?>"
//-->
</script>
    <?php
	$rptQuery="SELECT distinct(order_num), user_id,tray_num,lab,prescript_lab,eye,order_item_number,order_date_processed,order_date_shipped,
order_item_date,order_quantity,order_patient_first,order_patient_last,patient_ref_num,salesperson_id,order_product_name,order_product_id,order_product_index,
order_product_material,order_product_price,order_product_discount,order_shipping_cost,order_shipping_method,order_over_range_fee,order_product_type,
order_product_coating,order_product_photo,order_product_polar,order_status,order_total,currency,entry_fee from orders 
WHERE lab='$_POST[main_lab]'  AND order_status IN ('filled','cancelled')  AND order_date_shipped between '$start_date' and '$end_date' group by orders.prescript_lab desc";
	}else {
		$rptQuery="SELECT distinct(order_num), user_id,tray_num,lab,prescript_lab,eye,order_item_number,order_date_processed,order_date_shipped,
order_item_date,order_quantity,order_patient_first,order_patient_last,patient_ref_num,salesperson_id,order_product_name,order_product_id,order_product_index,
order_product_material,order_product_price,order_product_discount,order_shipping_cost,order_shipping_method,order_over_range_fee,order_product_type,
order_product_coating,order_product_photo,order_product_polar,order_status,order_total,currency,entry_fee from orders 
WHERE lab='$_POST[main_lab]'  AND order_status IN ('filled','cancelled')  AND order_date_shipped between '$start_date' and '$end_date' group by orders.order_num ORDER by order_num desc";
	}
	

	//echo $rptQuery;
	$rptResult=mysql_query($rptQuery);
	$order_num=mysql_num_rows($rptResult);
	$_SESSION["RPTQUERYRB"] = $rptQuery;
	if ($order_num!=0){?>
		
<div align="center"><form action="reports_ReBillingSendEmail.php" method="post">
</form></div>



     
<?php

	//CREATE EXPORT FILE//
	$today=date("Y-m-d");
	$filename="../admin/RebillingData". '-' . $today .".csv";
	$fp=fopen($filename, "w");
	
	$orderQuery="SELECT * from orders WHERE lab='$_POST[main_lab]'  AND order_status IN ('filled','cancelled') AND order_date_shipped between '$start_date' and '$end_date' ORDER by order_num desc";
	//echo $orderQuery;
	
	$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());
	$itemcount=mysql_num_rows($orderResult);
	
	while ($orderData=mysql_fetch_array($orderResult)){
	$outputstring=Export_Rebilling_Admin($orderData[order_num]);
	fwrite($fp,$outputstring);
		}
	mysql_free_result($orderResult);
	fclose($fp);


?>


				<?php 
				
				$today=date("Y-m-d");
				$filename="RebillingData". '-' . $today .".csv";

				echo "<div class=\"formField2\">".$adm_rbslab_txt." <b>".$mlItem[lab_name]."</b></div>";
				echo "<div class=\"formField2\">".$adm_daterange2_txt." <b> ".$_POST[start_date]." - ".$_POST[end_date]."</b></div>";
				echo "<div class=\"formField2\"><a style=\"text-decoration:none;\" href=\"$filename\"><b>Download Csv file</b></a></div>";
				echo'<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3"><tr bgcolor="#C5C5C5">';
				echo "<tr  bgcolor=\"#999999\"><td align=\"center\" colspan=\"11\" ><b>Orders</b></td></tr>";	
				echo '<td class="formFieldBold">'.$adm_ordernum2_txt.'</td>
                <td class="formFieldBold">'.$adm_orderdate2_txt.'</td>
                <td class="formFieldBold">'.$adm_shipdate2_txt.'</td>
				<td class="formFieldBold">'.  'CUSTOMER'. '</td>
                <td class="formFieldBold">'.$adm_patientrefnum2_txt.'</td>
                <td class="formFieldBold">'.$adm_fname2_txt.'</td>
                <td class="formFieldBold">'.$adm_lname2_txt.'</td>
				<td class="formFieldBold">'.'PRODUCT'.'</td>
                <td class="formFieldBold"align="right">TOTAL</td>
				<td class="formFieldBold" align="right">'.'TOTAL AFTER DSC'.'</td>
                <td class="formFieldBold" align="right">'.'TOTAL TO PAY'.'</td>
              </tr>';
			$total_after_dsc=0;
		while ($listItem=mysql_fetch_array($rptResult)){
			
			$CostAfterDiscounts = 0;
			$count++;
			$bgcolor=(($count%2)==1) ? "#FFFFFF" : "#DDDDDD";
			
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$formatted_processed_date=mysql_result($new_result,0,0);
			
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$formatted_shipped_date=mysql_result($new_result,0,0);
			
			
			
			
			if ($listItem[currency]=='CA')
			$Elab_Currency = 'e_lab_can_price';
			if ($listItem[currency]=='US')
			$Elab_Currency = 'e_lab_us_price';
			if ($listItem[currency]=='EU')
			$Elab_Currency = 'e_lab_us_price';
			if ($listItem[currency]=='EUR')
			$Elab_Currency = 'e_lab_us_price';
			
			//Définir le elab price (3ieme colonne, celle de droite)(PRICE TO PAY)
			if ($listItem[order_product_type]=='exclusive')//Rx
			{
			$queryElabPrice = "SELECT $Elab_Currency as theprice FROM exclusive where primary_key =" . $listItem[order_product_id];
			$rptElabPrice=mysql_query($queryElabPrice);
			$DataElabPrice=mysql_fetch_array($rptElabPrice);
			mysql_free_result($rptElabPrice);
			$elabPrice = $DataElabPrice['theprice'];
			
			
			/*//Vérifier s'il y a un over range PAS D'OVER RANGE POUR EAGLE
			$queryOverRange ="SELECT order_over_range_fee, order_shipping_cost from orders WHERE order_num = " . $listItem[order_num];
			$ResultOverRange=mysql_query($queryOverRange);
			$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$elabPrice =$elabPrice  + $DataOverRange[order_over_range_fee] ;
				}*/
				//Si on a pas les deux yeux, on divise la facture en deux
				if ($listItem[eye] != "Both") {
				$elabPrice = $elabPrice/2;
				}
			
			}else{
			//Commande Stock on cumulle les valeurs des différents produits dans la commande
			$queryStockOrders = "SELECT * from orders WHERE order_num = " . $listItem["order_num"];
				//echo '<br><br>'.$queryStockOrders ;
				$ResultStockOrders = mysql_query($queryStockOrders)	or die  ('I cannot select items because: ' . mysql_error());
				$Total_Elab_Stock_Order = 0;
					while ($DataStockOrders=mysql_fetch_array($ResultStockOrders)){
						//Pour chaque tuple dans orders,  on cummule le total de facture elab
						$queryPrixElab = "SELECT   $Elab_Currency  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
					//	echo '<br>'. $queryPrixElab;
						$resultPrixElab=mysql_query($queryPrixElab)	or die  ('I cannot select items because: ' . mysql_error());
						$DataPrixElab=mysql_fetch_array($resultPrixElab);
						//echo '<br>quantity'. $DataStockOrders["order_quantity"];
						//echo '<br> price:'. $DataPrixElab["$Elab_Currency"];
						$PrixElab = $DataPrixElab["$Elab_Currency"] *  $DataStockOrders["order_quantity"] ;
						//echo '<br> total :'. $PrixElab;
						
						$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
						
					}//End While	
					$elabPrice = $Total_Elab_Stock_Order;
					//	echo '<br><br> total pour cette commande :'. $Total_Elab_Stock_Order;	
						
			}//End IF
			
			
			
			
			
			
			$elabPrice = $elabPrice + $DataOverRange[order_shipping_cost] ;
			$elabPrice  = money_format('%.2n',($elabPrice));
			
			
			$queryCompany= "SELECT company, currency FROM ACCOUNTS WHERE user_id = '$listItem[user_id]'";
			$rptCompany=mysql_query($queryCompany);
			$DataCompany=mysql_fetch_array($rptCompany);
			mysql_free_result($rptCompany);
			$Company = $DataCompany['company'];
			$Currency= $DataCompany['currency'];
			
			
			
			if (($Currency == 'US') && ($listItem[order_product_type] =='exclusive'))
			{
				
				$QueryBasicPrice = "Select price  as basicPrice from exclusive where primary_key =" . $listItem[order_product_id];
				$rptBasicPrice=mysql_query($QueryBasicPrice);
				$DataBasicPrice=mysql_fetch_array($rptBasicPrice);
				mysql_free_result($rptBasicPrice);
				$BasicPrice = $DataBasicPrice['basicPrice'];
				//Valider s'il y a un over range
				$queryOverRange ="SELECT order_over_range_fee from orders WHERE order_num = " . $listItem[order_num];
				$ResultOverRange=mysql_query($queryOverRange);
				$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$BasicPrice =$BasicPrice  + $DataOverRange[order_over_range_fee] ;
				}
				
				if ($listItem[eye] != "Both") {
					$BasicPrice = $BasicPrice/2;
				}
						
			}
			
			
			
			if (($Currency == 'US') && ($listItem[order_product_type] !='exclusive'))//Stock product by tray or bulk
			{			
			$queryStockOrders = "SELECT * from orders WHERE order_num = " . $listItem["order_num"];
		//	echo '<br><br>'.$queryStockOrders ;
			$ResultStockOrders = mysql_query($queryStockOrders)	or die  ('I cannot select items because: ' . mysql_error());
			$Total_Elab_Stock_Order = 0;
				while ($DataStockOrders=mysql_fetch_array($ResultStockOrders)){
					//Pour chaque tuple dans orders,  on cummule le total de facture elab
					$queryPrixElab = "SELECT  price  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
					//echo '<br>'. $queryPrixElab;
					$resultPrixElab=mysql_query($queryPrixElab)	or die  ('I cannot select items because: ' . mysql_error());
					$DataPrixElab=mysql_fetch_array($resultPrixElab);
					//echo '<br>quantity'. $DataStockOrders["order_quantity"];
					//echo '<br> price:'. $DataPrixElab['price'];
					$PrixElab = $DataPrixElab['price'] *  $DataStockOrders["order_quantity"] ;
					//echo '<br> total :'. $PrixElab;
					
					$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
				}//End While	
				//	echo '<br><br> total pour cette commande :'. $Total_Elab_Stock_Order;
			$BasicPrice = $Total_Elab_Stock_Order;
											
			}//End IF
			
			
			
			if (($Currency == 'CA') && ($listItem[order_product_type] =='exclusive'))
			{
				$QueryBasicPrice = "Select price_can  as basicPrice from exclusive where primary_key =" . $listItem[order_product_id];
				$rptBasicPrice=mysql_query($QueryBasicPrice);
				$DataBasicPrice=mysql_fetch_array($rptBasicPrice);
				mysql_free_result($rptBasicPrice);
				$BasicPrice = $DataBasicPrice['basicPrice'];
				//Valider s'il y a un over range
				$queryOverRange ="SELECT order_over_range_fee from orders WHERE order_num = " . $listItem[order_num];
				$ResultOverRange=mysql_query($queryOverRange);
				$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$BasicPrice =$BasicPrice  + $DataOverRange[order_over_range_fee] ;
				}
				if ($listItem[eye] != "Both") {
					$BasicPrice = $BasicPrice/2;
				}			
			}
			
			
			
			
			if (($Currency == 'CA') && ($listItem[order_product_type] !='exclusive'))//Stock product by tray or bulk
			{
			$queryStockOrders = "SELECT * from orders WHERE order_num = " . $listItem["order_num"];
			$ResultStockOrders = mysql_query($queryStockOrders)	or die  ('I cannot select items because: ' . mysql_error());
			$Total_Elab_Stock_Order = 0;
				while ($DataStockOrders=mysql_fetch_array($ResultStockOrders)){
					//Pour chaque tuple dans orders,  on cummule le total de facture elab
					$queryPrixElab = "SELECT  price_can  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
					//echo '<br>'. $queryPrixElab;
					$resultPrixElab=mysql_query($queryPrixElab)	or die  ('I cannot select items because: ' . mysql_error());
					$DataPrixElab=mysql_fetch_array($resultPrixElab);
					$PrixElab = $DataPrixElab['price_can'] *  $DataStockOrders["order_quantity"]  ;
					$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
				}//End While	
			$BasicPrice = $Total_Elab_Stock_Order;						
			}//End IF
			
			
				if (($Currency == 'EU') && ($listItem[order_product_type] =='exclusive'))
			{
				
				$QueryBasicPrice = "Select price_eur  as basicPrice from exclusive where primary_key =" . $listItem[order_product_id];
				$rptBasicPrice=mysql_query($QueryBasicPrice);
				$DataBasicPrice=mysql_fetch_array($rptBasicPrice);
				mysql_free_result($rptBasicPrice);
				$BasicPrice = $DataBasicPrice['basicPrice'];
				//Valider s'il y a un over range
				$queryOverRange ="SELECT order_over_range_fee from orders WHERE order_num = " . $listItem[order_num];
				$ResultOverRange=mysql_query($queryOverRange);
				$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$BasicPrice =$BasicPrice  + $DataOverRange[order_over_range_fee] ;
				}
				
				if ($listItem[eye] != "Both") {
					$BasicPrice = $BasicPrice/2;
				}
						
			}
			
			

		
			if (($Currency == 'EUR') && ($listItem[order_product_type] =='exclusive'))
			{
				$QueryBasicPrice = "Select price_eur  as basicPrice from exclusive where primary_key =" . $listItem[order_product_id];
				$rptBasicPrice=mysql_query($QueryBasicPrice);
				$DataBasicPrice=mysql_fetch_array($rptBasicPrice);
				mysql_free_result($rptBasicPrice);
				$BasicPrice = $DataBasicPrice['basicPrice'];
				//Valider s'il y a un over range
				$queryOverRange ="SELECT order_over_range_fee from orders WHERE order_num = " . $listItem[order_num];
				$ResultOverRange=mysql_query($queryOverRange);
				$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$BasicPrice =$BasicPrice  + $DataOverRange[order_over_range_fee] ;
				}
				
				if ($listItem[eye] != "Both"){
					$BasicPrice = $BasicPrice/2;
				}
						
			}
			
		
			$BasicPrice  = money_format('%.2n',($BasicPrice));
			
			$queryAddDsc = "SELECT additional_dsc, discount_type, order_product_type, currency FROM ORDERS WHERE order_num = " . $listItem[order_num];
			$rptAddDsc=mysql_query($queryAddDsc);
			$DataAddDsc=mysql_fetch_array($rptAddDsc);
			mysql_free_result($rptAddDsc);
			
			if ($DataAddDsc[additionnal_dsc] > 0)
			$CostAfterDiscounts = $DataAddDsc[additionnal_dsc] ;
			
			if ($DataAddDsc[order_product_type] =='exclusive'){//Commande RX
			$totalWithShipping=money_format('%.2n',($listItem[order_total] + $listItem[order_shipping_cost]));  
			}else{
			//Commande Stock
				 if ($DataAddDsc[currency]=='US')
				 $elabCurrency = "e_lab_us_price";
				 if ($DataAddDsc[currency]=='CA')
				 $elabCurrency = "e_lab_can_price";
				 if ($DataAddDsc[currency]=='EU')
				 $elabCurrency = "e_lab_us_price";
				 if ($DataAddDsc[currency]=='EUR')
				 $elabCurrency = "e_lab_us_price";
			
			
				$queryStockOrders = "SELECT * from orders WHERE order_num = " . $listItem["order_num"];
			//	echo '<br><br>'.$queryStockOrders ;
				$ResultStockOrders = mysql_query($queryStockOrders)	or die  ('I cannot select items because: ' . mysql_error());
				$Total_Elab_Stock_Order = 0;
					while ($DataStockOrders=mysql_fetch_array($ResultStockOrders)){
						//Pour chaque tuple dans orders,  on cummule le total de facture elab
						$queryPrixElab = "SELECT   $elabCurrency  from prices  WHERE product_name = '". $DataStockOrders["order_product_name"] . "'"; 
					//	echo '<br>'. $queryPrixElab;
						$resultPrixElab=mysql_query($queryPrixElab)	or die  ('I cannot select items because: ' . mysql_error());
						$DataPrixElab=mysql_fetch_array($resultPrixElab);
					//	echo '<br>quantity'. $DataStockOrders["order_quantity"];
					//	echo '<br> price:'. $DataPrixElab["$elabCurrency"];
						$PrixElab = $DataPrixElab["$elabCurrency"] *  $DataStockOrders["order_quantity"] ;
					//	echo '<br> total :'. $PrixElab;
						
						$Total_Elab_Stock_Order = $Total_Elab_Stock_Order +  $PrixElab;
					}//End While	
					//	echo '<br><br> total pour cette commande :'. $Total_Elab_Stock_Order;
				$totalWithShipping=money_format('%.2n',($Total_Elab_Stock_Order + $listItem[order_shipping_cost]));  
					 
			}//End IF product is not an exclusive product
			
			
			
			
			echo "<tr bgcolor=\"$bgcolor\"><td>".$listItem[order_num]."</td>";
			echo "<td>".$formatted_processed_date."</td>";
			echo "<td>".$formatted_shipped_date."</td>";
			echo "<td>".$Company."</td>";
			echo "<td>".$listItem[patient_ref_num]."</td>";
			echo "<td>".$listItem[order_patient_first]."</td>";
			echo "<td>".$listItem[order_patient_last]."</td>";
			echo "<td>".$listItem[order_product_name]."</td>";
			echo "<td align=\"right\">".$BasicPrice."</td>";
			echo "<td align=\"right\">". $totalWithShipping."</td>";

			$wholesale_total=money_format('%.2n',($totalWithShipping+$mlItem[amount]));
			$running_total+=$wholesale_total;
			$total_elab+=$elabPrice;
			$total_after_dsc +=$BasicPrice;
			 echo "<td align=\"right\"><b>". $elabPrice."</b></td></tr>";
			}//END WHILE
			mysql_free_result($rptResult);
										  
										  
					echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\">
			<td colspan=\"4\" align=\"right\"></td>
			<td colspan=\"4\" align=\"right\">".'TOTAL'."</td>
			
			<td align=\"right\">".money_format('%.2n',($total_after_dsc))."$</td>
			<td align=\"right\">".money_format('%.2n',($running_total))."$</td>
			<td align=\"right\">".money_format('%.2n',($total_elab)). "$</td>
			</tr>";						  
										  
										  
										  
										  
		echo "<tr  bgcolor=\"#999999\"><td align=\"center\" colspan=\"11\"><b>Credits</b></td></tr>";	
		echo "<tr bgcolor=\"#CCCCCC\">
		<td class=\"formFieldBold\">ORDER NUM</td>";
		echo "<td class=\"formFieldBold\">CREDIT DATE</td>";
		echo "<td class=\"formFieldBold\">MEMO CREDIT NUM</td>";
		echo "<td class=\"formFieldBold\">COMPANY</td>";
		echo "<td class=\"formFieldBold\">CREDIT/DEBIT</td>";
		echo "<td class=\"formFieldBold\">PATIENT FIRST</td>";
		echo "<td class=\"formFieldBold\">PATIENT LAST</td>";
		echo "<td class=\"formFieldBold\">&nbsp;</td>"; 
		echo "<td class=\"formFieldBold\" align=\"right\">&nbsp;</td>";
		echo "<td class=\"formFieldBold\" align=\"right\">&nbsp;</td>";
			echo "<td class=\"formFieldBold\" align=\"right\">AMOUNT</td>";
							  
										  
			//Ajouter  credits rebilling	
			$queryUserid= "Select user_id from accounts WHERE main_lab='$_POST[main_lab]'";
			$rptUserID=mysql_query($queryUserid);
			$lesUserID = '(';
			$count = 0;
			while ($DataUserID=mysql_fetch_array($rptUserID)){
			if ($count > 0)
			$lesUserID .= ",";
			$lesUserID .=  "'". $DataUserID[user_id] . "'";
			$count +=1;
			}
			$lesUserID .= ')';
			
			$totalCredit = 0;	  
										  
			$queryRebilling= "Select * from memo_credits_rebilling WHERE mcred_acct_user_id  IN $lesUserID AND mcred_date BETWEEN '$start_date' and '$end_date' ORDER BY mcred_date  ";
			$rptRebilling=mysql_query($queryRebilling);
			
			
			while ($DataRebilling=mysql_fetch_array($rptRebilling)){
			
			echo "<tr bgcolor=\"$bgcolor\"><td>".$DataRebilling[mcred_order_num]."</td>";
			echo "<td>".$DataRebilling[mcred_date]."</td>";
			echo "<td>$DataRebilling[mcred_memo_num]</td>";
			echo "<td>".$Company."</td>";
			echo "<td>".$DataRebilling[mcred_cred_type]."</td>";
			echo "<td>".$DataRebilling[patient_first_name]."</td>";
			echo "<td>".$DataRebilling[patient_last_name]."</td>";
			echo "<td>".$DataRebilling[order_product_name]."</td>";
			echo "<td>&nbsp;</td>";
			echo "<td>&nbsp;</td>";
			echo "<td align=\"right\">";
			if ($DataRebilling[mcred_cred_type] == 'credit')
			echo '-';
			if ($DataRebilling[mcred_cred_type] == 'debit')
			echo '+';
			echo $DataRebilling[mcred_abs_amount];
			echo "</td>";
			
			
			
				if ($DataRebilling[mcred_cred_type]=='debit')
				{
				$totalCredit = $totalCredit - $DataRebilling[mcred_abs_amount];
				}
				
				if ($DataRebilling[mcred_cred_type]=='credit')
				{	
				$totalCredit = $totalCredit +$DataRebilling[mcred_abs_amount];			  
				}	
									  
			}//End while
			mysql_free_result($rptRebilling);							  
										  
	
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\">
			<td colspan=\"4\" align=\"right\"></td>
			<td colspan=\"4\" align=\"right\">TOTAL CREDITS</td>		
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">".money_format('%.2n',($totalCredit)). "$</td>
			</tr>";		

				
			echo '<tr><td>&nbsp;</td></tr>';
				
				
				
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\">
			<td colspan=\"4\" align=\"right\"></td>
			<td colspan=\"4\" align=\"right\">".'TOTAL ORDERS'."</td>		
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">".money_format('%.2n',($total_elab)). "$</td>
			</tr>";
			
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\">
			<td colspan=\"4\" align=\"right\"></td>
			<td colspan=\"4\" align=\"right\">". ' --- TOTAL CREDITS'."</td>		
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">".money_format('%.2n',($totalCredit)). "$</td>
			</tr>";
				
				
			$InvoiceAfterCredit = 	$total_elab - $totalCredit;
				
										  
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\">
			<td colspan=\"4\" align=\"right\"></td>
			<td colspan=\"4\" align=\"right\">".$adm_totalinvoice2_txt."</td>		
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">&nbsp;</td>
			<td align=\"right\">".money_format('%.2n',($InvoiceAfterCredit)). "$</td>
			</tr></table>";
	  }//IN IF ORDER NUM
}
?>
</td>
	  </tr>
</table>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
