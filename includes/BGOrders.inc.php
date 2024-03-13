<?php
$bg_pkey=$_SESSION["BG_pkey"];
$bg_name=$_SESSION["sessionBGData"]["bg_name"];
$BGdisc=$_SESSION["sessionBGData"]["global_dsc"];
$BGdisc=bcdiv($BGdisc, 100, 2);


if($_POST[order_search]=="Get Order Info"){
	$heading="All Orders";
	$rptQuery="SELECT * from orders, accounts, buying_groups WHERE orders.user_id=accounts.user_id AND accounts.buying_group=buying_groups.primary_key AND accounts.buying_group='$bg_pkey' AND orders.order_status!='basket'";
	
	if($_POST[acctName]!=""){
		$rptQuery.=" AND orders.user_id='" . $_POST[acctName] . "'";
		$query="select user_id, company from accounts where user_id='$_POST[acctName]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading="Orders from account $acctData[company]";
	}	
	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_item_date between '$date_from' and '$date_to'";
		$heading.=" for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
	}

	$rptQuery.=" group by order_num desc order by accounts.company";
}
?>
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
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
	<tr>
		<td colspan="3" bgcolor="#000099" class="tableHead"><div align="center">
			<?php print "$bg_name"; ?> Orders Search
		</div></td>
	</tr>
<form method="post" name="goto_date" id="goto_date" action="BGAccount.php">
	<tr bgcolor="#E7F2FF">
		<td class="formCellNosides">
			Select Account 		</td>
		<td class="formCellNosides"><select name="acctName" id="acctName">
			<option value="">Select Account</option>
			<?php
	$query="select company, user_id from accounts where buying_group='$bg_pkey' order by company";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		print "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
}
?>
		</select></td>
		<td colspan="5" class="formCellNosides">Date From
						<input name="date_from" type="text" id="date_from" value="All" size="11" />
							<a href="#" onclick="cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;" title="Popup calendar for quick date selection" name="anchor1xx" id="anchor1xx"><img src="includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle" /></a> &nbsp;&nbsp;&nbsp;Through
						<input name="date_to" type="text" id="date_to" value="All" size="11" />
						<a href="#" onclick="cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;" title="Popup calendar for quick date selection" name="anchor2xx" id="anchor2xx"><img src="includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle" /></a></td>
	</tr>
	<tr bgcolor="#E7F2FF">
		<td colspan="3" class="formStockBulk"><input name="order_search" type="submit" id="order_search" value="Get Order Info" class="formText" /></td>
	</tr></form>
</table>
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            	<tr bgcolor="#000099">
            		<td colspan="4" class="tableHead"><div align="center"><?php print "$heading"; ?></div></td>
           		</tr>

<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)
		or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}
			
if ($usercount != 0){
	$outputstring="Orders from account $acctData[company]";

	if ($_POST[date_from] != "All" && $_POST[date_to] != "All")
		$outputstring .= " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];

	$outputstring.="Company".chr(9)."Discount".chr(9)."AR Orders".chr(9)."Total Orders".chr(13);
  print "<tr>
  <td align=\"center\"  class=\"formCell\">Company</td>
  <td align=\"center\"  class=\"formCell\">Discount</td>
  <td align=\"center\"  class=\"formCell\">AR Orders</td>
  <td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
	$acctTotal=0;			  
	$ARTotal=0;			  
	while ($listItem=mysql_fetch_array($rptResult)){
		if(!isset($currentAcct))
			$currentAcct=$listItem[company];
		$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_item_date]','%m-%d-%Y')");
		$formated_date=mysql_result($new_result,0,0);
			//$orderQuery="SELECT order_quantity, order_product_discount, order_over_range_fee, order_product_name from orders WHERE order_num='$listItem[order_num]'";
			//$orderResult=mysql_query($orderQuery)
			//	or die  ('I cannot select items because: ' . mysql_error().$orderQuery);
			//$orderTotal=0;			  
			//$ARorderTotal=0;			  
			//while ($orderTally=mysql_fetch_array($orderResult)){ /* add up the total order with each line order */
			//	$orderSubTally=bcmul($orderTally[order_quantity], $orderTally[order_product_discount], 2); /* multiply qty by item price */
			//	$orderSubTally=bcadd($orderSubTally, $orderTally[order_over_range_fee], 2); /* add in over range fee */
			//	$orderTotal=bcadd($orderTotal, $orderSubTally, 2); /* add the line total to the order total */
		$prodName=explode(" ", $listItem[order_product_name]); /* check if an AR product */
		$prodCount=count($prodName);
		$i=$prodCount;
		$i--;
		if($prodName[$i] == "AR"){ /* if AR product, add to AR total */
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
		}
			//}
		$acctTotal=bcadd($acctTotal,$listItem[order_total],2);
		if($currentAcct!=$listItem[company]){
			$BGtotal=bcmul($acctTotal, $BGdisc, 2);
			$acctTotal=money_format('%.2n',$acctTotal);
			$ARTotal=money_format('%.2n',$ARTotal);
			$outputstring.="$currentAcct".chr(9)."$BGtotal".chr(9)."$ARTotal".chr(9)."$acctTotal".chr(13);
			print "<tr><td align=\"center\"  class=\"formCell\">$currentAcct</td><td align=\"center\"  class=\"formCell\">\$$BGtotal</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$acctTotal</td></tr>";
			$acctTotal=0;
			$ARTotal=0;
			$acctTotal=bcadd($acctTotal, $listItem[order_total], 2);
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
			$currentAcct=$listItem[company];
		}else{
			$acctTotal=bcadd($acctTotal, $listItem[order_total], 2);
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
		}
	}//END WHILE
	$BGtotal=bcmul($acctTotal, $BGdisc, 2);
	$acctTotal=money_format('%.2n',$acctTotal);
	$ARTotal=money_format('%.2n',$ARTotal);
	$outputstring.="$currentAcct".chr(9)."$BGtotal".chr(9)."$ARTotal".chr(9)."$acctTotal".chr(13);
	print "<tr><td align=\"center\"  class=\"formCell\">$currentAcct</td><td align=\"center\"  class=\"formCell\">\$$BGtotal</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$acctTotal</td></tr>";
	print "</table>";
}else{
	print "<tr><td colspan=\"3\" class=\"formCell\">No Orders Found</td></tr></table>";
}//END USERCOUNT CONDITIONAL
?>
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
