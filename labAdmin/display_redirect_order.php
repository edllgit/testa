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

//if ($_POST[billCard]=="Enter Payment"){//admin enters order payment
//	$_GET[order_num]=makeAdminPmt($_POST[user_id]);
//}



$orderQuery="select user_id, order_status,additional_dsc,discount_type,extra_product,extra_product_price, order_date_processed, patient_ref_num, order_patient_first, order_patient_last from orders WHERE order_num='$_GET[order_num]' limit 1"; //get order's user id and additional discount
$orderResult=mysql_query($orderQuery)
	or die  ('I cannot select items because: ' . mysql_error());
		
$orderData=mysql_fetch_array($orderResult);


$userQuery="select * from accounts WHERE user_id='$orderData[user_id]'"; //find user's data
$userResult=mysql_query($userQuery)
	or die  ('I cannot select items because: ' . mysql_error());
		
$userData=mysql_fetch_array($userResult);

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script src="../formFunctions.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function validate(theForm)
{

  if (theForm.TRAY.value== "")
  {
    alert("You must enter a value in the \"Tray Reference\" field.");
    theForm.TRAY.focus();
    return (false);
  }
  }

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
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
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">DISPLAY
            					REDIRECTION LAB ORDER</font></b></td>
           		</tr>
			<tr><td>
			<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo $message;?>
			<tr>
			  <td colspan="2" valign="middle" bgcolor="#666666">
                <span class="formField3 style1"><strong>ORDER STATUS: <?php if($orderData[order_status]=="cancelled") echo "Cancelled";
				 if($orderData[order_status]=="all")				echo " All";
				 if($orderData[order_status]=="open") 				echo " Open";
				 if($orderData[order_status]=="cancelled") 			echo " Cancelled";
				 if($orderData[order_status]=="processing") 		echo " Confirmed";
				 if($orderData[order_status]=="order imported") 	echo " Order Imported";
				 if($orderData[order_status]=="job started") 		echo " Surfacing";
				 if($orderData[order_status]=="in coating")	    	echo " In Coating";
				 if($orderData[order_status]=="in mounting") 		echo " In Mounting";
				 if($orderData[order_status]=="in edging") 		    echo " In Edging";
				 if($orderData[order_status]=="order completed")	echo " Order Completed";
				 if($orderData[order_status]=="delay issue 0") 	 	echo " Delay Issue 0";
				 if($orderData[order_status]=="delay issue 1") 	 	echo " Delay Issue 1";
				 if($orderData[order_status]=="delay issue 2")  	echo " Delay Issue 2";
				 if($orderData[order_status]=="delay issue 3")  	echo " Delay Issue 3";
				 if($orderData[order_status]=="delay issue 4")  	echo " Delay Issue 4";
				 if($orderData[order_status]=="delay issue 5")  	echo " Delay Issue 5";
				 if($orderData[order_status]=="delay issue 6")  	echo " Delay Issue 6";
				 if($orderData[order_status]=="waiting for frame")  echo " Waiting for Frame";
				 if($orderData[order_status]=="in transit") 		echo " In Transit";
				 if($orderData[order_status]=="filled") 		    echo " Shipped";?>
                   
                   </strong></span>
			 </td>
			  </tr>
			<tr>
			  <td width="20%" align="left" valign="middle"><div class="formField2">Order Number: <?php echo $_GET[order_num];?></div>			    </td>
			  </tr>
			<tr><td colspan="2" align="left"><div class="formField3">P.O. Number: <?php echo $_GET[po_num];?></div></td>
			  </tr>
			<tr>
				<td colspan="2" align="left"><div class="formField3">
					Patient Reference Number: <?php echo $orderData[patient_ref_num] . " " . $orderData[order_patient_first] . " " . $orderData[order_patient_last];?></div></td>
				</tr>
			</table>
			</td></tr>
			<tr><td><?php
			$order_num=$_GET[order_num];
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$prescrQuantity=0;
					
			$lab_pkey=$_SESSION["lab_pkey"];
			
			
						$query="SELECT * from orders WHERE prescript_lab='$lab_pkey' and order_num='$order_num' and order_product_type='exclusive' and lab!='$lab_pkey' ORDER by order_num";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			if ($usercount != 0){
			
			 echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formField3\">
              <tr>
                <td bgcolor=\"#000000\"><font color=\"#FFFFFF\">PRESCRIPTION ITEMS</font></td>
              </tr>
            </table>";
					
					while ($listItem=mysql_fetch_array($result)){
					$order_shipping_method=$listItem[order_shipping_method];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_price]+$over_range+$listItem[extra_product_price]-$coupon_dsc;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*$listItem[order_product_discount]+$over_range+$listItem[extra_product_price]-$coupon_dsc;
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("redirect_prescrOrderHistory.inc.php");
					} 
			}
			
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0))
					include("redirect_displayHistoryFooter.inc.php");
				
				?>
	<div class="formField3">
		<a href="http://www.direct-lens.com/labAdmin/reports_redirection_lab.php">Back to Order List</a>
	</div></td>
  </tr>
</table>
 &nbsp;<br></td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
