<?php 
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));

//Search errors of the day   
		   $rptQuery="SELECT * FROM orders  
		   WHERE  order_status = 'tba'
		   ORDER  BY order_date_processed, user_id"; 
		   

if ($_REQUEST[status_update] <> ''){
	//echo '<br>ID A EFFACER: ' . $_REQUEST[status_update];
	
	if (strlen($_REQUEST[status_update]) == 7){
	$queryDelete  = "UPDATE orders SET order_status = 'processing' WHERE order_num = $_REQUEST[status_update]";
	//echo '<br>'. $queryDelete;
	$resultDelete = mysql_query($queryDelete) or die  ('I cannot delete  items because: ' . mysql_error());
	}//End if Order num = length 7
	
	//echo '<br>Longeur:'. strlen($_REQUEST[status_update]);
	//Rediriger à la date ou la commande a été effacée
	/*if  ($DataDetail[date]<>''){
		header("Location: tba_today.php?ladate=".$DataDetail[date]);
		exit();	
	}*/
}//End IF There is an ID to delete

?>
<html>
<head>
<title>Commandes en attente d'approbation</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="55%">
<tr valign="top">
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="100%">
<form  method="post" name="Who_product_redirected1" id="Who_product_redirected1" action="tba_today.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Commandes en attente d\'approbation'; ?></font></b></td>
            		</tr>

				<tr align="center" bgcolor="#DDDDDD">
					<td nowrap="nowrap">
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Recharger la liste'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
				</tr>
		
</form>
</table>


<?php 		
	$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";


if (($usercount != 0)){//some products were found
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
	echo "<tr>
			<th width=\"9%\"  align=\"center\">Date</th>
			<th width=\"9%\" align=\"center\">Compte</th>
			<th width=\"6%\" align=\"center\">Order #</th>
			<th width=\"40%\" align=\"left\">Instruction Speciale</th>
			<th width=\"19%\" align=\"center\">Update Status</th>";
	echo "</tr>";

	while ($listItem=mysql_fetch_array($rptResult)){
			echo "<tr>
			<td align=\"center\">".$listItem[order_date_processed]."</td>
			<td align=\"center\">".$listItem[user_id]."</td>
			<td align=\"center\">".$listItem[order_num]."</td>
			<td align=\"left\">".$listItem[special_instructions]."</td>
			<td align=\"center\"><a href=\"tba_today.php?status_update=$listItem[order_num]\">Update Status to Confirmed</a></td>";

			echo "</tr>";
	}//END WHILE
	
	
	
	echo "</form></table>";

}else{
	if ($_POST["rpt_search"] <> '')
	echo "<div class=\"formField\">".'Aucune commande en attente d\'approbation'."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
</body>
</html>