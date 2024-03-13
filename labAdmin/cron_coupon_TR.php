<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");
$time_start = microtime(true);

$today=date("Y-m-d");
$tomorrow = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$today = date("Y/m/d", $tomorrow);


//Date hard cod�
//$today = '2013-10-09';
  
  
  	
//Debut AIT
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today' 
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 47  GROUP BY code, user_id ";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());


	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
			    <td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
	
$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
$DataProduct=mysql_fetch_array($rptProduct);


if ($DataProduct[order_num] <> '-1' )
{

		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
               <td align=\"center\">$listItem[user_id]</td>
			   <td align=\"center\">$DataProduct[order_product_name]</td>
			   <td align=\"center\">$DataProduct[order_num]</td>
               <td align=\"center\">$listItem[code]</td>
			   <td align=\"center\">$listItem[NbrOrder]</td>
               <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject='Coupon codes used today: AIT:'.$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by AIT",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by AIT",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}	

//FIN AIT		
			
			
			
  
  
  
  
//Debut TR
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today' 
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 21 GROUP BY code, user_id ";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());


	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
			    <td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
	
$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
$DataProduct=mysql_fetch_array($rptProduct);


if ($DataProduct[order_num] <> '-1' )
{

		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
               <td align=\"center\">$listItem[user_id]</td>
			   <td align=\"center\">$DataProduct[order_product_name]</td>
			   <td align=\"center\">$DataProduct[order_num]</td>
               <td align=\"center\">$listItem[code]</td>
			   <td align=\"center\">$listItem[NbrOrder]</td>
               <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject='Coupon codes used today: Directlab Trois-Rivieres :'.$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by TR",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by TR",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}	
//FIN TR

















//Debut SCT
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 3 GROUP BY code, user_id  ";

$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());

		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
$queryProduct="SELECT  order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
$DataProduct=mysql_fetch_array($rptProduct);	
	
	
if ($DataProduct[order_num] <> '-1' )
{	
	
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
if ($count > 0)
{
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Coupon codes used today: Directlab St-Catharines"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by SCT",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by SCT",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}		
}
//FIN SCT
?>










<?php   
//Debut Directlab Atlantic
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 36  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT  order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
	
		
if ($DataProduct[order_num] <> '-1' )
{		
		
		
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
	mysql_free_result($rptResult);
if ($count > 0)
{	
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Coupon codes used today: Directlab Atlantic"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by DLAB ATLANTIC",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by DLAB ATLANTIC",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Directlab Atlantic
?>




<?php   
//Debut  Directlab Drummondville
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 22  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT  order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
		
if ($DataProduct[order_num] <> '-1' )
{
	
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
	mysql_free_result($rptResult);	
if ($count > 0)
{		
		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Coupon codes used today: Directlab Drummondville"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($send_to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Directlab Drummondville",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Directlab Drummondville",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}
//FIN Directlab Drummondville
?>








<?php   
//Debut Lensclub Atlantic
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 33  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT  order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
			
if ($DataProduct[order_num] <> '-1' )
{	
			
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club Atlantic"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet Atlantic",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet Atlantic",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}
//FIN Lens net Club Atlantic
?>









<?php   
//Debut Lensclub Ontario
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 29  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT  order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
		
if ($DataProduct[order_num] <> '-1' )
{		
		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club Ontario"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet Ontario",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet Ontario",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}
}		
//FIN Lens net Club Ontario
?>





<?php   
//Debut Lensclub Qc
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 28  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
		
if ($DataProduct[order_num] <> '-1' )
{		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
if ($count > 0)
{
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club Qc"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet QC",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet QC",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Lens net Club QC
?>





<?php   
//Debut Lensclub USA
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date ='$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 32  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
		
		
if ($DataProduct[order_num] <> '-1' )
{
		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1	
			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
if ($count > 0)
{
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club USA"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet USA",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet USA",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Lens net Club USA
?>





<?php   
//Debut Lensclub WEST
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 34  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name  FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
		
if ($DataProduct[order_num] <> '-1' )
{
		
		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
					case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: LensnetClub West"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet West",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet West",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}			
}		
?>




<?php   
//Debut Vision optics technologies
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 1  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
					
		
if ($DataProduct[order_num] <> '-1' )
{
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Vision Optic Technologies"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by VOT",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by VOT",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}			
//FIN Vision Optic Technologies
		
		
		
		
		
		
		
		
	

//Debut Directlab France
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 37  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
					
	
if ($DataProduct[order_num] <> '-1' )
{

	
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Directlab France"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Directlab France",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Directlab France",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Direct-lab France
		
		
		
		
		

//Debut Directlab USA
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 41  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
if ($DataProduct[order_num] <> '-1' )
{	
	
	
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1	
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
		//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Directlab USA"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Directlab USA",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Directlab USA",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}							
}		
//FIN Direct-lab USA
		
		
		
		
		
		
		
		
//Debut Directlab Pacific
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 43  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
		
		
if ($DataProduct[order_num] <> '-1' )
{		
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1				  
			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Directlab Pacific"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Directlab Pacific",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Directlab Pacific",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}		
}		
//FIN Direct-lab Pacific		
		
		
		
		
		
		
		
		
//Debut Lensnet CLub Italia
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date= '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 42  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
if ($DataProduct[order_num] <> '-1' )
{
		
		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club Italia"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet Italia",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet Italia",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Lensnet CLub Italia	
	
		
		
//Debut Lensnet CLub Pacific
$rptQuery="SELECT coupon_use.*, accounts.main_lab, count( order_id ) AS NbrOrder from coupon_use, accounts 
WHERE coupon_use.use_date = '$today'
AND  coupon_use.user_id = accounts.user_id 
AND accounts.main_lab = 44  GROUP BY code, user_id";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	
		$count=0;
		$message="";
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">User id</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Order #</td>
                <td align=\"center\">Code</td>
				<td align=\"center\">Has been used</td>
                <td align=\"center\">Amount</td>
                <td align=\"center\">Date</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryProduct="SELECT order_num,order_product_name FROM orders WHERE primary_key    = '$listItem[order_id]'  ";
		$rptProduct=mysql_query($queryProduct)		or die  ('I cannot select items because: ' . mysql_error());	
		$DataProduct=mysql_fetch_array($rptProduct);	
			
if ($DataProduct[order_num] <> '-1' )
{

		
		
		
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "In Production";			break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Re-do";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
			$message.="<tr bgcolor=\"$bgcolor\">";
             
               $message.="
                <td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$DataProduct[order_product_name]</td>
				 <td align=\"center\">$DataProduct[order_num]</td>
                <td align=\"center\">$listItem[code]</td>
				<td align=\"center\">$listItem[NbrOrder]</td>
                <td align=\"center\">$listItem[amount]</td>
               <td align=\"center\">$listItem[use_date]</td>";
              $message.="</tr>";
			  
			  
}//END IF ORDER NUM <> -1			  
			  
		}//END WHILE
		mysql_free_result($rptResult);
		
if ($count > 0)
{
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Coupon codes used today: Lensnet Club Pacific"." : ".$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	$compteur = 0;
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}
	
	if($response){ 
		log_email("REPORT: Coupon used by Lensnet Pacific",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Coupon used by Lensnet Pacific",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		
//FIN Lens net CLub Pacific
		

			
		
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time) VALUES('Coupon Used Report by email', '$time','$today','$timeplus3heures') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error() );


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>