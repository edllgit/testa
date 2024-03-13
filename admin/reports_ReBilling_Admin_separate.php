<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
include("admin_functions.inc.php");
include("../labAdmin/re_billing_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];
?>
<html>
<head>
<title>Direct Lens Admin Area Re-Billing Separate by Labs</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
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
<form  method="post" name="goto_date" id="form" action="reports_ReBilling_Admin_separate.php" >
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="3"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo $adm_titlemast_lrs; ?> </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="30%"><div align="right">
						<?php echo $adm_datefr_txt; ?></div></td>
					<td width="45%"><input name="start_date" type="text" class="formField" id="start_date" value="<?php echo $_REQUEST[d1]; ?>" size="11">
				<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="end_date" type="text" class="formField" id="end_date" value="<?php echo $_REQUEST['d2']; ?>" size="11">
					</td>
					<td width="25%">&nbsp;</td>
				</tr>
               
                <tr bgcolor="#DDDDDD">
					<td nowrap><div align="right">
						<?php echo $adm_selectlab_txt; ?></div></td>
					<td align="left" nowrap><select name="main_lab" class="formField3" id="main_lab" >
					  <?php
	$query="SELECT * from labs	  where primary_key not in (11,15,19,8,12,23,26) GROUP BY lab_name";

	$result=mysql_query($query)		or die ("Could not find lab list");
		echo "<option value=\"\"";
		echo ">Select a Main Lab</option>";
		if (mysql_num_rows($result)==0){
			echo " <option value=\"\" disabled>none available</option>";
		}else{
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\"";
		if ( $_REQUEST['main_lab'] == $labList[primary_key] ) {
		echo " selected ";
		}
		echo">$labList[lab_name]</option>";
			}//END WHILE
		}//END ELSE
?>
		        </select>
                &nbsp;&nbsp;Report type: 
              
                  <label>
                    <input type="radio" name="report_type"   value="all" id="report_type_0">
                    <a href="reports_ReBilling_Admin.php">All</a></label>
                  
                  <label>
                    <input type="radio" name="report_type" checked="true" value="separate" id="report_type_1">
                    Separate</label>
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
		
		$mlQuery="SELECT labs.lab_email,labs.lab_name,buying_levels.amount from labs LEFT JOIN (buying_levels) ON (buying_levels.buying_level=labs.buying_level) 
						WHERE labs.primary_key='$main_lab' ";
						
		$mlResult=mysql_query($mlQuery);
		$mlItem=mysql_fetch_array($mlResult);
	
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
		
		
		
	$queryLab="SELECT * from labs	where primary_key NOT  IN (11,15,19,8,12,23,26,28,29,31,32,33,34,24,37) GROUP BY lab_name";
	$resultLab=mysql_query($queryLab)		or die ("Could not find lab list");
while ($labListData=mysql_fetch_array($resultLab)){
						
	$total_elab = 0;
	$running_total = 0;
	$wholesale_total = 0;
	
		
		if ($_POST[report_type]=='all') {
		//Redirection vers une autre page qui ne traiterra que le cas 'all'
		$rptQuery="SELECT * from orders WHERE lab='$_POST[main_lab]'and prescript_lab = $labListData[primary_key]   AND order_status IN ('filled','cancelled') AND order_date_shipped between '$start_date' and '$end_date' group by orders.prescript_lab desc";
		}else {
		$rptQuery="SELECT * from orders WHERE lab='$_POST[main_lab]'  and prescript_lab = $labListData[primary_key]  AND order_status IN ('filled','cancelled') AND order_date_shipped between '$start_date' and '$end_date' ORDER by prescript_lab";
		}

	$rptResult=mysql_query($rptQuery);
	$order_num=mysql_num_rows($rptResult);
	$_SESSION["RPTQUERYRB"] = $rptQuery;
	
	if ($order_num!=0){
	?>
		
<div align="center"><form action="reports_ReBillingSendEmail.php" method="post"></div>
				<?php 
				echo "<br><br><div class=\"formField2\">".$adm_rbslab_txt." ".$mlItem[lab_name].": <b>". $labListData[lab_name]." </b>".  $adm_daterange2_txt." <b> ".$_POST[start_date]." - ".$_POST[end_date]."</b></div>";
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
			
			$queryElabPrice = "Select e_lab_can_price as theprice from exclusive where primary_key =" . $listItem[order_product_id];
			$rptElabPrice=mysql_query($queryElabPrice);
			$DataElabPrice=mysql_fetch_array($rptElabPrice);
			$elabPrice = $DataElabPrice['theprice'];
			
			/*//Vérifier s'il y a un over range ON NE FACTURE PAS D'OVER RANGE A EAGLE
			$queryOverRange ="SELECT order_over_range_fee, order_shipping_cost from orders WHERE order_num = " . $listItem[order_num];
			$ResultOverRange=mysql_query($queryOverRange);
			$DataOverRange=mysql_fetch_array($ResultOverRange);
				if ($DataOverRange[order_over_range_fee]> 0){
					$elabPrice =$elabPrice  + $DataOverRange[order_over_range_fee] ;
				}*/
			
			if ($listItem[eye] != "Both") {
			$elabPrice = $elabPrice/2;
			}
			
			$elabPrice  = money_format('%.2n',($elabPrice));
			$queryCompany= "Select company from accounts where user_id = '$listItem[user_id]'";
			$rptCompany=mysql_query($queryCompany);
			$DataCompany=mysql_fetch_array($rptCompany);
			$Company = $DataCompany['company'];
			
			echo "<tr bgcolor=\"$bgcolor\"><td>".$listItem[order_num]."</td>";
			echo "<td>".$formatted_processed_date."</td>";
			echo "<td>".$formatted_shipped_date."</td>";
			echo "<td>".$Company."</td>";
			echo "<td>".$listItem[patient_ref_num]."</td>";
			echo "<td>".$listItem[order_patient_first]."</td>";
			echo "<td>".$listItem[order_patient_last]."</td>";
			echo "<td>".$listItem[order_product_name]."</td>";
			echo "<td align=\"right\">".$listItem[order_total]."</td>";
			$wholesale_total=money_format('%.2n',($listItem[order_total]+$mlItem[amount]));
			$running_total+=$wholesale_total;
			$total_elab+=$elabPrice;
			$total_elab  = money_format('%.2n',($total_elab));
			
			  echo "<td align=\"right\">". $elabPrice."</td></tr>";
			 }//END WHILE
										  
			echo "<tr bgcolor=\"#C5C5C5\" class=\"formFieldBold\"><td colspan=\"8\" align=\"right\">".$adm_totalinvoice2_txt."</td><td align=\"right\">".money_format('%.2n',($running_total))."$</td><td align=\"right\">".money_format('%.2n',($total_elab)). "$</td></tr></table>";
	  }//IN IF ORDER NUM
}//END WHILE
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