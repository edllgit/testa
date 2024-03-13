<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("re_billing_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["start_date", "end_date"]);
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
<form  method="post" name="goto_date" id="form" action="reports_ReBilling.php" >
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="3"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo 'Lab Rebilling Statement'; ?> </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="30%"><div align="right">
						<?php echo $adm_datefr_txt; ?></div></td>
					<td width="45%"><input name="start_date" type="text" class="formField" id="start_date" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>size="11">
					<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="end_date" type="text" class="formField" id="end_date" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>size="11">
					</td>
					<td width="25%"><input name="include_stock" type="checkbox" value="1" checked>Include Stock Orders</td>
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
						WHERE labs.primary_key='$lab_pkey' ";
				
		$mlResult=mysql_query($mlQuery);
		$mlItem=mysql_fetch_array($mlResult);
	
		$init_start_date=$_POST[start_date];
		$init_end_date=$_POST[end_date];
		
		$date=array();
		$date2=array();
		
		$date= explode("/", $_POST[start_date]);
		$start_date=$_POST[start_date];

		$date2= explode("/", $_POST[end_date]);
		$end_date=$_POST[end_date];
		
		if ($_POST[start_date]=="All")
			$start_date="1900-1-1";
		if ($_POST[end_date]=="All")
			$end_date="2100-1-1";
	if ($_POST['include_stock']){
			$rptQuery="SELECT * from orders WHERE lab='$lab_pkey'  AND order_status='filled' AND order_date_shipped between '$start_date' and '$end_date' ORDER by order_num desc";}
	else{
		$rptQuery="SELECT * from orders WHERE lab='$lab_pkey'  AND order_status='filled' AND order_product_type='exclusive' AND (order_date_shipped between '$start_date' and '$end_date') ORDER by order_num desc";}
	$rptResult=mysql_query($rptQuery);
	$order_num=mysql_num_rows($rptResult);
	$_SESSION["RPTQUERYRB"] = $rptQuery;
	if ($order_num!=0){?>
		
<div align="center"><form action="reports_ReBillingSendEmail.php" method="post"><input name="stmt_search" type="submit" id="stmt_search" value="<?php echo $btn_emailthis_txt; ?> <?php echo $mlItem[lab_name]?>" class="formField3"></form></div>
				<?php 
				echo "<div class=\"formField2\">".$adm_rbslab_txt." <b>".$mlItem[lab_name]."</b></div>";
				echo "<div class=\"formField2\">".$adm_daterange2_txt." <b> ".$_POST[start_date]." - ".$_POST[end_date]."</b></div>";
				echo'<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3"><tr bgcolor="#C5C5C5">
				<td class="formFieldBold">'.$adm_ordernum2_txt.'</td>
                <td class="formFieldBold">'.$adm_orderdate2_txt.'</td>
                <td class="formFieldBold">'.$adm_shipdate2_txt.'</td>
				  <td class="formFieldBold">'.  'CUSTOMER'. '</td>
                <td class="formFieldBold">'.$adm_patientrefnum2_txt.'</td>
                <td class="formFieldBold">'.$adm_fname2_txt.'</td>
                <td class="formFieldBold">'.$adm_lname2_txt.'</td>
				   <td class="formFieldBold">'.'PRODUCT'.'</td>
                <td class="formFieldBold"align="right">'.$adm_ordertotal2_txt.'</td>
                <td class="formFieldBold" align="right">'.'TOTAL TO PAY'.'</td>
              </tr>';
			
		while ($listItem=mysql_fetch_array($rptResult)){
			$count++;
			$bgcolor=(($count%2)==1) ? "#FFFFFF" : "#DDDDDD";
			
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$formatted_processed_date=mysql_result($new_result,0,0);
			
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$formatted_shipped_date=mysql_result($new_result,0,0);
			
			
			
			
			//Find customer currency
			$queryCurrency  =   "SELECT currency from accounts WHERE user_id = '"  . $listItem[user_id] . "'";
			$rptCurrency	=	mysql_query($queryCurrency);
			$DataCurrency	=	mysql_fetch_array($rptCurrency);
		
			if ($DataCurrency[currency] == 'CA'){
			$ElabCurrencyToUse = 'e_lab_can_price';
			$StockPrice		   = 'price_can';
			}else{
			$ElabCurrencyToUse = 'e_lab_us_price';
			$StockPrice		   = 'price';
			}
			
			
			
			if ($listItem[order_product_type]=='exclusive'){
			$queryElabPrice = "SELECT e_lab_can_price, e_lab_us_price, price, price_can   from exclusive WHERE primary_key =" . $listItem[order_product_id];
			}else{
			$queryElabPrice = "SELECT e_lab_can_price, e_lab_us_price, price, price_can   from prices WHERE prices.product_name ='" . $listItem[order_product_name] . "'";
			}
			
			$rptElabPrice=mysql_query($queryElabPrice);
			$DataElabPrice=mysql_fetch_array($rptElabPrice);
			$elabPrice = $DataElabPrice[$ElabCurrencyToUse];
			if ($listItem[eye] != "Both") {
			$elabPrice = $elabPrice/2;
			}
			
	//echo 'stock price: ' .$StockPrice;


			echo "<tr bgcolor=\"$bgcolor\"><td>".$listItem[order_num]."</td>";
			echo "<td>".$formatted_processed_date."</td>";
			echo "<td>".$formatted_shipped_date."</td>";
			echo "<td>".$listItem[user_id]."</td>";
			echo "<td>".$listItem[patient_ref_num]."</td>";
			echo "<td>".$listItem[order_patient_first]."</td>";
			echo "<td>".$listItem[order_patient_last]."</td>";
			echo "<td>".$listItem[order_product_name]."</td>";
			
					
			
			if ($listItem[order_product_type]=='exclusive'){
			echo "<td align=\"right\">".$listItem[order_total]."</td>";
			}else{//Stock order
			echo "<td align=\"right\">".$DataElabPrice[$StockPrice]."</td>";
			}
			
			
			if ($listItem[order_product_type]=='exclusive'){
			$wholesale_total=money_format('%.2n',($listItem[order_total]+$mlItem[amount]));
			}else{//Stock order
			$wholesale_total=money_format('%.2n',($DataElabPrice[$StockPrice]+$mlItem[amount]));
			}
		
			
			
			
			$running_total+=$wholesale_total;
			$total_elab+=$elabPrice;
			
			$elabPrice = money_format('%.2n',($elabPrice));
			
			  echo "<td align=\"right\">". $elabPrice."</td></tr>";
										  }//END WHILE
										  
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\"><td colspan=\"8\" align=\"right\">".$adm_totalinvoice2_txt."</td><td align=\"right\">".money_format('%.2n',($running_total))."$</td><td align=\"right\">".money_format('%.2n',($total_elab)). "$</td></tr></table>";
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