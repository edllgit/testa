<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
?>
<?php
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("../includes/pw_functions.inc.php");
include("../includes/sales_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$user_id=$_SESSION["sessionUser_Id"];
$company=stripslashes($_SESSION["sessionUserData"]["company"]);
$salesQuery="SELECT * from salespeople where acct_user_id = '$user_id' ORDER BY removed, last_name, first_name";//find salespeople list for this account
$salesResult=mysql_query($salesQuery)
	or die ('I cannot select items because: ' . mysql_error()."<br>".$salesQuery);
$salesCount=mysql_num_rows($salesResult);


if(($_POST["order_search"]=="Display Report")||($_POST["downloadRpt"] == "Download Report")){//build the report
	switch($_POST["report_type"]){
		case "basis":
			$heading=$company . " Salesperson Report - All Orders";
		break;
		case "exclusive":
			$heading=$company . " Salesperson Report - Exclusive Products Orders";
		break;
		case "AR":
			$heading=$company . " Salesperson Report - AR Orders";
		break;
		case "totalsales":
			$heading=$company . " Salesperson Report - Total Sales";
		break;
	}
	$rptQuery="SELECT accounts.user_id as user_id, accounts.company, orders.order_num as order_num, orders.order_total, orders.order_item_date, orders.order_status, orders.salesperson_id, orders.order_product_type, orders.order_product_name, orders.order_product_coating, salespeople.sales_id, salespeople.last_name, salespeople.first_name, salespeople.removed, exclusive.collection from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

	LEFT JOIN (salespeople) ON (orders.salesperson_id = salespeople.sales_id) 

	LEFT JOIN (exclusive) ON (orders.order_product_name = exclusive.product_name) 

	WHERE orders.user_id='$user_id' AND orders.order_status!='basket' AND orders.order_status!='canceled' AND orders.order_product_type='exclusive' AND salespeople.removed!='yes'";

	$_POST[date_from]=ucfirst($_POST[date_from]);
	$_POST[date_to]=ucfirst($_POST[date_to]);
	
	if ($_POST[salesperson] != "All"){
		$rptQuery.=" AND salespeople.sales_id = '$_POST[salesperson]'";
		$heading.=" for Salesperson ID " . $_POST[salesperson];//add sales id to report heading
	}
	
	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_item_date between '$date_from' and '$date_to'";
		$heading.=" for date range: " . $_POST[date_from] . " - " . $_POST[date_to];//add date range to report heading
	}

	$rptQuery.=" GROUP BY orders.order_num desc ORDER BY salespeople.last_name, salespeople.first_name";
}
$_SESSION["rptQuery"]=$rptQuery;
$_SESSION["heading"]=$heading;

//below is order report data
$rptBasisData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
            	<tr bgcolor=\"#17A2D2\">
            		<td colspan=\"5\" class=\"tableHead\"><div align=\"center\">$heading</div></td>
           		</tr>";
$rptExclusiveData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
            	<tr bgcolor=\"#17A2D2\">
            		<td colspan=\"12\" class=\"tableHead\"><div align=\"center\">$heading</div></td>
           		</tr>";
$rptARData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
            	<tr bgcolor=\"#17A2D2\">
            		<td colspan=\"9\" class=\"tableHead\"><div align=\"center\">$heading</div></td>
           		</tr>";
$rptTotalData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
            	<tr bgcolor=\"#17A2D2\">
            		<td colspan=\"9\" class=\"tableHead\"><div align=\"center\">$heading</div></td>
           		</tr>";

if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)
		or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
	$orderCount=mysql_num_rows($rptResult);
	$rptQuery="";
}
			
if ($orderCount != 0){//if there are some orders for this account, build the report
	$outputstring=$company . " Salesperson Report" . chr(13);

	$outputstring.="Sales ID".chr(9)."Last Name".chr(9)."First Name".chr(9)."Infocus".chr(9)."My World".chr(9)."Precision".chr(9)."Vision Pro".chr(9)."Vision Pro Poly".chr(9)."Generation".chr(9)."TrueHD".chr(9)."$Easy Fit HD".chr(9)."Other".chr(9)."AR Orders".chr(9)."Total Orders".chr(13);



  $rptBasisData.= "<tr bgcolor=\"#E7F2FF\">
  <td align=\"center\"  class=\"formCell\">Sales ID</td>
  <td align=\"center\"  class=\"formCell\">Last Name</td>
  <td align=\"center\"  class=\"formCell\">First Name</td>
  <td align=\"center\"  class=\"formCell\">AR Orders</td>
  <td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
  $rptExclusiveData.= "<tr bgcolor=\"#E7F2FF\">
  <td align=\"center\"  class=\"formCell\">Sales ID</td>
  <td align=\"center\"  class=\"formCell\">Last Name</td>
  <td align=\"center\"  class=\"formCell\">Infocus</td>
  <td align=\"center\"  class=\"formCell\">My World</td>
  <td align=\"center\"  class=\"formCell\">Precision</td>
  <td align=\"center\"  class=\"formCell\">Vision Pro</td>
  <td align=\"center\"  class=\"formCell\">Vision Pro Poly</td>
  <td align=\"center\"  class=\"formCell\">Generation</td>
  <td align=\"center\"  class=\"formCell\">TrueHD</td>
  <td align=\"center\"  class=\"formCell\">Easy Fit HD</td>
  <td align=\"center\"  class=\"formCell\">Other</td>
<td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
  $rptARData.= "<tr bgcolor=\"#E7F2FF\">
  <td align=\"center\"  class=\"formCell\">Sales ID</td>
  <td align=\"center\"  class=\"formCell\">Last Name</td>
  <td align=\"center\"  class=\"formCell\">First Name</td>
  <td align=\"center\"  class=\"formCell\">AR Orders</td>
  <td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
  $rptTotalData.= "<tr bgcolor=\"#E7F2FF\">
  <td align=\"center\"  class=\"formCell\">Sales ID</td>
  <td align=\"center\"  class=\"formCell\">Last Name</td>
  <td align=\"center\"  class=\"formCell\">First Name</td>
  <td align=\"center\"  class=\"formCell\">AR Orders</td>
  <td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
	$sales_idTotal=0;			  
	$ARTotal=0;			  
	$InfocusTotal=0;			  
	$InnovativeTotal=0;			  
	$PrecisionTotal=0;			  
	$VPTotal=0;			  
	$VPPolyTotal=0;			  
	$GenTotal=0;			  
	$IceTotal=0;			  
	$EasyFitTotal=0;			  
	$OtherTotal=0;
	$prodTest=array("infocus" => "Infocus", "innovative" => "My World", "precision" => "Precision", "vision_pro" => "Vision Pro", "vision_pro_poly" => "Vision Pro Poly", "generation" => "Generation", "TrueHD" => "TrueHD", "Easy_Fit_HD" => "Easy Fit HD", "other" => "Other");//create exclusive products array
				  
	while ($listItem=mysql_fetch_array($rptResult)){// 1st time around
		if(!isset($currentSales_ID)){
			$currentSales_ID=$listItem[sales_id];
			$last_name=$listItem[last_name];
			$first_name=$listItem[first_name];
			foreach($prodTest as $key => $value){//step thru exclusive products array and check for product type
					if($value == $listItem[collection]){
						switch($key){
							case "infocus":
								$InfocusTotal=bcadd($InfocusTotal, $listItem[order_total], 2);
							break;
							case "innovative":
								$InnovativeTotal=bcadd($InnovativeTotal, $listItem[order_total], 2);
							break;
							case "precision":
								$PrecisionTotal=bcadd($PrecisionTotal, $listItem[order_total], 2);
							break;
							case "vision_pro_poly":
								$VPPolyTotal=bcadd($VPPolyTotal, $listItem[order_total], 2);
							break;
							case "vision_pro":
								$VPTotal=bcadd($VPTotal, $listItem[order_total], 2);
							break;
							case "Generation":
								$GenTotal=bcadd($GenTotal, $listItem[order_total], 2);
							break;
							case "TrueHD":
								$IceTotal=bcadd($IceTotal, $listItem[order_total], 2);
							break;
							case "Easy Fit HD":
								$EasyFitTotal=bcadd($EasyFitTotal, $listItem[order_total], 2);
							break;
							case "other":
								$OtherTotal=bcadd($OtherTotal, $listItem[order_total], 2);
							break;
						}
					}
				}
			
			$AR_test = strpos($listItem[order_product_coating], " AR");// add the order totals in for the 1st time
			if($AR_test !== false){ /* if AR product, add to AR total */
				$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
			}
			$sales_idTotal=bcadd($sales_idTotal,$listItem[order_total], 2);
		}
		elseif($currentSales_ID!=$listItem[sales_id]){// if it's not the 1st time around the sales id has changed, print the totals then clear them
			$sales_idTotal=money_format('%.2n',$sales_idTotal);
			$ARTotal=money_format('%.2n',$ARTotal);
			$outputstring.="$currentSales_ID".chr(9)."$last_name".chr(9)."$first_name".chr(9)."$InfocusTotal".chr(9)."$InnovativeTotal".chr(9)."$PrecisionTotal".chr(9)."$VPTotal".chr(9)."$VPPolyTotal".chr(9)."$GenTotal".chr(9)."$IceTotal".chr(9)."$EasyFitTotal".chr(9)."$OtherTotal".chr(9)."$ARTotal".chr(9)."$sales_idTotal".chr(13);
			$rptBasisData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr>";
			$rptExclusiveData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">\$$InfocusTotal</td><td align=\"center\"  class=\"formCell\">\$$InnovativeTotal</td><td align=\"center\"  class=\"formCell\">\$$PrecisionTotal</td><td align=\"center\"  class=\"formCell\">\$$VPTotal</td><td align=\"center\"  class=\"formCell\">\$$VPPolyTotal</td><td align=\"center\"  class=\"formCell\">\$$GenTotal</td><td align=\"center\"  class=\"formCell\">\$$IceTotal</td><td align=\"center\"  class=\"formCell\">\$$EasyFitTotal</td><td align=\"center\"  class=\"formCell\">\$$OtherTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr>";
			$rptARData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr>";
			$rptTotalData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr>";
			$sales_idTotal=0;
			$ARTotal=0;
			$InfocusTotal=0;			  
			$InnovativeTotal=0;			  
			$PrecisionTotal=0;			  
			$VPTotal=0;			  
			$VPPolyTotal=0;			  
			$GenTotal=0;			  
			$IceTotal=0;			  
			$EasyFitTotal=0;			  
			$OtherTotal=0;
			$sales_idTotal=bcadd($sales_idTotal, $listItem[order_total], 2);// add in the NEW sales totals
			foreach($prodTest as $key => $value){
					if($value == $listItem[collection]){
						switch($key){
							case "infocus":
								$InfocusTotal=bcadd($InfocusTotal, $listItem[order_total], 2);
							break;
							case "innovative":
								$InnovativeTotal=bcadd($InnovativeTotal, $listItem[order_total], 2);
							break;
							case "precision":
								$PrecisionTotal=bcadd($PrecisionTotal, $listItem[order_total], 2);
							break;
							case "vision_pro_poly":
								$VPPolyTotal=bcadd($VPPolyTotal, $listItem[order_total], 2);
							break;
							case "vision_pro":
								$VPTotal=bcadd($VPTotal, $listItem[order_total], 2);
							break;
							case "Generation":
								$GenTotal=bcadd($GenTotal, $listItem[order_total], 2);
							break;
							case "TrueHD":
								$IceTotal=bcadd($IceTotal, $listItem[order_total], 2);
							break;
							case "Easy Fit HD":
								$EasyFitTotal=bcadd($EasyFitTotal, $listItem[order_total], 2);
							break;
							case "other":
								$OtherTotal=bcadd($OtherTotal, $listItem[order_total], 2);
							break;
						}
					}
				}

			$AR_test = strpos($listItem[order_product_coating], " AR");
			if($AR_test !== false){ /* if AR product, add to AR total */
				$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
			}
			$currentSales_ID=$listItem[sales_id];
			$last_name=$listItem[last_name];
			$first_name=$listItem[first_name];
		}else{// if there's no change in sales id
			$sales_idTotal=bcadd($sales_idTotal, $listItem[order_total], 2);
			foreach($prodTest as $key => $value){
					if($value == $listItem[collection]){
						switch($key){
							case "infocus":
								$InfocusTotal=bcadd($InfocusTotal, $listItem[order_total], 2);
							break;
							case "innovative":
								$InnovativeTotal=bcadd($InnovativeTotal, $listItem[order_total], 2);
							break;
							case "precision":
								$PrecisionTotal=bcadd($PrecisionTotal, $listItem[order_total], 2);
							break;
							case "vision_pro_poly":
								$VPPolyTotal=bcadd($VPPolyTotal, $listItem[order_total], 2);
							break;
							case "vision_pro":
								$VPTotal=bcadd($VPTotal, $listItem[order_total], 2);
							break;
							case "Generation":
								$GenTotal=bcadd($GenTotal, $listItem[order_total], 2);
							break;
							case "TrueHD":
								$IceTotal=bcadd($IceTotal, $listItem[order_total], 2);
							break;
							case "Easy Fit HD":
								$EasyFitTotal=bcadd($EasyFitTotal, $listItem[order_total], 2);
							break;
							case "other":
								$OtherTotal=bcadd($OtherTotal, $listItem[order_total], 2);
							break;
						}
					}
				}

			$AR_test = strpos($listItem[order_product_coating], " AR");
			if($AR_test !== false){ /* if AR product, add to AR total */
				$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
			}
		}
	}//END WHILE
	$sales_idTotal=money_format('%.2n',$sales_idTotal);
	$ARTotal=money_format('%.2n',$ARTotal);
	$outputstring.="$currentSales_ID".chr(9)."$last_name".chr(9)."$first_name".chr(9)."$InfocusTotal".chr(9)."$InnovativeTotal".chr(9)."$PrecisionTotal".chr(9)."$VPTotal".chr(9)."$VPPolyTotal".chr(9)."$GenTotal".chr(9)."$IceTotal".chr(9)."$EasyFitTotal".chr(9)."$OtherTotal".chr(9)."$ARTotal".chr(9)."$sales_idTotal".chr(13);
	$rptBasisData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr></table>";
	$rptExclusiveData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">\$$InfocusTotal</td><td align=\"center\"  class=\"formCell\">\$$InnovativeTotal</td><td align=\"center\"  class=\"formCell\">\$$PrecisionTotal</td><td align=\"center\"  class=\"formCell\">\$$VPTotal</td><td align=\"center\"  class=\"formCell\">\$$VPPolyTotal</td><td align=\"center\"  class=\"formCell\">\$$GenTotal</td><td align=\"center\"  class=\"formCell\">\$$IceTotal</td><td align=\"center\"  class=\"formCell\">\$$EasyFitTotal</td><td align=\"center\"  class=\"formCell\">\$$OtherTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr></table>";
	$rptARData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr></table>";
	$rptTotalData.="<tr><td align=\"center\"  class=\"formCell\">$currentSales_ID</td><td align=\"center\"  class=\"formCell\">$last_name</td><td align=\"center\"  class=\"formCell\">$first_name</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$sales_idTotal</td></tr></table>";
}else{
	$rptBasisData.="<tr><td colspan=\"3\" class=\"formCell\">No Orders Found</td></tr></table>";
	$rptExclusiveData.="<tr><td colspan=\"9\" class=\"formCell\">No Orders Found</td></tr></table>";
	$rptARData.="<tr><td colspan=\"3\" class=\"formCell\">No Orders Found</td></tr></table>";
	$rptTotalData.="<tr><td colspan=\"3\" class=\"formCell\">No Orders Found</td></tr></table>";
}//END USERCOUNT CONDITIONAL


//This is the main template wrapper variable:
$pageData = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td align=\"center\"><table width=\"917\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
      <tr>
        <td><img src=\"http://direct-lens-public.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg\" alt=\"Direct Lens\" width=\"917\" height=\"158\"></td>
      </tr>
      <tr>
        <td background=\"http://direct-lens-public.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg\">
		<table width=\"900\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
  <tr>
    <td width=\"215\" valign=\"top\"> <div id=\"leftColumn\">";//below displays left nav menu
	$pageData.="<p>&nbsp;</p>
		  <div id=\"menu\"><ul >
            <li><a href='login.php'>CUSTOMER LOGIN</a></li>
			<li><a href=\"labAdmin/\">LAB ADMIN LOGIN</a></li>
            <li><a href='requestAccount.php'>OPEN AN ACCOUNT</a></li>
            <li><a href='myAccount.php'>MY ACCOUNT</a></li>
			<li><a href='basket.php'>VIEW MY BASKET</a></li>
			<li><a href='order_history.php'>MY ORDER HISTORY</a></li>
			<li><a href='mySalespeople.php'>MY SALESPEOPLE</a></li>
			<li><a href='SalesAccount.php'>SALESPERSON REPORTS</a></li>
          </ul><br>
		 <ul >
            <li><a href='stock.php'>STOCK LENSES - BY TRAY</a></li>
			 <li><a href='stock_bulk.php'>STOCK LENSES - BULK</a></li>
            <li><a href='prescription.php'>PRESCRIPTION LENSES</a></li>
            <li><a href='price_lists.php'>PRICE LISTS</a></li>
          </ul><br>
		   <ul >
            <li ><a href='promotions/promo.pdf' target=\"_blank\">PROMOTIONS</a></li>
            <li ><a href='services.php'>SERVICES</a></li>
          </ul><br>
		  <ul >
            <li ><a href='contact.php'>CONTACT US</a></li>
            <li ><a href='index.php'>HOME PAGE</a></li>
            <li ><a href='logout.php'>LOGOUT</a></li>
          </ul>
		  </div>
          <p>&nbsp;</p></div></td>
    <td width=\"685\" valign=\"top\">";
if ($salesCount != 0){ /* if account has salespeople, display order report parameters form */
	$formData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
	<tr>
		<td colspan=\"6\" bgcolor=\"#17A2D2\" class=\"tableHead\"><div align=\"center\">
			$company Salesperson Report
		</div></td>
	</tr>
<form method=\"post\" name=\"goto_date\" id=\"goto_date\" action=\"SalesAccount.php\">
	<tr bgcolor=\"#E7F2FF\">
		<td class=\"formCellNosides\">
			Select Salesperson</td>
		<td class=\"formCellNosides\"><select name=\"salesperson\" id=\"salesperson\">
			<option value=\"All\">All Salespersons</option>";
	$query="SELECT sales_id, first_name, last_name from salespeople WHERE acct_user_id='$user_id' AND removed!='yes' ORDER BY last_name, first_name";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($salesList=mysql_fetch_array($result)){
		$formData.= "<option value=\"$salesList[sales_id]\""; if($_POST[salesperson]=="$salesList[sales_id]") $formData.="selected=\"selected\""; $formData.=">$salesList[sales_id] - $salesList[last_name], $salesList[first_name]</option>";
}
		$formData.="</select></td>
		<td colspan=\"5\" class=\"formCellNosides\" nowrap>Date From
						<input name=\"date_from\" type=\"text\" id=\"date_from\" value=\"";
						if(($_POST[date_from]!="All")&&($_POST[date_from]!="")) $formData.=$_POST[date_from]; else $formData.="All"; 
						$formData.="\" size=\"10\" />
							<a href=\"#\" onclick=\"cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;\" title=\"Popup calendar for quick date selection\" name=\"anchor1xx\" id=\"anchor1xx\"><img src=\"includes/popup_cal.gif\" width=\"14\" height=\"14\" hspace=\"2\" border=\"0\" align=\"absmiddle\" /></a> To <input name=\"date_to\" type=\"text\" id=\"date_to\" value=\"";
						if(($_POST[date_to]!="All")&&($_POST[date_to]!="")) $formData.=$_POST[date_to]; else $formData.="All"; 
						$formData.="\" size=\"10\" />
						<a href=\"#\" onclick=\"cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;\" title=\"Popup calendar for quick date selection\" name=\"anchor2xx\" id=\"anchor2xx\"><img src=\"includes/popup_cal.gif\" width=\"14\" height=\"14\" hspace=\"2\" border=\"0\" align=\"absmiddle\" /></a></td>
	</tr>
	<tr bgcolor=\"#E7F2FF\">
		<td class=\"formCellNosides\">
			Select Report Type</td>
		<td colspan=\"5\" class=\"formCellNosides\"><input name=\"report_type\" type=\"radio\" value=\"basis\"";
		if(($_POST[report_type]=="basis")||($_POST[report_type]=="")) $formData.=" checked=\"checked\""; 
		$formData.=">Basis&nbsp;&nbsp;&nbsp;<input name=\"report_type\" type=\"radio\" value=\"exclusive\"";
		if($_POST[report_type]=="exclusive")  $formData.=" checked=\"checked\"";
		$formData.=">Exclusive Products&nbsp;&nbsp;&nbsp;<input name=\"report_type\" type=\"radio\" value=\"AR\"";
		if($_POST[report_type]=="AR")  $formData.=" checked=\"checked\"";
		$formData.=">AR Products&nbsp;&nbsp;&nbsp;<input name=\"report_type\" type=\"radio\" value=\"totalsales\"";
		if($_POST[report_type]=="totalsales")  $formData.=" checked=\"checked\"";
		$formData.=">Total Sales</td>
	</tr>
	<tr bgcolor=\"#E7F2FF\">
		<td class=\"formStockBulk\" colspan=\"2\"><input name=\"order_search\" type=\"submit\" id=\"order_search\" value=\"Display Report\" class=\"formText\" /></td><td class=\"formStockBulk\">";
	switch($_POST[report_type]){
		case "basis":
			$_SESSION["printRpt"]=$rptBasisData;
		break;
		case "exclusive":
			$_SESSION["printRpt"]=$rptExclusiveData;
		break;
		case "AR":
			$_SESSION["printRpt"]=$rptARData;
		break;
		case "totalsales":
			$_SESSION["printRpt"]=$rptTotalData;
		break;
	}
		if($_POST[order_search]=="Display Report") 
			$formData.="<input name=\"printRpt\" type=\"button\" id=\"printRpt\" value=\"Print Report\" class=\"formText\" onclick=\"popup('printSalesRpt.php')\" />"; 
		$formData.="</td><td colspan=\"3\" class=\"formStockBulk\" align=\"right\"><input type=\"submit\" name=\"downloadRpt\" value=\"Download Report\" class=\"formText\" /></td>
	</tr></form>
</table>";
//Build Salespersons list
$salesData="<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
	<tr>
		<td colspan=\"4\" bgcolor=\"#17A2D2\" class=\"tableHead\"><div align=\"center\">
			Salespeople
		</div></td>
	</tr>
	<tr bgcolor=\"#E7F2FF\">
		<td class=\"formCell\">
			Sales ID		</td>
		<td class=\"formCell\">
			First Name		</td>
		<td class=\"formCell\">
			Last Name		</td>
		<td class=\"formCell\">
			Status		</td>
	</tr>";

//	$count=0;
	while($listData=mysql_fetch_array($salesResult)){
//	if(($count%2)==0)
//		$bgcolor="#ffffff";
//	else
//		$bgcolor="#E7F2FF";
	$salesData.="<tr>
		<td class=\"formCell\">$listData[sales_id]</td>
		<td class=\"formCell\">$listData[first_name]</td>
		<td class=\"formCell\">$listData[last_name]</td>
		<td class=\"formCell\">"; if($listData[removed]=="yes") $salesData.="removed"; else $salesData.="&nbsp;";
		$salesData.="</td>
	</tr>";
//	$count++;
}
$salesData.="</table>";
	
	
}else{
	$salesData.= "<p class=\"formText\" align=\"center\">This customer account has no salespeople registered with direct-lens.com.</p>";
	}
$salesData.="</td></tr></table>
		           </td>
      </tr>
      <tr>
        <td background=\"http://direct-lens-public.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg\"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>";
if ($_POST[downloadRpt] == "Download Report"){
	$success=download_report($outputstring);
	print $success;
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>



   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
<!--
function popup(url) 
{
 params  = 'width='+800;
 params += ', height='+800;
 params += ', top=0, left=0'
 params += ', fullscreen=yes';

 newwin=window.open(url,'windowname4', params);
 if (window.focus) {newwin.focus()}
 return false;
}
// -->
</script>

<SCRIPT LANGUAGE="JavaScript" SRC="includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>


</head>


<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
 <?php
//print $pageData;
print $formData;
switch($_POST[report_type]){
	case "basis":
		print $rptBasisData;
	break;
	case "exclusive":
		print $rptExclusiveData;
	break;
	case "AR":
		print $rptARData;
	break;
	case "totalsales":
		print $rptTotalData;
	break;
}

//print $salesData;
?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>