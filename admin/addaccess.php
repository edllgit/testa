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


  
$print_order_with_price   = $_POST['print_order_with_price'];
if ($print_order_with_price  == ""){
$print_order_with_price  = 'no';
}else{
$print_order_with_price  = 'yes';
}
 
$print_order   = $_POST['print_order'];
if ($print_order  == ""){
$print_order  = 'no';
}else{
$print_order  = 'yes';
}
 
 $lnc_reward_management   = $_POST['lnc_reward_management'];
if ($lnc_reward_management  == ""){
$lnc_reward_management  = 'no';
}else{
$lnc_reward_management  = 'yes';
}

$enter_payment_notification   = $_POST['enter_payment_notification'];
if ($enter_payment_notification  == ""){
$enter_payment_notification  = 'no';
}else{
$enter_payment_notification  = 'yes';
}
 
$limited_order_report_and_status_update   = $_POST['limited_order_report_and_status_update'];
if ($limited_order_report_and_status_update  == ""){
$limited_order_report_and_status_update  = 'no';
}else{
$limited_order_report_and_status_update  = 'yes';
}




$search_who_order_belongs_to = $_POST['search_who_order_belongs_to'];
if ($search_who_order_belongs_to == ""){
$search_who_order_belongs_to = 'no';
}else{
$search_who_order_belongs_to = 'yes';
}



$see_customer_passwords = $_POST['see_customer_passwords'];
if ($see_customer_passwords == ""){
$see_customer_passwords = 'no';
}else{
$see_customer_passwords = 'yes';
}


$management_people = $_POST['management_people'];
if ($management_people == ""){
$management_people = 'no';
}else{
$management_people = 'yes';
}

$edit_customer_buying_group = $_POST['edit_customer_buying_group'];
if ($edit_customer_buying_group == ""){
$edit_customer_buying_group = 'no';
}else{
$edit_customer_buying_group = 'yes';
}



$report_last_login = $_POST['report_last_login'];
if ($report_last_login == ""){
$report_last_login = 'no';
}else{
$report_last_login = 'yes';
}

$order_report = $_POST['order_report'];
if ($order_report == ""){
$order_report = 'no';
}else{
$order_report = 'yes';
}

$update_status = $_POST['update_status'];
if ($update_status == ""){
$update_status = 'no';
}else{
$update_status = 'yes';
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


$queryInsert = "Insert into ACCESS (print_order_with_price,print_order,lnc_reward_management,enter_payment_notification,limited_order_report_and_status_update ,name,lab_primary_key,username,password,order_report,late_job_report,coupon_code_usage_report,delay_order_report,
redirection_report,all_product_total,dream_ar_total,exclusive_products_total,index_total,sales_reports,issue_monthly_credit,issue_memo_credit,
memo_codes,memo_credit_usage_report,print_monthly_statement,pay_monthly_statement,lab_rebilling_statement,product_inventory_report,supplier_order_report,
update_product_inventory,update_inventory_notification_settings,process_product_inventory_orders,extra_product_pricing,can_view_sales_management_report,
can_approve_account,can_edit_sales_manager,can_manage_inventory,can_manage_credit,can_edit_account,listofallaccount,newsletter_management,report_last_login,update_status, management_people, edit_customer_buying_group, see_customer_passwords,search_who_order_belongs_to) VALUES ('$print_order_with_price','$print_order','$lnc_reward_management','$enter_payment_notification','$limited_order_report_and_status_update','$_POST[name]',$_POST[lab],'$_POST[username]','$_POST[password]',
'$order_report','$late_job_report','$coupon_code_usage_report','$delay_order_report','$redirection_report','$all_product_total',
'$dream_ar_total','$exclusive_products_total','$index_total','$sales_reports','$issue_monthly_credit','$issue_memo_credit',
'$memo_codes','$memo_credit_usage_report','$print_monthly_statement','$pay_monthly_statement','$lab_rebilling_statement',
'$product_inventory_report','$supplier_order_report','$update_product_inventory','$update_inventory_notification_settings',
'$process_product_inventory_orders','$extra_product_pricing','$can_view_sales_management_report','$can_approve_account',
'$can_edit_sales_manager','$can_manage_inventory','$can_manage_credit','$can_edit_account','$listofallaccount','$newsletter_management','$report_last_login', '$update_status','$management_people', '$edit_customer_buying_group', '$see_customer_passwords','$search_who_order_belongs_to')";
$QueryResult=mysql_query($queryInsert)	or die ("Error: Could not create access" . mysql_error() );
echo $queryInsert;
echo 'Access created.';
header("Location:listaccess.php");
exit();
}

	
$query="select * from labs where primary_key = '$pkey'";
$labResult=mysql_query($query)
	or die ("Could not find lab");
$labData=mysql_fetch_array($labResult);
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
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print $heading; ?></font></b></td>
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
		print "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
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
					<td bgcolor="#DDDDDD" align="left"><input name="order_report" type="checkbox" value="yes"></td>					
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Late job Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="late_job_report" type="checkbox" value="yes"></td>				
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">
						Report last login:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="report_last_login" type="checkbox" value="yes"></td>		
                    
                    
                       			
                			
                </tr>
                
                
                
                
                <tr bgcolor="#FFFFFF">
			               
                              <td bgcolor="#DDDDDD" align="left"><div align="right">
						Coupon code usage report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="coupon_code_usage_report" type="checkbox" value="yes"></td>	
                           
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Delay Order Reports:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="delay_order_report" type="checkbox" value="yes"></td>					
                
                
                	   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Redirection report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="redirection_report" type="checkbox" value="yes"></td>	
                    
                    


				</tr>
                
                
                
                
                
                
                
                 <tr bgcolor="#FFFFFF">
                 
                 
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						All Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="all_product_total" type="checkbox" value="yes"></td>					
			               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Dream AR Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="dream_ar_total" type="checkbox" value="yes"></td>					
                
                
                	   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Exclusive Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="exclusive_products_total" type="checkbox" value="yes" ></td>	
                    
                    
                      
				</tr>
                
                
                
                  <tr bgcolor="#FFFFFF">
			               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Sales Reports Daily/Wkly:<br>
					</div></td>
				<td bgcolor="#DDDDDD" align="left"><input name="sales_reports" type="checkbox" value="yes"></td>					
   
    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Index Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="index_total" type="checkbox" value="yes" ></td>					
                
				</tr>
                
                
                
                    	<tr><td>&nbsp;</td></tr>


			<td bgcolor="#DDDDDD" ><div align="right"><b>RESTRICTED ACCESS</b></div></td>
                
              
                   
                <tr>
                                   <td colspan="3" bgcolor="#DDDDDD" align="left"><div align="right">
						Restricted access to Order report and Update status(no prices are displayed):<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="limited_order_report_and_status_update" type="checkbox" value="yes"></td>		
                    
                        
                 
</tr>
                     
                  
                
                
                
               	<tr><td>&nbsp;</td></tr>


			<td bgcolor="#DDDDDD" ><div align="right"><b>CREDITS MANAGEMENT</b></div></td>
                
                
                
                
                   
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Issue Monthly Credits:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="issue_monthly_credit" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Issur Memo Credits:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="issue_memo_credit" type="checkbox" value="yes" ></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Memo Codes:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="memo_codes" type="checkbox" value="yes"></td>	
</tr>
                     
                
                
                
                      
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Memo Credits usage report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="memo_credit_usage_report" type="checkbox" value="yes"></td>		
                    
                        
                 
                      
</tr>
                
                
                
                
                
                   	<tr><td>&nbsp;</td></tr>


			<td bgcolor="#DDDDDD" ><div align="right"><b>ACCOUNTING MANAGEMENT</b></div></td>
                  
                   
                   
                   <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
					Print Monthly Statement:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="print_monthly_statement" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Pay Monthly Statement:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="pay_monthly_statement" type="checkbox" value="yes"</td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Lab Re-billing Statements:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="lab_rebilling_statement" type="checkbox" value="yes"></td>	
  
                </tr>

				  
                   
                   
                   
                   
                   
                   
                   	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>INVENTORY</b></div></td>
                        
                
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Product Inventory Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="product_inventory_report" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Supplier Order Report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="supplier_order_report" type="checkbox" value="yes"></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Update Product Inventory:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="update_product_inventory" type="checkbox" value="yes"></td>	
  
                </tr>

                        
                            
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Update Inventory Notification Settings:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="update_inventory_notification_settings" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Process Product Inventory Orders:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="process_product_inventory_orders" type="checkbox" value="yes"></td>		

                </tr>
                        
                          	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>PRICING</b></div></td>
                        
                           
                           <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Extra Product Pricing:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="extra_product_pricing" type="checkbox" value="yes"></td>		

                </tr>  
                           
                      
                           
                           
                           
                        	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>OTHER</b></div></td>       
                       
                       
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						List of all accounts:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="listofallaccount" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Newsletter management:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="newsletter_management" type="checkbox" value="yes"></td>		
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
						Update Order status:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="update_status" type="checkbox" value="yes" ></td>		

                </tr>
                
                
                         <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Quickly Print Order <b>(no prices)</b>:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="print_order" type="checkbox" value="yes"></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Quickly Print Order <b>(with prices):</b><br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="print_order_with_price" type="checkbox" value="yes"></td>		
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
						&nbsp;:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left">&nbsp;</td>		

                </tr>
                
                
                
                         
                        	<tr><td>&nbsp;</td></tr>
                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>PAYMENT NOTIFICATION</b></div></td>       
                       
                       
                <tr>
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
						Enter payment notification:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="enter_payment_notification" type="checkbox" value="yes" ></td>		

                </tr>
                       
                       
                    
                        	<tr><td>&nbsp;</td></tr>    
                       
                        
                
                <tr>
                                   <td bgcolor="#DDDDDD" align="left"><div align="right">
						View Sales Management Reports:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_view_sales_management_report" type="checkbox" value="yes" ></td>		
                    
                        
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Approve Account:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_approve_account" type="checkbox" value="yes" ></td>		
                    
                    
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Sales Managers:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_edit_sales_manager" type="checkbox" value="yes" ></td>	
  
                </tr>

				
                
                    
                
                <tr>  
                 <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Inventory:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_manage_inventory" type="checkbox" value="yes" ></td>		
                    
                                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Credit:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_manage_credit" type="checkbox" value="yes" ></td>				
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Account:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="can_edit_account" type="checkbox" value="yes"></td>				
               </tr>
               
               
               
                    <tr>  
                 <td bgcolor="#DDDDDD" align="left"><div align="right">
						Management People:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="management_people" type="checkbox" value="yes" ></td>		
                    
                                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Customer Buying Group:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_customer_buying_group" type="checkbox" value="yes" ></td>				
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						See Customers Password:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="see_customer_passwords" type="checkbox" value="yes" ></td>					
               </tr>
               
               
               
               
                   <tr>  
                 <td bgcolor="#DDDDDD" align="left"><div align="right">
						Search who an orders belongs to:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="search_who_order_belongs_to" type="checkbox" value="yes" ></td>		
                    
                      
                      		
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">Lnc Reward (OptiPoints):</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="lnc_reward_management" type="checkbox" value="yes"></td>					
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
</body>
</html>
