<?php
session_start();

include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../Connections/directlens.php";
include "../includes/getlang.php";


 if ($mylang == 'lang_french'){
		$heading="Sommaire des access au Labadmin";
 }else {
		$heading="Summary of Labadmin Access";	
 }
	
	
$heading.=$dateInfo;
//$heading=ucwords($heading);

$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<form  method="post" name="goto_date" id="goto_date" action="report_labadmin_access.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
					<?php if ($mylang == 'lang_french'){
					echo "Sommaire des access au Labadmin";
					}else {
					echo "Summary of Labadmin Access";	
					}
					?> 
					
					</font></b></td>
            		</tr>
				

				
</form>
<?php 

if ($_POST[date_from] != "All" && $_POST[date_to] != "All" && $_POST[date_to] != "" ){//select between these dates

}
print "<table width=\"40%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
print '<tr><td>&nbsp;</td></tr><tr><td align="center">'. $heading . '</td><td>&nbsp;</td></tr>';
print '<tr><td>&nbsp;</td></tr>';

$queryLabs = "SELECT * from access ORDER BY name";
$rptLabs=mysql_query($queryLabs) 		or die  ('I cannot select items because: ' . mysql_error());
while ($listLab=mysql_fetch_array($rptLabs))
{
	
echo '<tr><td><b>'. $listLab['name'] . '</b></td></tr>' ;

$queryNomLab = "SELECT lab_name from labs where primary_key = " .  $listLab['lab_primary_key'];
$rptNomLab=mysql_query($queryNomLab) 		or die  ('I cannot select items because: ' . mysql_error());
$DataLab=mysql_fetch_array($rptNomLab);
$labName = $DataLab['lab_name'];

echo '<tr><td>Lab: </td> <td><b>'. $labName . '</b></td></tr>' ;
echo '<tr><td>Username: </td> <td><b>'. $listLab['username'] . '</b></td></tr>' ;
echo '<tr><td>Password: </td> <td><b>'. $listLab['password'] . '</b></td></tr>' ;

echo '<tr><td>Order Reports:</td> <td><b>'. $listLab['order_report'] . '</b></td></tr>' ;
echo '<tr><td>Late job Report: </td> <td><b>'. $listLab['late_job_report'] . '</b></td></tr>' ;
echo '<tr><td>Coupon code usage report: </td> <td><b>'. $listLab['coupon_code_usage_report'] . '</b></td></tr>' ;
echo '<tr><td>Delay Order Reports:</td> <td> <b>'. $listLab['delay_order_report'] . '</b></td></tr>' ;
echo '<tr><td>Redirection report:</td> <td> <b>'. $listLab['redirection_report'] . '</b></td></tr>' ;
echo '<tr><td>All Products Totals:</td> <td> <b>'. $listLab['all_product_total'] . '</b></td></tr>' ;
echo '<tr><td>Dream AR Totals: </td> <td><b>'. $listLab['dream_ar_total'] . '</b></td></tr>' ;
echo '<tr><td>Exclusive Products Totals: </td> <td><b>'. $listLab['exclusive_products_total'] . '</b></td></tr>' ;
echo '<tr><td>Index Totals: </td> <td><b>'. $listLab['index_total'] . '</b></td></tr>' ;
echo '<tr><td>Sales Reports Daily/Wkly:</td> <td><b>'. $listLab['sales_reports'] . '</b></td></tr>' ;
echo '<tr><td>Issue Monthly Credits: </td> <td><b>'. $listLab['issue_monthly_credit'] . '</b></td></tr>' ;
echo '<tr><td>Issue Memo Credits: </td> <td><b>'. $listLab['issue_memo_credit'] . '</b></td></tr>' ;

echo '<tr><td>Memo Codes: </td> <td><b>'. $listLab['memo_codes'] . '</b></td></tr>' ;
echo '<tr><td>Memo Credits usage report: </td> <td><b>'. $listLab['memo_credit_usage_report'] . '</b></td></tr>' ;
echo '<tr><td>Print Monthly Statement: </td> <td><b>'. $listLab['print_monthly_statement'] . '</b></td></tr>' ;
echo '<tr><td>Pay Monthly Statement: </td> <td><b>'. $listLab['pay_monthly_statement'] . '</b></td></tr>' ;
echo '<tr><td>Lab Re-billing Statements: </td> <td><b>'. $listLab['lab_rebilling_statement'] . '</b></td></tr>' ;
echo '<tr><td>Product Inventory Report: </td> <td><b>'. $listLab['product_inventory_report'] . '</b></td></tr>' ;
echo '<tr><td>Supplier Order Report: </td> <td><b>'. $listLab['supplier_order_report'] . '</b></td></tr>' ;
echo '<tr><td>Update Product Inventory: </td> <td><b>'. $listLab['update_product_inventory'] . '</b></td></tr>' ;
echo '<tr><td>Update Inventory Notification Settings: </td> <td><b>'. $listLab['update_inventory_notification_settings'] . '</b></td></tr>' ;
echo '<tr><td>Process Product Inventory Orders: </td> <td><b>'. $listLab['process_product_inventory_orders'] . '</b></td></tr>' ;
echo '<tr><td>Extra Product Pricing: </td> <td><b>'. $listLab['extra_product_pricing'] . '</b></td></tr>' ;
echo '<tr><td>View Sales Management Reports: </td> <td><b>'. $listLab['can_view_sales_management_report'] . '</b></td></tr>' ;
echo '<tr><td>Approve Account: </td> <td><b>'. $listLab['can_approve_account'] . '</b></td></tr>' ;
echo '<tr><td>Edit Sales Managers: </td> <td><b>'. $listLab['can_edit_sales_manager'] . '</b></td></tr>' ;
echo '<tr><td>Manage Inventory: </td> <td><b>'. $listLab['can_manage_inventory'] . '</b></td></tr>' ;
echo '<tr><td>Manage Credit: </td> <td><b>'. $listLab['can_manage_credit'] . '</b></td></tr>' ;
echo '<tr><td>Edit Account: </td> <td><b>'. $listLab['can_edit_account'] . '</b></td></tr>' ;





echo '<tr><td>List of all accounts: </td><td><b>'. $listLab['listofallaccount'] . '</b></td></tr>' ;
echo '<tr><td>Newsletter management:</td><td><b>'. $listLab['newsletter_management'] . '</b></td></tr>' ;
echo '<tr><td>Report last login: </td><td><b>'. $listLab['report_last_login'] . '</b></td></tr>' ;
echo '<tr><td>Update Order status: </td><td><b>'. $listLab['update_status'] . '</b></td></tr>' ;
echo '<tr><td>Management People: </td><td><b>'. $listLab['management_people'] . '</b></td></tr>' ;
echo '<tr><td>Edit Customer Buying Group: </td><td><b>'. $listLab['edit_customer_buying_group'] . '</b></td></tr>' ;



echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';


}//End while listAccount


 	 	 

print "</table>";
?>
</td>
	  </tr>
</table><br><br></table>
<p>&nbsp;</p>
</body>
</html>
