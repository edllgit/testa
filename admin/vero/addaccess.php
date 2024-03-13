<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

session_start();
if ($_SESSION[adminData][username]!="superadmin"){
	echo  "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../../Connections/sec_connect.inc.php");

$heading="Add Main Admin Access";
if($_SESSION[formVars])
	$heading="ADMIN NEW LAB FORM - User ID exists, please try a different User ID";

if (isset($_POST['name'])) {




$manage_memo_credit_global = $_POST['manage_memo_credit_global'];
if ($manage_memo_credit_global == ""){
$manage_memo_credit_global = 'no';
}else{
$manage_memo_credit_global = 'yes';
}

$fast_shipping_tool = $_POST['fast_shipping_tool'];
if ($fast_shipping_tool == ""){
$fast_shipping_tool = 'no';
}else{
$fast_shipping_tool = 'yes';
}

$fast_redirecting_tool = $_POST['fast_redirecting_tool'];
if ($fast_redirecting_tool == ""){
$fast_redirecting_tool = 'no';
}else{
$fast_redirecting_tool = 'yes';
}


$approve_memo_credit = $_POST['approve_memo_credit'];
if ($approve_memo_credit == ""){
$approve_memo_credit = 'no';
}else{
$approve_memo_credit = 'yes';
}


$customer_password_history = $_POST['customer_password_history'];
if ($customer_password_history == ""){
$customer_password_history = 'no';
}else{
$customer_password_history = 'yes';
}


$edit_customer_buying_group = $_POST['edit_customer_buying_group'];
if ($edit_customer_buying_group == ""){
$edit_customer_buying_group = 'no';
}else{
$edit_customer_buying_group = 'yes';
}



$edit_customer_discounts = $_POST['edit_customer_discounts'];
if ($edit_customer_discounts == ""){
$edit_customer_discounts = 'no';
}else{
$edit_customer_discounts = 'yes';
}


$management_people_report = $_POST['management_people_report'];
if ($management_people_report == ""){
$management_people_report = 'no';
}else{
$management_people_report = 'yes';
}

$edit_bbg_account = $_POST['edit_bbg_account'];
if ($edit_bbg_account == ""){
$edit_bbg_account = 'no';
}else{
$edit_bbg_account = 'yes';
}

$approve_bbg_account = $_POST['approve_bbg_account'];
if ($approve_bbg_account == ""){
$approve_bbg_account = 'no';
}else{
$approve_bbg_account = 'yes';
}


$edit_products = $_POST['edit_products'];
if ($edit_products == ""){
$edit_products = 'no';
}else{
$edit_products = 'yes';
}

$report_promo = $_POST['report_promo'];
if ($report_promo == ""){
$report_promo = 'no';
}else{
$report_promo = 'yes';
}

$edit_dl_account = $_POST['edit_dl_account'];
if ($edit_dl_account == ""){
$edit_dl_account = 'no';
}else{
$edit_dl_account = 'yes';
}

$approve_dl_account = $_POST['approve_dl_account'];
if ($approve_dl_account == ""){
$approve_dl_account = 'no';
}else{
$approve_dl_account = 'yes';
}

$edit_ln_account = $_POST['edit_ln_account'];
if ($edit_ln_account == ""){
$edit_ln_account = 'no';
}else{
$edit_ln_account = 'yes';
}

$approve_ln_account = $_POST['approve_ln_account'];
if ($approve_ln_account == ""){
$approve_ln_account = 'no';
}else{
$approve_ln_account = 'yes';
}

$manage_labadmin_access = $_POST['manage_labadmin_access'];
if ($manage_labadmin_access == ""){
$manage_labadmin_access = 'no';
}else{
$manage_labadmin_access = 'yes';
}


$order_report = $_POST['order_report'];
if ($order_report == ""){
$order_report = 'no';
}else{
$order_report = 'yes';
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

$exclusive_product_total = $_POST['exclusive_product_total'];
if ($exclusive_product_total == ""){
$exclusive_product_total = 'no';
}else{
$exclusive_product_total = 'yes';
}

$index_total = $_POST['index_total'];
if ($index_total == ""){
$index_total = 'no';
}else{
$index_total = 'yes';
}

$re_billing_statement = $_POST['re_billing_statement'];
if ($re_billing_statement == ""){
$re_billing_statement = 'no';
}else{
$re_billing_statement = 'yes';
}

$coupon_code_usage = $_POST['coupon_code_usage'];
if ($coupon_code_usage == ""){
$coupon_code_usage = 'no';
}else{
$coupon_code_usage = 'yes';
}

$list_stock_products = $_POST['list_stock_products'];
if ($list_stock_products == ""){
$list_stock_products = 'no';
}else{
$list_stock_products = 'yes';
}

$list_exclusive_products = $_POST['list_exclusive_products'];
if ($list_exclusive_products == ""){
$list_exclusive_products = 'no';
}else{
$list_exclusive_products = 'yes';
}

$add_exclusive_product = $_POST['add_exclusive_product'];
if ($add_exclusive_product == ""){
$add_exclusive_product = 'no';
}else{
$add_exclusive_product = 'yes';
}

$add_stock_price = $_POST['add_stock_price'];
if ($add_stock_price == ""){
$add_stock_price = 'no';
}else{
$add_stock_price = 'yes';
}

$frame_collections = $_POST['frame_collections'];
if ($frame_collections == ""){
$frame_collections = 'no';
}else{
$frame_collections = 'yes';
}

$frames = $_POST['frames'];
if ($frames == ""){
$frames = 'no';
}else{
$frames = 'yes';
}

$frames_temple_colors = $_POST['frames_temple_colors'];
if ($frames_temple_colors == ""){
$frames_temple_colors = 'no';
}else{
$frames_temple_colors = 'yes';
}

$edit_lab = $_POST['edit_lab'];
if ($edit_lab == ""){
$edit_lab = 'no';
}else{
$edit_lab = 'yes';
}

$add_lab = $_POST['add_lab'];
if ($add_lab == ""){
$add_lab = 'no';
}else{
$add_lab = 'yes';
}

$edit_buying_group = $_POST['edit_buying_group'];
if ($edit_buying_group == ""){
$edit_buying_group = 'no';
}else{
$edit_buying_group = 'yes';
}

$add_buying_group = $_POST['add_buying_group'];
if ($add_buying_group == ""){
$add_buying_group = 'no';
}else{
$add_buying_group = 'yes';
}

$coupon_codes = $_POST['coupon_codes'];
if ($coupon_codes == ""){
$coupon_codes = 'no';
}else{
$coupon_codes = 'yes';
}

$upload_new_promotion = $_POST['upload_new_promotion'];
if ($upload_new_promotion == ""){
$upload_new_promotion = 'no';
}else{
$upload_new_promotion = 'yes';
}

$collection_rewards = $_POST['collection_rewards'];
if ($collection_rewards == ""){
$collection_rewards = 'no';
}else{
$collection_rewards = 'yes';
}



$queryInsert = "Insert into ACCESS_ADMIN (fast_redirecting_tool, fast_shipping_tool, name,username,password,edit_dl_account,approve_dl_account,edit_ln_account,approve_ln_account,manage_labadmin_access,order_report,all_product_total,dream_ar_total,exclusive_product_total,index_total,re_billing_statement,coupon_code_usage,
list_stock_products,list_exclusive_products,add_exclusive_product,add_stock_price,frame_collections,frames,frames_temple_colors,edit_lab,
add_lab,edit_buying_group,add_buying_group,coupon_codes,upload_new_promotion,collection_rewards,edit_products,report_promo, approve_bbg_account, edit_bbg_account, management_people_report,edit_customer_discounts, edit_customer_buying_group, customer_password_history,approve_memo_credit,manage_memo_credit_global) 
VALUES ('$fast_redirecting_tool','$fast_shipping_tool','$_POST[name]','$_POST[username]','$_POST[password]','$edit_dl_account','$approve_dl_account','$edit_ln_account','$approve_ln_account','$manage_labadmin_access','$order_report','$all_product_total','$dream_ar_total','$exclusive_product_total','$index_total','$re_billing_statement','$coupon_code_usage',
'$list_stock_products','$list_exclusive_products','$add_exclusive_product','$add_stock_price','$frame_collections','$frames','$frames_temple_colors','$edit_lab',
'$add_lab','$edit_buying_group','$add_buying_group','$coupon_codes','$upload_new_promotion','$collection_rewards','$edit_products','$report_promo', '$approve_bbg_account', '$edit_bbg_account', '$management_people_report', '$edit_customer_discounts',  '$edit_customer_buying_group', '$customer_password_history', '$approve_memo_credit','$manage_memo_credit_global')";
//echo 'query:' . $queryInsert . '<br><br>';

$QueryResult=mysql_query($queryInsert)	or die ("Error: Could not create access" . mysql_error());
echo 'Main admin Access created.';
header("Location:listaccess.php");
exit();
}

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../admin.css" rel="stylesheet" type="text/css" />
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
		include("../adminNav.php");
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
          
            <tr><td>&nbsp;</td></tr>
            
            
				<tr bgcolor="#FFFFFF">
               
				<td bgcolor="#DDDDDD" ><div align="right"><b>ACCOUNTS</b></div></td>

				</tr>
                
                <tr bgcolor="#FFFFFF">
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Direct-Lens Accounts:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_dl_account" type="checkbox" value="yes"></td>					
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Approve Direct-Lens Accounts:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="approve_dl_account" type="checkbox" value="yes"></td>				
                    
                    
                         <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit LensNet Club Accounts:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_ln_account" type="checkbox" value="yes"></td>	
                    

                			
                </tr>
                
                
                <tr>
                      <td bgcolor="#DDDDDD" align="left"><div align="right">Approve LensNet Club Accounts:<br>		</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="approve_ln_account" type="checkbox" value="yes"></td>	
                    
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">Approve BBG Club Accounts:<br>		</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="approve_bbg_account" type="checkbox" value="yes"></td>	
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">Edit BBG Club Accounts:<br>		</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_bbg_account" type="checkbox" value="yes"></td>	
                    
                    
                    </tr>
                    
                    
                    
                    
                       <tr>
                      <td bgcolor="#DDDDDD" align="left"><div align="right">Edit Customers Discounts:<br>		</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_customer_discounts" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">Edit Customers Buying Group:<br>		</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_customer_buying_group" type="checkbox" value="yes"></td>		
                    
                      <td bgcolor="#DDDDDD" align="left">&nbsp;</td>
					<td bgcolor="#DDDDDD" align="left">&nbsp;</td>	
                    
                    
                    </tr>
                    
                    
                    
                    
                    
                        <tr><td>&nbsp;</td></tr>
            
				<tr bgcolor="#FFFFFF">
               
				<td bgcolor="#DDDDDD" ><div align="right"><b>ACCESS</b></div></td>

				</tr>
                
                <tr bgcolor="#FFFFFF">
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage LabAdmin Access:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="manage_labadmin_access" type="checkbox" value="yes"></td>					
                			
                </tr>
                
                
                    <tr><td>&nbsp;</td></tr> 
                
                
                
                			<tr bgcolor="#FFFFFF">
               
				<td bgcolor="#DDDDDD" ><div align="right"><b>CREDITS REQUEST</b></div></td>

				</tr>
                
                <tr bgcolor="#FFFFFF">
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Credit Request:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="approve_memo_credit" type="checkbox" value="yes" 
					<?php if($LeData['approve_memo_credit']=="yes") echo " checked"; ?>></td>			
                    
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
						Manage Memo Credit Global:<br>
					</div></td>
                    	<td bgcolor="#DDDDDD" align="left"><input name="manage_memo_credit_global" type="checkbox" value="yes" 
					<?php if($LeData['manage_memo_credit_global']=="yes") echo " checked"; ?>></td>					
                			
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
						All Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="all_product_total" type="checkbox" value="yes"></td>					

                    
                               
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Dream AR Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="dream_ar_total" type="checkbox" value="yes"></td>				
                    
                    
                </tr>
                
               
                
                
                <tr bgcolor="#FFFFFF">
                       
                        
                	   <td bgcolor="#DDDDDD" align="left"><div align="right">
						Exclusive Products Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="exclusive_product_total" type="checkbox" value="yes"></td>	
                        
                        
                       
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Index Totals:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="index_total" type="checkbox" value="yes"></td>					
                
                 
                          <td bgcolor="#DDDDDD" align="left"><div align="right">
						Re-billing Statement:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="re_billing_statement" type="checkbox" value="yes"></td>					
                
                 		
     

				</tr>
                
                
                <tr>
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Coupon code usage:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="coupon_code_usage" type="checkbox" value="yes"></td>		
              
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit/Delete Products:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_products" type="checkbox" value="yes"></td>		
                
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Report Promo (Innovative):<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="report_promo" type="checkbox" value="yes"></td>
                
                </tr>
                
                
                  <tr>
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						Management people report:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="management_people_report" type="checkbox" value="yes"></td>		
              
                
                <td bgcolor="#DDDDDD" align="left"><div align="right">
						Customer password history:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="customer_password_history" type="checkbox" value="yes"></td>		
              
                
                
                
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
						&nbsp;
					</div></td>
					<td bgcolor="#DDDDDD" align="left">&nbsp;</td>
                
                </tr>
                
                
                

                
               	<tr><td>&nbsp;</td></tr>


                    
                    
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>PRODUCTS</b></div></td>
                        
                           
                           <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						List Stock Products:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="list_stock_products" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						List Exclusive Products:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="list_exclusive_products" type="checkbox" value="yes"></td>	
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">
						Add an Exclusive Products:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="add_exclusive_product" type="checkbox" value="yes"></td>		

                </tr>  
                        
                     <tr>
                       <td bgcolor="#DDDDDD" align="left"><div align="right">
						Add a Stock Price:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="add_stock_price" type="checkbox" value="yes"></td>	
                     </tr>   
                           
                        	<tr><td>&nbsp;</td></tr>
                            
                            
                            
                            
                            
                            
                            
                    	<td bgcolor="#DDDDDD" ><div align="right"><b>FRAMES</b></div></td>
                        
                           
                           <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Frames Collections:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="frame_collections" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Frames:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="frames" type="checkbox" value="yes"></td>	
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">
						Frames Temple Colors:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="frames_temple_colors" type="checkbox" value="yes"></td>		

                </tr>  
                
                
                
                   	<tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>LABS</b></div></td>
                                                
                     <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Labs:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_lab" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Add a Lab:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="add_lab" type="checkbox" value="yes"></td>	
                    

                </tr>  
                
                
                
                
                 	<tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>BUYING GROUPS</b></div></td>
                                                
                     <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Edit Buying groups:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="edit_buying_group" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Add a Buying Group:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="add_buying_group" type="checkbox" value="yes"></td>	
                    

                </tr> 
                
                
                
                
                  	<tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>PROMOTIONS</b></div></td>
                                                
                     <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Coupon Codes:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="coupon_codes" type="checkbox" value="yes"></td>	
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Upload new promotion:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="upload_new_promotion" type="checkbox" value="yes"></td>	
                    

                </tr> 
                
                
                
                
                
                  
                  	<tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>LOYALTY REWARDS</b></div></td>
                                                
                     <tr>
                   
                    <td bgcolor="#DDDDDD" align="left"><div align="right">
						Collection rewards:<br>
					</div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="collection_rewards" type="checkbox" value="yes"></td>	
                    
                                    

                </tr> 
                
                
                
                   
                  	<tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>FAST SHIPPING</b></div></td>
                                                
                    <tr>
                    <td bgcolor="#DDDDDD" align="left"><div align="right">Fast Shipping Tool:<br></div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="fast_shipping_tool" type="checkbox" value="yes"></td>	
	                </tr> 
                    
                    
                    
                    <tr><td>&nbsp;</td></tr>
                    
                    
                 	<td bgcolor="#DDDDDD" ><div align="right"><b>FAST REDIRECTING</b></div></td>
                                                
                    <tr>
                    <td bgcolor="#DDDDDD" align="left"><div align="right">Fast Redirecting Tool:<br></div></td>
					<td bgcolor="#DDDDDD" align="left"><input name="fast_redirecting_tool" type="checkbox" value="yes"></td>	
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
