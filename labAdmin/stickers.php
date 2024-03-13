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

$queryLab = "SELECT produce_lens from labs WHERE primary_key =  '$lab_pkey'";
$rptLab=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
$DataLab=mysql_fetch_array($rptLab);
$product_lens = $DataLab[produce_lens];


if ($product_lens =="yes")
{
$orderQuery="select * from orders
 WHERE order_status <> 'cancelled' AND order_status <> 'filled' AND prescript_lab='$lab_pkey' AND order_date_processed = '".  $_REQUEST['date'] . "'"  ; //get order's user id and additional discount
 if ($_SESSION["accessid"]== 136)
 $orderQuery .= " AND lab <> 37";
}else{
$orderQuery="select * from orders
 WHERE order_status <> 'cancelled' AND order_status <> 'filled' AND lab='$lab_pkey' AND order_date_processed = '".  $_REQUEST['date'] . "'"  ; //get order's user id and additional discount
  if ($_SESSION["accessid"]== 136)
 $orderQuery .= " AND lab <> 37";
}
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
                
			
			<table style="font-size:9px;font-family:Arial, Helvetica, sans-serif;width:280px;" border="0" cellpadding="2" cellspacing="0">
				<tr>
			  		<td colspan="6" align="left" valign="middle">
                    	<span style="font-size:9px;">
                        <b>Code Client :</b> <?php echo $userData[account_num];?>
						&nbsp;<b>Client :</b> <?php echo $userData[company];?>
                        </span>
                    </td>
            	</tr>
            
            <?php 
			$queryUPC = "SELECT temple_model_num  from extra_product_orders WHERE order_num = $orderData[order_num] AND	category=\"Frame\"";
			$resultUPC=mysql_query($queryUPC)		or die  ('I cannot select items because: ' . mysql_error());
			$DataUPC=mysql_fetch_array($resultUPC);
			?>
            
				<tr>
            		<td colspan="6">
                    	<span style="font-size:9px;"><b>Porteur :</b> <?php echo  $orderData[order_patient_first] . " " . $orderData[order_patient_last];?>
            			&nbsp;<b>Monture:</b>
						<?php if ($DataUPC[temple_model_num] <> ''){
			 				echo  $DataUPC[temple_model_num];
							 }else{
			 				echo '-';
			 			}?>
            			</span>
                   </td>
            	</tr>
			
			<?php
			$order_num=$orderData[order_num];
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$lab_pkey=$_SESSION["lab_pkey"];
			
			if ($product_lens =="yes")
			{
			$query="SELECT * from orders WHERE prescript_lab='$lab_pkey' and order_num='$order_num' and order_product_type='exclusive' and lab!='$lab_pkey' ORDER by order_num";//SELECT ALL OPEN PRESCRIPTION ORDERS
			}else{
			$query="SELECT * from orders WHERE lab='$lab_pkey' and order_num='$order_num' and order_product_type='exclusive'  ORDER by order_num";//SELECT ALL OPEN PRESCRIPTION ORDERS
			}
						
			$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
					
					while ($listItem=mysql_fetch_array($result)){
						include("redirect_prescrOrderHistorySticker.inc.php");
						
					} 
			}?>		
        
</table>

  <?php } ?>
  <p>&nbsp;</p>
  
</body>
</html>