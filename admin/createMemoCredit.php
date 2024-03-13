<?php
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
$lab_pkey=$_SESSION["lab_pkey"];
$today=date("m/d/Y");
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("../includes/calc_functions.inc.php");
$patient_ref_num=$_SESSION["patient_ref_num"];
$order_num=$_SESSION["order_num"];
if($_POST[emailMemo]=="Email Memo to Customer"){
	$email_test=email_memo_credit();
	if($email_test)
		$heading="CREATE MEMO ORDER - MEMO ORDER $_POST[mcred_memo_num] EMAILED TO CUSTOMER";
}

if($_GET["order_num"]!=""){
		$_SESSION["patient_ref_num"]="";
		$_SESSION["order_num"]=$_GET["order_num"];
		$orderQuery="select order_num, user_id, order_status, additional_dsc, discount_type, extra_product, extra_product_price, order_date_processed, order_patient_first, order_patient_last, patient_ref_num, order_total from orders WHERE order_num='$_GET[order_num]'  limit 1"; //get order's user id and additional discount
	}
	elseif(($_POST["order_num"]!="")&&($_POST["patient_ref_num"]!="")){
		$patient_ref_num="%".$_POST["patient_ref_num"]."%";
		$_SESSION["patient_ref_num"]=$patient_ref_num;
		$_SESSION["order_num"]=$_POST["order_num"];
		$orderQuery="select order_num, user_id, order_status, additional_dsc, discount_type, extra_product, extra_product_price, order_date_processed, order_patient_first, order_patient_last, patient_ref_num, order_total from orders WHERE order_num='$_POST[order_num]'  AND (patient_ref_num like '$patient_ref_num' OR order_patient_first like '$patient_ref_num' OR order_patient_last like '$patient_ref_num') limit 1"; //get order's user id and additional discount
	}
	elseif(($_POST["order_num"]=="")&&($_POST["patient_ref_num"]!="")){
		$patient_ref_num="%".$_POST["patient_ref_num"]."%";
		$_SESSION["patient_ref_num"]=$patient_ref_num;
		$_SESSION["order_num"]="";
		$orderQuery="select order_num, user_id, order_status, additional_dsc, discount_type, extra_product, extra_product_price, order_date_processed, order_patient_first, order_patient_last, patient_ref_num, order_total from orders WHERE  (patient_ref_num like '$patient_ref_num' OR order_patient_first like '$patient_ref_num' OR order_patient_last like '$patient_ref_num') group by order_num"; //get order's user id and additional discount
	}
	elseif(($_POST["order_num"]!="")&&($_POST["patient_ref_num"]=="")){
		$_SESSION["patient_ref_num"]="";
		$_SESSION["order_num"]=$_POST["order_num"];
		$orderQuery="select order_num, user_id, order_status, additional_dsc, discount_type, extra_product, extra_product_price, order_date_processed, order_patient_first, order_patient_last, patient_ref_num, order_total from orders WHERE order_num='$_POST[order_num]'  limit 1"; //get order's user id and additional discount
	}
	elseif(($_POST["order_num"]=="")&&($_POST["patient_ref_num"]=="")){
		$_SESSION["patient_ref_num"]="";
		$_SESSION["order_num"]=="";
		header("Location:findMC_order.php?message=11");	
	}
	$orderResult=mysql_query($orderQuery)
		or die  ('I cannot select items because: ' . mysql_error());
	$orderCount=mysql_num_rows($orderResult);
	if($orderCount==0)
		header("Location:findMC_order.php?message=1");	
	
	if($orderCount==1){
		$include_file="memoCreditForm.php";		
		$orderData=mysql_fetch_array($orderResult);
	
		$userQuery="select * from accounts WHERE user_id='$orderData[user_id]'"; //find user's data
		$userResult=mysql_query($userQuery)
			or die  ('I cannot select items because: ' . mysql_error());
			
		$userData=mysql_fetch_array($userResult);
	
		$lastMemoQuery="select mcred_memo_num from memo_credits_rebilling WHERE mcred_order_num='$orderData[order_num]' ORDER BY mcred_memo_num DESC limit 1"; //get the most recent memo credit for this order number
		$lastMemoResult=mysql_query($lastMemoQuery)
			or die  ('I cannot select items because: ' . mysql_error());
		$lastNumTest=mysql_num_rows($lastMemoResult);
		if($lastNumTest==0){
			$lastMemoNum="M" . $orderData[order_num] . "A";
		}else{
			$lastNum=mysql_fetch_array($lastMemoResult);
			$lastMemoNum=++$lastNum[mcred_memo_num];
		}
	
		$memoQuery="select * from memo_credits_rebilling  WHERE mcred_order_num='$orderData[order_num]' ORDER BY mcred_memo_num"; //get all memo credits for this order number
		$memoResult=mysql_query($memoQuery)
			or die  ('I cannot select items because: ' . mysql_error());
	}
	if($orderCount>1){
		$include_file="orderMC_list.php";		
	}
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
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("mcred_amount", "mcred_date");
	// field description to appear in the dialog box
	var fieldDescription = Array("Discount Value", "Date");
	// dialog message
	var alertMsg = "Please enter:\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "select-one":
				if (obj.selectedIndex == "" || obj.options[obj.selectedIndex].text == ""){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "select-multiple":
				if (obj.selectedIndex == -1){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			default:
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length != l_Msg){
		alert(alertMsg);
		return false;
	}else{
	var goodEmail=document.form3.email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.biz)|(\..{2,2}))$)\b/gi);
	if (!goodEmail){
		var emailMsg="The email address you've entered doesn't appear to be valid. \nPlease edit the email field and resubmit.\n";
		alert(emailMsg);
		return false;
		}	
	}
}
// -->
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
		include($include_file);
		?>  <p>&nbsp;</p>
</td>
	  </tr>
</table>
  
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
