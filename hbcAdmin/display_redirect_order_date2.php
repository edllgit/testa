<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("lab_confirmation_func.inc.php");
include("fax_lab_confirm_func.inc.php");
include("../includes/calc_functions.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$orderQuery="select order_num, prescript_lab, user_id, order_status,additional_dsc,discount_type,extra_product,extra_product_price, order_date_processed, patient_ref_num, order_patient_first, order_patient_last from orders
 WHERE prescript_lab='$lab_pkey' AND order_date_processed = '".  $_REQUEST['date'] . "'"  ; //get order's user id and additional discount

$orderResult=mysql_query($orderQuery)	or die  ('I cannot select items because: ' . mysql_error());

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
<style>
@media print
{
table {page-break-after:always}
}
</style>
</head>
<body>
            
<?php  
while  ($orderData=mysql_fetch_array($orderResult))
{
$userQuery="select * from accounts WHERE user_id='$orderData[user_id]'"; //find user's data
$userResult=mysql_query($userQuery)	or die  ('I cannot select items because: ' . mysql_error());	
$userData=mysql_fetch_array($userResult);
?> 
                
			<div style="page-break-after:always;">
			<table width="650px" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo $message;?>
			<tr>
			  <td colspan="8" valign="middle" bgcolor="#666666">
                <span class="formField3 style1"><strong>ORDER STATUS: <?php 
				 if($orderData[order_status]=="cancelled")			 	echo " Cancelled";
				 if($orderData[order_status]=="all") 					echo " All";
				 if($orderData[order_status]=="open") 					echo " Open";
				 if($orderData[order_status]=="processing") 			echo " Confirmed";
				 if($orderData[order_status]=="order imported")			echo " Order Imported";
				 if($orderData[order_status]=="job started") 			echo " Surfacing";
				 if($orderData[order_status]=="in coating") 			echo " In Coating";
				 if($orderData[order_status]=="in mounting") 			echo " In Mounting";
				 if($orderData[order_status]=="in edging") 				echo " In Edging";
				 if($orderData[order_status]=="order completed") 		echo " Order Completed";
				 if($orderData[order_status]=="delay issue 0")			echo " Delay Issue 0";
				 if($orderData[order_status]=="delay issue 1") 			echo " Delay Issue 1";
				 if($orderData[order_status]=="delay issue 2") 			echo " Delay Issue 2";
				 if($orderData[order_status]=="delay issue 3") 			echo " Delay Issue 3";
				 if($orderData[order_status]=="delay issue 4") 			echo " Delay Issue 4";
				 if($orderData[order_status]=="delay issue 5") 			echo " Delay Issue 5";
				 if($orderData[order_status]=="delay issue 6")			echo " Delay Issue 6";
				 if($orderData[order_status]=="waiting for frame") 		echo " Waiting for Frame";
				 if($orderData[order_status]=="waiting for frame store") 	echo " Waiting for Frame Store";
				 if($orderData[order_status]=="waiting for frame ho/supplier") 	echo " Waiting for Frame Head Office/Supplier";
				 if($orderData[order_status]=="in transit") 			echo " In Transit";
				 if($orderData[order_status]=="filled")					echo " Shipped";?>
                   
                   </strong></span>
			</td>
			  </tr>
			<tr>
			  <td colspan="8" align="left" valign="middle"><div class="formField2">Order Number: <?php echo $orderData[order_num];?>			    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              P.O. Number: <?php echo $_GET[po_num];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Patient Reference Number: <?php echo $orderData[patient_ref_num] . " " . $orderData[order_patient_first] . " " . $orderData[order_patient_last];?></div></td>
				</tr>
			
			
			<?php
			$order_num=$orderData[order_num];
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$lab_pkey=$_SESSION["lab_pkey"];
			
			
						$query="SELECT * from orders WHERE prescript_lab='$lab_pkey' and order_num='$order_num' and order_product_type='exclusive' and lab!='$lab_pkey' ORDER by order_num";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			

					
					while ($listItem=mysql_fetch_array($result)){
						include("redirect_prescrOrderHistory.inc.php");
						echo '</div>';
					} 
			}?>
			
</td> </tr>
  <tr><td>&nbsp;</td></tr>  <tr><td>&nbsp;</td></tr>  
    <tr><td>&nbsp;</td></tr>   <tr><td>&nbsp;</td></tr>   <tr><td>&nbsp;</td></tr>
      <tr><td>&nbsp;</td></tr>   <tr><td>&nbsp;</td></tr>   <tr><td>&nbsp;</td></tr>
        
</table>
 
  <?php } ?>




  <p>&nbsp;</p>
  
</body>
</html>
