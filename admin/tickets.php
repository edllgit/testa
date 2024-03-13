<?php /*?><?php 
require('../constants/aws.constant.php');
include "Connections/directlens.php";
include "includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);
	
require('Connections/sec_connect.inc.php');
include("labAdmin/export_functions_w_prices.inc.php");

$user_id=$_SESSION["sessionUser_Id"];

if ($_POST[from_form_order_num]=="true"){

	$order_num=$_POST[order_num];
	$query="SELECT orders.order_num AS order_no, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, orders.redo_order_num, payments.pmt_amount, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE orders.order_num='$order_num' and orders.user_id='$user_id' GROUP by orders.order_num";
	
$_SESSION["QUERY"]=$query;
}//END IF FROM FORM ORDER NUM

if ($_POST[from_form_patient_ref]=="true"){

	$patient_ref_num=$_POST[patient_ref_num];
	$query="SELECT orders.order_num AS order_no,  orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed,orders.patient_ref_num, order_patient_last, order_patient_first, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, orders.redo_order_num, payments.pmt_amount, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE (orders.patient_ref_num like '$patient_ref_num' OR order_patient_first like '$patient_ref_num' OR order_patient_last like '$patient_ref_num') and orders.user_id='$user_id' GROUP by orders.order_num";
	
$_SESSION["QUERY"]=$query;
}//END IF FROM FORM ORDER NUM


if ($_POST[from_form]=="true"){

	if ($_POST[order_status]=="open"){
		$order_status_string="(order_status!='filled' AND order_status!='cancelled' AND order_status!='basket')";
		}
	else if ($_POST[order_status]=="all"){
		$order_status_string="order_status!='basket'";
		}
	else if ($_POST[order_status]=="filled"){
		$order_status_string="order_status='filled'";
		}
	else if ($_POST[order_status]=="cancelled"){
		$order_status_string="order_status='cancelled'";
	}
	else if ($_POST[order_status]=="all delay issue"){
		$order_status_string="(order_status='delay issue 0' OR order_status='delay issue 1' OR order_status='delay issue 2' OR order_status='delay issue 3' OR order_status='delay issue 4' OR order_status='delay issue 5' OR order_status='delay issue 6')";
	}
	else if ($_POST[order_status]=="processing"){
		$order_status_string="order_status='processing'";
	}
	else if ($_POST[order_status]=="order imported"){
		$order_status_string="order_status='order imported'";
	}
	else if ($_POST[order_status]=="job started"){
		$order_status_string="order_status='job started'";
	}
	else if ($_POST[order_status]=="in coating"){
		$order_status_string="order_status='in coating'";
	}
	else if ($_POST[order_status]=="in mounting"){
		$order_status_string="order_status='in mounting'";
	}
	else if ($_POST[order_status]=="order completed"){
		$order_status_string="order_status='order completed'";
	}
	else if ($_POST[order_status]=="delay issue 0"){
		$order_status_string="order_status='delay issue 0'";
	}
	else if ($_POST[order_status]=="delay issue 1"){
		$order_status_string="order_status='delay issue 1'";
	}
	else if ($_POST[order_status]=="delay issue 2"){
		$order_status_string="order_status='delay issue 2'";
	}
	else if ($_POST[order_status]=="delay issue 3"){
		$order_status_string="order_status='delay issue 3'";
	}
	else if ($_POST[order_status]=="delay issue 4"){
		$order_status_string="order_status='delay issue 4'";
	}
	else if ($_POST[order_status]=="delay issue 5"){
		$order_status_string="order_status='delay issue 5'";
	}
	else if ($_POST[order_status]=="delay issue 6"){
		$order_status_string="order_status='delay issue 6'";
	}
	else if ($_POST[order_status]=="waiting for frame"){
		$order_status_string="order_status='waiting for frame'";
	}
	else if ($_POST[order_status]=="waiting for lens"){
		$order_status_string="order_status='waiting for lens'";
	}
	else if ($_POST[order_status]=="waiting for shape"){
		$order_status_string="order_status='waiting for shape'";
	}
	else if ($_POST[order_status]=="re-do"){
		$order_status_string="order_status='re-do'";
	}
	else if ($_POST[order_status]=="in transit"){
		$order_status_string="order_status='in transit'";
	}
	else if ($_POST[order_status]=="information in hand"){
		$order_status_string="order_status='information in hand'";
	}
	
	
	if ($_POST[order_type]=="stock"){
		$order_type_string="(order_product_type='stock' OR order_product_type='stock_tray')";
	}
	else if ($_POST[order_type]=="exclusive"){
		$order_type_string="order_product_type='exclusive'";
	}
	else if ($_POST[order_type]=="any"){
		$order_type_string="(order_product_type='exclusive' OR order_product_type='stock' OR order_product_type='stock_tray')";
	}

if ($_POST[filter]=="none"){
	$query="SELECT orders.order_num AS order_no,  orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, orders.redo_order_num, payments.pmt_amount, est_ship_date.est_ship_date FROM orders 
	LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
	LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	WHERE ".$order_status_string." AND ".$order_type_string." AND orders.user_id='$user_id' GROUP by orders.order_num";
	}
else if ($_POST[filter]=="date"){
		
		$date=array();
		$date2=array();
		
		$date= explode("/", $_POST[date_from]);
		$start_date=$date[2]."-".$date[0]."-".$date[1];

		$date2= explode("/", $_POST[date_to]);
		$end_date=$date2[2]."-".$date2[0]."-".$date2[1];
		
			$query="SELECT orders.order_num AS order_no, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, orders.redo_order_num, payments.pmt_amount, est_ship_date.est_ship_date FROM orders 
			LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
			LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
			WHERE ".$order_status_string." and ".$order_type_string." and (orders.order_date_processed between '$start_date' and '$end_date') and orders.user_id='$user_id' GROUP by orders.order_num";
	}

$_SESSION["QUERY"]=$query;

}//END IF FROM FORM

if ($_GET["q"]!=""){// IF COLUMN SORT CLICKED ON
	$query=$_SESSION["QUERY"]." ".$_GET["q"];
}
else if (($_SESSION["QUERY"]!="")&&($_GET["q"]=="")) {//IF SEARCH FORM USED
	$query=$_SESSION["QUERY"]." desc";
}

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens &mdash; My Order History</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css">

<script src="formFunctions.js" type="text/javascript"></script>

<SCRIPT LANGUAGE="JavaScript" SRC="includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/date_validation.js"></SCRIPT>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
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
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn"><?php 
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?></div></td>
    <td width="685" valign="top"><form  method="post" name="goto_date" id="goto_date" action="order_history.php">
	
      <div class="header"><?php echo $lbl_titlemast_hist;?>  </div><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
            <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
            	<tr bgcolor="#000000">
            		<td colspan="4" align="right" bgcolor="#000099" class="formCell">&nbsp;</td>
            		</tr>

				<tr bgcolor="#DDDDDD">
				  <td colspan="4" align="center" nowrap bgcolor="#FFFFFF" class="formCellNoleft" ><div align="center">
				    <select name="order_status" class="formText" id="order_status" ><option value="open"><?php echo $lbl_slct_ordrstatus_txt;?></option>
                      <option value="all"  <?php if ($_POST[order_status]=="all") print "selected";?>>All</option>
                      <option value="open" <?php if ($_POST[order_status]=="open") print "selected";?>>Open</option>
                      <option value="filled" <?php if ($_POST[order_status]=="filled") print "selected";?>>Past</option>
                      <option value="all delay issue" <?php if ($_POST[order_status]=="all delay issue") print "selected";?>>All Delay Issue</option>
                      <option value="" disabled="disabled">–</option>
                      
							<option value="processing" <?php if ($_POST[order_status]=="processing") print "selected";?>>Confirmed</option>
							<option value="order imported" <?php if ($_POST[order_status]=="order imported") print "selected";?>>Order Imported</option>
							<option value="job started" <?php if ($_POST[order_status]=="job started") print "selected";?>>In Production</option>
							<option value="information in hand" <?php if ($_POST[order_status]=="information in hand") print "selected";?>>Information in Hand</option>
							<option value="in coating" <?php if ($_POST[order_status]=="in coating") print "selected";?>>In Coating</option>
							<option value="in mounting" <?php if ($_POST[order_status]=="in mounting") print "selected";?>>In Mounting</option>
							<option value="order completed" <?php if ($_POST[order_status]=="order completed") print "selected";?>>Order Completed</option>
							<option value="delay issue 0" <?php if ($_POST[order_status]=="delay issue 0") print "selected";?>>Delay Issue 0</option>
							<option value="delay issue 1" <?php if ($_POST[order_status]=="delay issue 1") print "selected";?>>Delay Issue 1</option>
							<option value="delay issue 2" <?php if ($_POST[order_status]=="delay issue 2") print "selected";?>>Delay Issue 2</option>
							<option value="delay issue 3" <?php if ($_POST[order_status]=="delay issue 3") print "selected";?>>Delay Issue 3</option>
							<option value="delay issue 4" <?php if ($_POST[order_status]=="delay issue 4") print "selected";?>>Delay Issue 4</option>
							<option value="delay issue 5" <?php if ($_POST[order_status]=="delay issue 5") print "selected";?>>Delay Issue 5</option>
							<option value="delay issue 6" <?php if ($_POST[order_status]=="delay issue 6") print "selected";?>>Delay Issue 6</option>
							<option value="waiting for frame" <?php if ($_POST[order_status]=="waiting for frame") print "selected";?>>Waiting for Frame</option>
							<option value="waiting for shape" <?php if ($_POST[order_status]=="waiting for shape") print "selected";?>>Waiting for Shape</option>
							<option value="waiting for lens" <?php if ($_POST[order_status]=="waiting for lens") print "selected";?>>Waiting for Lens</option>
							<option value="re-do" <?php if ($_POST[order_status]=="re-do") print "selected";?>>Re-do</option>
							<option value="in transit" <?php if ($_POST[order_status]=="in transit") print "selected";?>>In Transit</option>
							<option value="filled" <?php if ($_POST[order_status]=="filled") print "selected";?>>Shipped</option>
							<option value="cancelled" <?php if ($_POST[order_status]=="Cancelled") print "selected";?>>Cancelled</option>
                                                                            </select>
				    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;				    <select name="order_type" class="formText" id="order_type" >
				      <option value="any" ><?php echo $lbl_slct_ordrtype_txt;?></option>
				      <option value="stock" <?php if ($_POST[order_type]=="stock") print "selected";?>>Stock Orders</option>
				      <option value="exclusive" <?php if ($_POST[order_type]=="exclusive") print "selected";?>>Prescription</option>
				      <option value="any" <?php if ($_POST[order_type]=="any") print "selected";?>>Any</option></select></div></td>
				  </tr>
				<tr bgcolor="#DDDDDD">
				  <td nowrap bgcolor="#FFFFFF" class="formCellNosides" ><div align="right"> <?php echo $lbl_fltrby_txt;?> </div></td>
				  <td width="50%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="filter" type="radio" value="date" <?php if ($_POST[filter]=="date") print "checked";?>>
				    <?php echo $lbl_fltrby1;?>
				    <input name="filter" type="radio" value="none" <?php if ($_POST[filter]!="date") print "checked";?>>
				    <?php echo $lbl_fltrby2;?> </td>
				  <td width="30%" colspan="2" align="left" bgcolor="#FFFFFF" class="formCellNoleft">&nbsp;</td>
				  </tr>
				<tr bgcolor="#DDDDDD">
				  <td width="20%" nowrap bgcolor="#FFFFFF" class="formCellNosides" ><div align="right"> <?php echo $lbl_datefrom_spr;?> </div></td>
				  <td align="left" bgcolor="#FFFFFF" class="formCellNoleft"><input name="date_from" type="text" class="formText" id="date_from" value=<?php $today=getdate(time()); 
		
			if ($_POST[date_from]!="")
				print "\"".$_POST[date_from]."\"";
			else
				print "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; 
				?> size="11">
						<a href="#" onclick="cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;" title="Popup calendar for quick date selection" name="anchor1xx" id="anchor1xx"><img src="includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle" /></a>&nbsp;
						  <?php echo $lbl_through_oh;?>&nbsp;
						  <input name="date_to" type="text" class="formText" id="date_to" value=<?php $today=getdate(time()); 
		
			if ($_POST[date_to]!="")
				print "\"".$_POST[date_to]."\"";
			else
				print "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; 
				?> size="11">
						<A HREF="#" onClick="cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor2xx" ID="anchor2xx"><img src="includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A></td>
				  <td colspan="2" align="right" bgcolor="#FFFFFF" class="formCellNoleft"><input name="rpt_search" type="submit" id="rpt_search" value="<?php echo $btn_srch_st_type;?>" class="formText">
				    <input name="from_form" type="hidden" id="from_form" value="true"></td>
				  </tr>
			</table>
</form>
<form  method="post" name="search_form" id="search_form" action="order_history.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						<?php echo $lbl_ordrnum_txt_oh;?></div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="order_num" type="text" id="order_num" size="12" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="<?php echo $btn_ordrnum_oh;?>" class="formText">
				
					  <input name="from_form_order_num" type="hidden" id="from_form_order_num" value="true"></td>
				</tr>
			</table>
</form><form  method="post" name="search_form" id="search_form" action="order_history.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						<?php echo $lbl_patientrefnum_txt;?>
					</div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="patient_ref_num" type="text" id="patient_ref_num" size="12" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="<?php echo $btn_srchpatrefnum;?>" class="formText">
				
					  <input name="from_form_patient_ref" type="hidden" id="from_form_patient_ref" value="true"></td>
				</tr>
			</table>
</form>
			
			<?php 
			
			if ($query!=""){
				$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			}
			
			
if ($usercount != 0){
	$query="";
	switch ($_POST[order_type]) {
		case "stock":
    		$order_type=$lbl_stockorders_txt;
    		break;
		case "exclusive":
   			$order_type=$lbl_presorders_txt;
   			 break;
		case "any":
   			$order_type=$lbl_allorders_txt;
    		break;
			 }

if ($_POST[from_form]=="true"){
	$message=$order_type." (".$_POST[order_status].") - ".$usercount;
	}
else if ($_POST[from_form_order_num]=="true"){
	$message=$lbl_ordrnum_txt_oh." - ".$_POST[order_num];
	}
	else if ($_POST[from_form_patient_ref]=="true"){
	$message=$lbl_patrefnum_txt_oh." - ".$_POST[patient_ref_num];
	}
else if ($_GET["q"]!=""){
	$message=$lbl_custsort_txt;
}
else{$message="&nbsp;";}

$downloadFilePath="tempDownloadFiles/".$_SESSION["sessionUser_Id"]."-data_export.csv";//OPEN EXPORT FILE FOR WRITING

$fp=fopen($downloadFilePath, "w");	
$headerstring=get_header_string();
	fwrite($fp,$headerstring);

print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\"><tr >
                <td colspan=\"6\" bgcolor=\"#000098\" class=\"tableHead\">".$message."</td><td colspan=\"2\" bgcolor=\"#000098\" ><div  class=\"tableHead\" ><a href=\"".$downloadFilePath."\" align=\"right\">".$lbl_dwnlddata_txt."</a></div></td>";
  print "</tr>
              <tr>
                <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= desc\">".$lbl_ordernum_txt_oh."</a></td>
				<td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by patient_ref_num\">".$lbl_patrefnum_txt_oh."</a></td>
                <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by order_patient_last\">".$lbl_patlastname_txt_oh."</a></td>
                <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by order_date_processed desc\">".$lbl_orderdate_txt_oh."</a></td>
                <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by est_ship_date desc,order_date_shipped desc\">".$lbl_shipdate_txt_oh."</a></td>
                <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by order_status\">".$lbl_orderstatus_txt_oh."</a></td>";
               
			   if ($_SESSION['account_type']!='restricted')
				{
			   print " <td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by order_total desc\">".$lbl_total_txt_oh."</a></td>";
			   }
			  print "<td align=\"center\" class=\"formCell\"><a href=\"order_history.php?q= ORDER by pmt_amount\">".$lbl_pmtstatus_txt_oh."</a></td>
              </tr>";
			  
while ($listItem=mysql_fetch_array($result)){

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$formated_date=mysql_result($new_result,0,0);
			
			if ($listItem[order_date_shipped]!="0000-00-00"){
				$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
				$ship_date=mysql_result($new_result,0,0);}
			else {
				if (($listItem[est_ship_date]!="0000-00-00")&&($listItem[est_ship_date]!=NULL)){
					$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[est_ship_date]','%m-%d-%Y')");
					$ship_date="<b>".$lbl_est_txt."</b> ".mysql_result($new_result,0,0);}
				else{ $ship_date="<b>".$lbl_est_txt."</b> ".$lbl_tbd_txt;}
				}
		print  "<tr>
                <td align=\"center\"  class=\"formCell\"><a href=\"display_order.php?order_num=$listItem[order_no]&po_num=$listItem[po_num]&pmt_status=$listItem[pmt_amount]&redo=$listItem[redo_order_num]\">$listItem[order_no]";
				
				if ($listItem['redo_order_num']!=0) print "<b>R</b></a>";
				else print "</a>&nbsp;&nbsp;";
				
				print"</td>
				<td align=\"center\"  class=\"formCell\">$listItem[patient_ref_num]</td>
				<td align=\"center\"  class=\"formCell\">$listItem[order_patient_last]</td>
                <td align=\"center\"  class=\"formCell\">$formated_date</td>
				<td align=\"center\"  class=\"formCell\">$ship_date</td>
                <td align=\"center\" class=\"formCell\">";
			
				switch($listItem[order_status])
					{
						case 'processing':				print "Confirmed";					break;
						case 'order imported':			print "Order Imported";				break;
						case 'job started':				print "In Production";				break;
						case 'in coating':				print "In Coating";					break;
						case 'in mounting':				print "In Mounting";				break;
						case 'order completed':			print "Order Completed";			break;
						case 'delay issue 0':			print "Delay Issue 0";				break;
						case 'delay issue 1':			print "Delay Issue 1";				break;
						case 'delay issue 2':			print "Delay Issue 2";				break;
						case 'delay issue 3':			print "Delay Issue 3";				break;
						case 'delay issue 4':			print "Delay Issue 4";				break;
						case 'delay issue 5':			print "Delay Issue 5";				break;
						case 'delay issue 6':			print "Delay Issue 6";				break;
						case 'waiting for frame':		print "Waiting for Frame";			break;
						case 'waiting for lens':		print "Waiting for Lens";			break;
						case 'waiting for shape':		print "Waiting for Shape";			break;
						case 're-do':					print "Re-do";						break;
						case 'in transit':				print "In Transit";					break;
						case 'filled':					print "Shipped";					break;
						case 'information in hand':		print "Information in Hand";		break;
						case 'cancelled':				print "Cancelled";					break;
					}
				
				print "</td>";
				
				//print "<td align=\"center\" class=\"formCell\">";
				//switch($listItem[order_product_type])
					//{
						//case 'stock':			print "Stock";					break;
						//case 'exclusive':		print "Prescription";			break;
						//case 'stock_tray':		print "Stock";					break;
					//}
				//print"</td>";
				if ($_SESSION['account_type']!='restricted')
				{
				print "<td align=\"right\" class=\"formCell\">";
				$orderTotal=money_format('%.2n',$listItem["order_total"]);
				print "$".$orderTotal;
				print "</td>";
				}

                if($listItem[pmt_amount]==0)
					print "<td align=\"center\" class=\"formCell\">".$lbl_open_txt."</td>";
				else
					print "<td align=\"center\" class=\"formCell\">".$lbl_paid_txt."</td>";
              print "</tr>";
			  
		$outputstring=export_order($listItem[order_no]);//APPEND ORDE TO EXPORT FILE
		fwrite($fp, $outputstring);
		
		}//END WHILE
		print "</table><br>";

	fclose($fp);//CLOSE EXPORT FILE
}
else if ($query!=""){
	$query="";
		print "<div class=\"Subheader\">".$lbl_noordersfound_txt."</div>";}//END USERCOUNT CONDITIONAL
?></td>
  </tr>
</table>
		 </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFFFFF;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFFFFF;layer-background-color:white;"></DIV>
</body>
</html>
<?php */?>