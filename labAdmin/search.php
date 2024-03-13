<?php require_once('../Connections/directlens.php'); ?>
<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	echo "You are not logged in. Click <a href='/labAdmin'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");


$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);

$user_id=$_SESSION["sessionUser_Id"];

if ($_POST[from_form_order_num]=="true"){
	$order_num=$_POST[order_num];
	$querySearch="SELECT labs.lab_name, accounts.main_lab,orders.order_product_name, accounts.user_id as theUserID, orders.order_num AS order_no, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (accounts) ON (orders.user_id=accounts.user_id)
	LEFT JOIN (labs) ON (accounts.main_lab=labs.primary_key)
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE orders.order_num='$order_num'  GROUP by orders.order_num";
	
}//END IF FROM FORM ORDER NUM

if ($_POST[from_form_patient_ref]=="true"){
	$patient_ref_num=$_POST[patient_ref_num];
	$querySearch="SELECT labs.lab_name, accounts.main_lab, accounts.user_id as theUserID, orders.order_product_name, orders.order_num AS order_no,  orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed,orders.patient_ref_num, order_patient_last, order_patient_first, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (accounts) ON (orders.user_id=accounts.user_id)
	LEFT JOIN (labs) ON (accounts.main_lab=labs.primary_key)
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE (orders.patient_ref_num like '$patient_ref_num' OR order_patient_first like '$patient_ref_num' OR order_patient_last like '$patient_ref_num')  GROUP by orders.order_num";	
}//END IF FROM FORM ORDER NUM


if ($_POST[from_form_tray_num]=="true"){

	$tray_num=$_POST[tray_num];
	$querySearch="SELECT labs.lab_name, accounts.main_lab,orders.order_product_name, accounts.user_id as theUserID, orders.order_num AS order_no, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.tray_num, orders.order_product_type, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (accounts) ON (orders.user_id=accounts.user_id)
	LEFT JOIN (labs) ON (accounts.main_lab=labs.primary_key)
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE orders.tray_num='$tray_num'    ORDER BY orders.order_Date_processed desc ";

$_SESSION["QUERY"]=$query;


}//END IF FROM FORM ORDER NUM

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php echo "<div style='font-size:10px'>Welcome Back ".$_SESSION["labAdminData"]["username"]."</div>"; ?>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="search_form" id="search_form" action="search.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						Order Number
					</div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="order_num" type="text" id="order_num" size="12" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="Search by Order Number" class="formText">
				
					  <input name="from_form_order_num" type="hidden" id="from_form_order_num" value="true"></td>
				</tr>
			</table>
</form><form  method="post" name="search_form" id="search_form" action="search.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						Patient Reference Number
					</div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="patient_ref_num" type="text" id="patient_ref_num" size="12" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="Search by Patient Reference Number" class="formText">
				
					  <input name="from_form_patient_ref" type="hidden" id="from_form_patient_ref" value="true"></td>
				</tr>
			</table>
</form>


<form  method="post" name="search_form" id="search_form" action="search.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						Tray Num
					</div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="tray_num" type="text" id="tray_num" size="15" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="Search by Tray Num" class="formText">
				
					  <input name="from_form_tray_num" type="hidden" id="from_form_tray_num" value="true"></td>
				</tr>
			</table>
</form>
			

			<?php 
			if ($querySearch !=""){
			$result=mysql_query($querySearch)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			}
			

echo "<table width=\"1000\" border=\"0\" align=\"center\" cellpadding=\"2\" cellspacing=\"0\"  class=\"formBox\"><tr >
                <td colspan=\"7\" bgcolor=\"#000098\" class=\"tableHead\">".$message."</td><td colspan=\"2\" bgcolor=\"#000098\" ><div  class=\"tableHead\" >";
								
				echo "</div></td>";
  echo "</tr>
              <tr>
                <td align=\"center\" class=\"formCell\">Order #</td>
				<td align=\"center\" class=\"formCell\">Compte</td>
				<td align=\"center\" class=\"formCell\">Main lab</td>
				<td align=\"center\" class=\"formCell\">Tray</td>
				<td align=\"center\" class=\"formCell\">Pat. Ref. #</td>
                <td align=\"center\" class=\"formCell\">Pat. Last Name</td>
                <td align=\"center\" class=\"formCell\">Order Date</td>
				<td align=\"center\" class=\"formCell\">Product</td>
                <td align=\"center\" class=\"formCell\">Order Status</td>";
               
			  echo "</tr>";
if ($usercount > 0)
{		  
	while ($listItem=mysql_fetch_array($result)){
	
				$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
				$formated_date=mysql_result($new_result,0,0);
				
				if ($listItem[order_date_shipped]!="0000-00-00"){
					$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
					$ship_date=mysql_result($new_result,0,0);}
				else {
					if (($listItem[est_ship_date]!="0000-00-00")&&($listItem[est_ship_date]!=NULL)){
						$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[est_ship_date]','%m-%d-%Y')");
						$ship_date="<b>est:</b> ".mysql_result($new_result,0,0);}
					else{ $ship_date="<b>est:</b> TBD";}
					}
			echo  "<tr>
					<td align=\"center\"  class=\"formCell\">$listItem[order_no]</td>
					 <td align=\"center\"  class=\"formCell\">$listItem[theUserID]</td>
					 <td align=\"center\"  class=\"formCell\">$listItem[lab_name]</td>
					 <td align=\"center\"  class=\"formCell\">$listItem[tray_num]</td>
					<td align=\"center\"  class=\"formCell\">$listItem[patient_ref_num]</td>
					<td align=\"center\"  class=\"formCell\">$listItem[order_patient_last]</td>
					<td align=\"center\"  class=\"formCell\">$formated_date</td>
					<td align=\"center\"  class=\"formCell\">$listItem[order_product_name]</td>
					<td align=\"center\" class=\"formCell\">";
				
					switch($listItem[order_status])
						{
							case 'processing':				echo "Confirmed";					break;
							case 'order imported':			echo "Order Imported";				break;
							case 'job started':				echo "Surfacing";					break;
							case 'in coating':				echo "In Coating";					break;
							case 'in mounting':				echo "In Mounting";					break;
							case 'in edging':				echo "In Edging";					break;
							case 'order completed':			echo "Order Completed";				break;
							case 'delay issue 0':			echo "Delay Issue 0";				break;
							case 'delay issue 1':			echo "Delay Issue 1";				break;
							case 'delay issue 2':			echo "Delay Issue 2";				break;
							case 'delay issue 3':			echo "Delay Issue 3";				break;
							case 'delay issue 4':			echo "Delay Issue 4";				break;
							case 'delay issue 5':			echo "Delay Issue 5";				break;
							case 'delay issue 6':			echo "Delay Issue 6";				break;
							case 'waiting for frame':		echo "Waiting for Frame";			break;
							case 'waiting for frame swiss':		echo "Waiting for Frame Swiss";			break;
							case 'waiting for shape':		echo "Waiting for Shape";			break;
							case 're-do':					echo "Redo";						break;
							case 'in transit':				echo "In Transit";					break;
							case 'interlab':				echo "Interlab P";					break;
							case 'interlab vot':			echo "Interlab P";					break;
							case 'interlab qc':				echo "Interlab QC";					break;
							case 'waiting for lens':		echo "Waiting for lens";			break;
							case 'filled':					echo "Shipped";						break;
							case 'information in hand':		echo "Info in Hand";				break;
							case 'on hold':					echo "On Hold";						break;
							case 'cancelled':				echo "Cancelled";					break;
							case 'waiting for frame store':		echo "Waiting for Frame Store";			break;
							case 'waiting for frame ho/supplier':		echo "Waiting for Frame Head Office/Supplier";			break;
						}
					
					echo "</td></tr>";
	
			}//END WHILE
}//End IF $usercount >0			
		echo "</table><br>";

?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
</body>
</html>