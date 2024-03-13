<?php 
session_start();
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');					
?>    
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
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
    
    
	<div><table width="100%" border="0" cellpadding="2" cellspacing="0">

            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Refused Memo credits</font></b></td>
       		    </tr>
                
                
                
                
                
            <form  method="post" name="goto_date" id="goto_date" action="report.php">
          
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Order Reports</font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td nowrap bgcolor="#DDDDDD" ><div align="right">
						Order Number
					</div></td>
					<td align="left" nowrap="nowrap"><input name="order_num" type="text" id="order_num" size="10" class="formField"></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td width="25%" nowrap ><div align="right">
						Select Order Status
					</div></td>
					<td width="15%" align="left" nowrap="nowrap"><select name="order_status" id="order_status" class="formField">
					  <option value="all">All</option>
					  <option value="cancelled">Cancelled</option>
					  <option value="processing">Confirmed</option>
					  <option value="delay issue 0">Delay Issue 0</option>
					  <option value="delay issue 1">Delay Issue 1</option>
					  <option value="delay issue 2">Delay Issue 2</option>
					  <option value="delay issue 3">Delay Issue 3</option>
					  <option value="delay issue 4">Delay Issue 4</option>
					  <option value="delay issue 5">Delay Issue 5</option>
					  <option value="delay issue 6">Delay Issue 6</option>
					  <option value="on hold">On Hold</option>
					  <option value="information in hand">Info in Hand</option>
					  <option value="in coating">In Coating</option>
					  <option value="in mounting">In Mounting</option>
                      <option value="in edging">In Edging</option>
					  <option value="job started">Surfacing</option>
					  <option value="in transit">In Transit</option>
					  <option value="open">Open</option>
					  <option value="order completed">Order Completed</option>
					  <option value="order imported">Order Imported</option>
					  <option value="re-do">Redo</option>
					  <option value="filled">Shipped</option>
					  <option value="waiting for frame">Waiting for Frame</option>
					  <option value="waiting for lens">Waiting for Lens</option>
					  <option value="waiting for shape">Waiting for Shape</option>
			      </select></td>
					<td width="15%" nowrap="nowrap"><div align="right">
						Select Order Type
					</div></td>
					<td width="40%" align="left" nowrap ><input name="order_type" type="radio" value="stock">
						Stock
						&nbsp;&nbsp;&nbsp;
							<input name="order_type" type="radio" value="exclusive">
						Prescription
						&nbsp;&nbsp;&nbsp;
							<input name="order_type" type="radio" value="all" checked>
						All&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						Date From
					</div></td>
					<td><input name="date_from" type="text" class="formField" id="date_from" value="All" size="11">
					</td>
					<td><div align="center">
						Through
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="All" size="11">
					</td>
					</tr>
				
				<tr bgcolor="#DDDDDD">
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>

</form>    
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
            	<tr bgcolor="#DDDDDD">
            	<td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Order Num</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Lab</b></font></p></td>
            	<td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Compte</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Ref. Pat.</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Pat. Name</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Credit/Debit</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Amount</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Memo Code</b></font></p></td>
                <td align="left" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>OptiPoints</b></font></p></td>
                <td align="center" nowrap><p><font size="2" face="Arial, Helvetica, sans-serif"><b>Date</b></font></p></td>
            	</tr>
                <form  name="memo_credit_approval" id="memo_credit_approval" action="memo_credit_approval.php" method="post">
   <?php
	$QueryMemoCreditTemp="SELECT * FROM memo_credits_temp WHERE mcred_approbation = 'refuse'";
	$ResultMemoCreditTemp=mysql_query($QueryMemoCreditTemp)	or die (  mysql_error() );
	while($DataMemoCreditTemp=mysql_fetch_array($ResultMemoCreditTemp)){
	$count++;
	if (($count%2)==0)
   		$bgcolor="#DDDDDD";
	else 
		$bgcolor="#FFFFFF";
				
	$queryLab="SELECT lab_name, lab_email, primary_key as lab_primary_key FROM labs WHERE primary_key = (SELECT distinct lab  FROM orders WHERE order_num  = $DataMemoCreditTemp[mcred_order_num]) ";
	$ResultLab=mysql_query($queryLab)	or die ( "Query failed 1: " . mysql_error() );				
	$DataLab=mysql_fetch_array($ResultLab);
	mysql_free_result($ResultLab);

	$queryCode="SELECT mc_description FROM memo_codes WHERE mc_lab = $DataLab[lab_primary_key] AND  memo_code = '$DataMemoCreditTemp[mcred_memo_code]' ";
	$ResultCode=mysql_query($queryCode)	or die ( "Query failed 2: " . mysql_error() );				
	$DataCode=mysql_fetch_array($ResultCode);
	mysql_free_result($ResultCode);

					echo "<tr bgcolor=\"$bgcolor\"><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">
					$DataMemoCreditTemp[mcred_order_num]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataLab[lab_name]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_acct_user_id]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[pat_ref_num]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[patient_first_name] $DataMemoCreditTemp[patient_last_name]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_cred_type]</td>
					<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_abs_amount]</td>
					<td  align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataCode[mc_description]</td>
					<td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					if( $DataMemoCreditTemp[optipoints_to_substract]> 0) echo $DataMemoCreditTemp[optipoints_to_substract];
					echo "</td><td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$DataMemoCreditTemp[mcred_date]</td>";
					echo '<tr><td>&nbsp;</td></tr>';
				}
				
				mysql_free_result($ResultMemoCreditTemp);
				?>
				<input type='hidden' name="PostedForm" id="PostedForm" value="yes" ></form></table></div>
</td>
</tr>
</table>
</body>
</html>