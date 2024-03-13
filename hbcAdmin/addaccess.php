<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$heading="Add an Access";
if($_SESSION[formVars])
	$heading="ADMIN NEW LAB FORM - User ID exists, please try a different User ID";

if(     (isset($_POST['name'])) && (isset($_POST['lab']))    ) {



$order_report = $_POST['order_report'];
if ($order_report == ""){
$order_report = 'no';
}else{
$order_report = 'yes';
}

$listofallaccount = $_POST['listofallaccount'];
if ($listofallaccount == ""){
$listofallaccount = 'no';
}else{
$listofallaccount = 'yes';
}

$newsletter_management = $_POST['newsletter_management'];
if ($newsletter_management == ""){
$newsletter_management = 'no';
}else{
$newsletter_management = 'yes';
}

$late_job_report = $_POST['late_job_report'];
if ($late_job_report == ""){
$late_job_report = 'no';
}else{
$late_job_report = 'yes';
}

$coupon_code_usage_report = $_POST['coupon_code_usage_report'];
if ($coupon_code_usage_report == ""){
$coupon_code_usage_report = 'no';
}else{
$coupon_code_usage_report = 'yes';
}

$delay_order_report = $_POST['delay_order_report'];
if ($delay_order_report == ""){
$delay_order_report = 'no';
}else{
$delay_order_report = 'yes';
}

$redirection_report = $_POST['redirection_report'];
if ($redirection_report == ""){
$redirection_report = 'no';
}else{
$redirection_report = 'yes';
}

$all_product_total = $_POST['all_product_total'];
if ($all_product_total == ""){
$all_product_total = 'no';
}else{
$all_product_total = 'yes';
}

$dream_ar_total = $_POST['dream_ar_total'];
if ($dream_ar_total == ""){
$dream_ar_total = 'no';
}else{
$dream_ar_total = 'yes';
}

$exclusive_products_total = $_POST['exclusive_products_total'];
if ($exclusive_products_total == ""){
$exclusive_products_total = 'no';
}else{
$exclusive_products_total = 'yes';
}

$index_total = $_POST['index_total'];
if ($index_total == ""){
$index_total = 'no';
}else{
$index_total = 'yes';
}

$sales_reports = $_POST['sales_reports'];
if ($sales_reports == ""){
$sales_reports = 'no';
}else{
$sales_reports = 'yes';
}

$issue_monthly_credit = $_POST['issue_monthly_credit'];
if ($issue_monthly_credit == ""){
$issue_monthly_credit = 'no';
}else{
$issue_monthly_credit = 'yes';
}

$issue_memo_credit = $_POST['issue_memo_credit'];
if ($issue_memo_credit == ""){
$issue_memo_credit = 'no';
}else{
$issue_memo_credit = 'yes';
}

$memo_codes = $_POST['memo_codes'];
if ($memo_codes == ""){
$memo_codes = 'no';
}else{
$memo_codes = 'yes';
}

$memo_credit_usage_report = $_POST['memo_credit_usage_report'];
if ($memo_credit_usage_report == ""){
$memo_credit_usage_report = 'no';
}else{
$memo_credit_usage_report = 'yes';
}

$print_monthly_statement = $_POST['print_monthly_statement'];
if ($print_monthly_statement == ""){
$print_monthly_statement = 'no';
}else{
$print_monthly_statement = 'yes';
}

$pay_monthly_statement = $_POST['pay_monthly_statement'];
if ($pay_monthly_statement == ""){
$pay_monthly_statement = 'no';
}else{
$pay_monthly_statement = 'yes';
}

$lab_rebilling_statement = $_POST['lab_rebilling_statement'];
if ($lab_rebilling_statement == ""){
$lab_rebilling_statement = 'no';
}else{
$lab_rebilling_statement = 'yes';
}

$product_inventory_report = $_POST['product_inventory_report'];
if ($product_inventory_report == ""){
$product_inventory_report = 'no';
}else{
$product_inventory_report = 'yes';
}

$supplier_order_report = $_POST['supplier_order_report'];
if ($supplier_order_report == ""){
$supplier_order_report = 'no';
}else{
$supplier_order_report = 'yes';
}

$update_product_inventory = $_POST['update_product_inventory'];
if ($update_product_inventory == ""){
$update_product_inventory = 'no';
}else{
$update_product_inventory = 'yes';
}

$update_inventory_notification_settings = $_POST['update_inventory_notification_settings'];
if ($update_inventory_notification_settings == ""){
$update_inventory_notification_settings = 'no';
}else{
$update_inventory_notification_settings = 'yes';
}

$process_product_inventory_orders = $_POST['process_product_inventory_orders'];
if ($process_product_inventory_orders == ""){
$process_product_inventory_orders = 'no';
}else{
$process_product_inventory_orders = 'yes';
}

$extra_product_pricing = $_POST['extra_product_pricing'];
if ($extra_product_pricing == ""){
$extra_product_pricing = 'no';
}else{
$extra_product_pricing = 'yes';
}

$can_view_sales_management_report = $_POST['can_view_sales_management_report'];
if ($can_view_sales_management_report == ""){
$can_view_sales_management_report = 'no';
}else{
$can_view_sales_management_report = 'yes';
}


$can_approve_account = $_POST['can_approve_account'];
if ($can_approve_account == ""){
$can_approve_account = 'no';
}else{
$can_approve_account = 'yes';
}


$can_edit_sales_manager = $_POST['can_edit_sales_manager'];
if ($can_edit_sales_manager == ""){
$can_edit_sales_manager = 'no';
}else{
$can_edit_sales_manager = 'yes';
}


$can_manage_inventory = $_POST['can_manage_inventory'];
if ($can_manage_inventory == ""){
$can_manage_inventory = 'no';
}else{
$can_manage_inventory = 'yes';
}


$can_manage_credit = $_POST['can_manage_credit'];
if ($can_manage_credit == ""){
$can_manage_credit = 'no';
}else{
$can_manage_credit = 'yes';
}

$can_edit_account = $_POST['can_edit_account'];
if ($can_edit_account == ""){
$can_edit_account = 'no';
}else{
$can_edit_account = 'yes';
}


//echo 'can edit account: ' . $can_edit_account  . '<br>';
//echo 'can approve account: ' . $can_approve_account  . '<br>';
//echo 'can  edit sales manager: ' . $can_edit_sales_manager  . '<br>';
//echo 'can  viewsales management report: ' . $can_view_sales_management_report  . '<br>';
//echo 'can search job: ' . $can_search_job  . '<br>';
//echo 'can manage credits: ' . $can_manage_credit  . '<br>';
//echo 'can manage inventory: ' . $can_manage_inventory  . '<br>';

$queryInsert = "Insert into ACCESS (name,lab_primary_key,username,password,order_report,late_job_report,coupon_code_usage_report,delay_order_report,
redirection_report,all_product_total,dream_ar_total,exclusive_products_total,index_total,sales_reports,issue_monthly_credit,issue_memo_credit,
memo_codes,memo_credit_usage_report,print_monthly_statement,pay_monthly_statement,lab_rebilling_statement,product_inventory_report,supplier_order_report,
update_product_inventory,update_inventory_notification_settings,process_product_inventory_orders,extra_product_pricing,can_view_sales_management_report,
can_approve_account,can_edit_sales_manager,can_manage_inventory,can_manage_credit,can_edit_account,listofallaccount,newsletter_management) VALUES ('$_POST[name]',$_POST[lab],'$_POST[username]','$_POST[password]',
'$order_report','$late_job_report','$coupon_code_usage_report','$delay_order_report','$redirection_report','$all_product_total',
'$dream_ar_total','$exclusive_products_total','$index_total','$sales_reports','$issue_monthly_credit','$issue_memo_credit',
'$memo_codes','$memo_credit_usage_report','$print_monthly_statement','$pay_monthly_statement','$lab_rebilling_statement',
'$product_inventory_report','$supplier_order_report','$update_product_inventory','$update_inventory_notification_settings',
'$process_product_inventory_orders','$extra_product_pricing','$can_view_sales_management_report','$can_approve_account',
'$can_edit_sales_manager','$can_manage_inventory','$can_manage_credit','$can_edit_account','$listofallaccount','$newsletter_management')";
$QueryResult=mysql_query($queryInsert)	or die ("Error: Could not create access" . mysql_error() );
echo 'Access created.';
header("Location:listaccess.php");
exit();
}

	
$query="select * from labs where primary_key = '$pkey'";
$labResult=mysql_query($query)
	or die ("Could not find lab");
$labData=mysql_fetch_array($labResult);
mysql_free_result($labResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 7px}
-->
</style>
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
  		<td width="75%"><form name="form3" method="post" action="addaccess.php" class="formField">
        <input type="hidden" name="action" value="Add access">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $heading; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
					Name		
					</div></td>
					<td align="left">
						<input name="name" type="text" id="name" size="20" class="formField" value="">					</td>
				<td align="left" bgcolor="#DDDDDD" ><div align="right">
						Username
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><input name="username" type="text" id="username" size="20" class="formField">					</td>
					<td align="left" nowrap bgcolor="#DDDDDD"><div align="right">
						Password
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><input name="password" type="text" id="password" size="20" class="formField">					</td>
				</tr>

			
				<tr>
					<td align="left"><div align="right">
						Main Lab:
					</div></td>
					<td align="left"><select name="lab" class="formField" id="lab">
						         <?php
	$query="select primary_key, lab_name from labs where primary_key not in (11,15,19,8,12,23,26,24) order by lab_name ";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
mysql_free_result($result);
?>
					</select></td>

				</tr>
				<tr><td>&nbsp;</td></tr>

				<tr bgcolor="#FFFFFF">
               
				<td bgcolor="#DDDDDD" ><div align="right"><b>REPORTS</b></div></td>

				</tr>
                
                <tr bgcolor="#FFFFFF">
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Order Reports:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="order_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["order_report"]=="yes") echo " checked"; ?>></td>					
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Late job Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="late_job_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["late_job_report"]=="yes") echo " checked"; ?>></td>				
                    
                    
                          <td bgcolor="#DDDDDD" align="left"><div align="right">
						Coupon code usage report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="coupon_code_usage_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["coupon_code_usage_report"]=="yes") echo " checked"; ?>></td>				
                			
                </tr>
                
                
                
                
                <tr bgcolor="#FFFFFF">
			               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Delay Order Reports:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="delay_order_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["delay_order_report"]=="yes") echo " checked"; ?>></td>					
                
                
                	   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Redirection report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="redirection_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["redirection_report"]=="yes") echo " checked"; ?>></td>	
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						All Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="all_product_total" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["all_product_total"]=="yes") echo " checked"; ?>></td>					


				</tr>
                
                
                
                
                
                
                
                 <tr bgcolor="#FFFFFF">
			               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Dream AR Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="dream_ar_total" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["dream_ar_total"]=="yes") echo " checked"; ?>></td>					
                
                
                	   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Exclusive Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="exclusive_products_total" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["exclusive_products_total"]=="yes") echo " checked"; ?>></td>	
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Index Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="index_total" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["index_total"]=="yes") echo " checked"; ?>></td>					
                
				</tr>
                
                
                
                  <tr bgcolor="#FFFFFF">
			               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Sales Reports Daily/Wkly:<br>
					</div></td>
				<td bgcolor="#DDDDDD" align="left"><input name="sales_reports" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["sales_reports"]=="yes") echo " checked"; ?>></td>					
   
				</tr>
                
                
                
                
                
                
               	<tr><td>&nbsp;</td></tr>


			<td bgcolor="#DDDDDD" ><div align="right"><b>CREDITS MANAGEMENT</b></div></td>
                
                
                
                
                   
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Issue Monthly Credits:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="issue_monthly_credit" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["issue_monthly_credit"]=="yes") echo " checked"; ?>></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Issur Memo Credits:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="issue_memo_credit" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["issue_memo_credit"]=="yes") echo " checked"; ?>></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Memo Codes:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="memo_codes" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["memo_codes"]=="yes") echo " checked"; ?>></td>	
</tr>
                     
                
                
                
                      
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Memo Credits usage report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="memo_credit_usage_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["memo_credit_usage_report"]=="yes") echo " checked"; ?>></td>		
                    
                        
                 
                      
</tr>
                
                
                
                
                
                   	<tr><td>&nbsp;</td></tr>


			<td bgcolor="#DDDDDD" ><div align="right"><b>ACCOUNTING MANAGEMENT</b></div></td>
                  
                   
                   
                   <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
					Print Monthly Statement:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="print_monthly_statement" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["print_monthly_statement"]=="yes") echo " checked"; ?>></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Pay Monthly Statement:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="pay_monthly_statement" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["pay_monthly_statement"]=="yes") echo " checked"; ?>></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Lab Re-billing Statements:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="lab_rebilling_statement" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["lab_rebilling_statement"]=="yes") echo " checked"; ?>></td>	
  
                </tr>

				  
                   
                   
                   
                   
                   
                   
                   	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>INVENTORY</b></div></td>
                        
                
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Product Inventory Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="product_inventory_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["product_inventory_report"]=="yes") echo " checked"; ?>></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Supplier Order Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="supplier_order_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["supplier_order_report"]=="yes") echo " checked"; ?>></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Update Product Inventory:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="update_product_inventory" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["update_product_inventory"]=="yes") echo " checked"; ?>></td>	
  
                </tr>

                        
                            
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Update Inventory Notification Settings:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="update_inventory_notification_settings" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["update_inventory_notification_settings"]=="yes") echo " checked"; ?>></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Process Product Inventory Orders:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="process_product_inventory_orders" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["process_product_inventory_orders"]=="yes") echo " checked"; ?>></td>		

                </tr>
                        
                          	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>PRICING</b></div></td>
                        
                           
                           <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Extra Product Pricing:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="extra_product_pricing" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["extra_product_pricing"]=="yes") echo " checked"; ?>></td>		

                </tr>  
                           
                           
                           
                        	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>OTHER</b></div></td>       
                        
                
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						View Sales Management Reports:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_view_sales_management_report" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_view_sales_management_report"]=="yes") echo " checked"; ?>></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Approve Account:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_approve_account" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_approve_account"]=="yes") echo " checked"; ?>></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Sales Managers:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_edit_sales_manager" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_edit_sales_manager"]=="yes") echo " checked"; ?>></td>	
  
                </tr>

				
                
                    
                
                <tr>  
                 <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Inventory:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_manage_inventory" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_manage_inventory"]=="yes") echo " checked"; ?>></td>		
                    
                                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Credit:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_manage_credit" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_manage_credit"]=="yes") echo " checked"; ?>></td>				
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Account:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_edit_account" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["can_edit_account"]=="yes") echo " checked"; ?>></td>				
                    
                    </tr>
				
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="submit" name="addAccess" id="addAccess" value="Add Access" class="formField">
&nbsp;</td>
            		</tr>
			</table>
	  </form></td>
	  </tr>
</table>
  <p>&nbsp;</p>
<?php unset($_SESSION["formVars"]); ?>  
</body>
</html>
