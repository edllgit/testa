<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];


$rptQuery="SELECT * from  accounts where approved='approved' AND   main_lab =".  $lab_pkey . " order by sales_rep desc,last_connexion desc";
	//echo $rptQuery;
	
if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
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

<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ($lbl_error1_txt . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}			
			
if ($usercount != 0){//some orders were found
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"><tr bgcolor=\"#000000\">";
	echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td>";
	echo "</tr>";
	if($_SESSION["order_status"]=="processing")
		echo "<form action=\"report.php\" method=\"post\" name=\"statusForm\">";
		//print the top heading row
		echo "<tr>
		<th align=\"center\">Company</th>
		<th align=\"center\">Sales rep</th>
		<th align=\"center\">Last Login</th>
		<th align=\"center\">Last Order</th>
		<th align=\"center\">Total Orders</th>
		<th align=\"center\">Last 30 days Orders</th>
		</tr>";
		while ($listItem=mysql_fetch_array($rptResult)){


		if ($listItem[sales_rep] <> 0)
		{
		$querySalesrep = "SELECT rep_name from sales_reps  where id  = $listItem[sales_rep]";
		$rptSalesrep=mysql_query($querySalesrep)		or die  ($lbl_error1_txt . mysql_error());
		$DataSalesrep=mysql_fetch_array($rptSalesrep);
		$salesrep = $DataSalesrep['rep_name'];
		}else{
		$salesrep = 'none';
		}
	//	echo '<br><br>'.$querySalesrep . '<br>';
			echo  "<tr>";
			
               echo "<td align=\"center\">$listItem[company]</td>";
			    echo "<td align=\"center\">$salesrep</td>";
			   
			   
			   if ($listItem[last_connexion] != '0000-00-00 00:00:00'){
				$listItem[last_connexion] = substr($listItem[last_connexion],0,10);
			   echo"<td align=\"center\">$listItem[last_connexion]</td>";
			   }else{
			   echo"<td align=\"center\">Unknown</td>";
			   }
			   
			   
			   
			   //Get last order of customer
			  $QueryLastOrder = "Select max(order_date_processed)  as max_date, count(distinct order_num) as nbr_commande from orders where order_num <> -1 AND user_id = '" . $listItem[user_id]. "' ";
			  $LastOrderResult=mysql_query($QueryLastOrder)		or die  ($lbl_error1_txt . mysql_error());
			  $DataLastOrder=mysql_fetch_array($LastOrderResult);
			  $LastOrderDate = $DataLastOrder['max_date'];
			  $totalOrder =  $DataLastOrder['nbr_commande'];
			//  echo  $QueryLastOrder;
			  
			  
			  $tomorrow = mktime(0,0,0,date("m"),date("d")-30,date("Y"));
			  $ilyaunmois = date("Y/m/d", $tomorrow);
			  
			  $queryLastmonth = "Select count(distinct order_num) as nbrOrderLastMonth from orders where order_num <> -1 AND user_id = '$listItem[user_id]'   and order_date_processed >  '$ilyaunmois' ";
			  $LastMonthResult=mysql_query($queryLastmonth)		or die  ($lbl_error1_txt . mysql_error());
			  $DataLastMonth=mysql_fetch_array($LastMonthResult);
			  $NbrOrderLastMonth =  $DataLastMonth['nbrOrderLastMonth'];
			  
			  
			  if ($LastOrderDate != ""){
                echo "<td align=\"center\">$LastOrderDate</td>";
				}else{
				echo "<td align=\"center\">Never ordered</td>";
				}
				
				 if ($totalOrder != 0){
                echo "<td align=\"center\">$totalOrder</td>";
				}else{
				echo "<td align=\"center\">-</td>";
				}
				
				 if ($NbrOrderLastMonth != 0){
                echo "<td align=\"center\">$NbrOrderLastMonth</td>";
				}else{
				echo "<td align=\"center\">-</td>";
				}
				
			
				
              echo "</tr>";
		}//END WHILE
			
		echo "</table>";

}
?></td>
	  </tr>
</table>
  <p>&nbsp;</p>
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
