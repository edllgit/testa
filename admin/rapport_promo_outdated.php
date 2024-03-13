<?php
session_start();
include("../Connections/sec_connect.inc.php");

$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}

$type=$_GET[category];
$aujourdhui = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datedujour = date("Y-m-d", $aujourdhui);
//$query="select * from coupon_codes where code like '%promo1%' AND code NOT like '%promo15%' and date > '$hier' order by date";
$query="select * from coupon_codes where code like '%promo1%' AND code NOT like '%promo15%' and date < '$datedujour'  order by date";
$catResult=mysql_query($query)	or die ( "Query failed: " . mysql_error() . $query );
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
		
	 <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Rapport Promo Innovative: <b>Coupons expirés</b>&nbsp;&nbsp;<h2><a href="rapport_promo.php">Coupons Valides</a></h2></font></b></td>
       		  </tr>
            </table><div id="displayBox">
            	 <table width="100%" border="0" cellpadding="2" cellspacing="0">
                 <tr bgcolor="#DDDDDD">
                     <td nowrap><font size="1" face="Arial, Helvetica, sans-serif">Code</font></td>
                     <td nowrap><font size="1" face="Arial, Helvetica, sans-serif">Client</font></td>
                     <td><font size="1" face="Arial, Helvetica, sans-serif">Utilisé</font></td>
                     <td><font size="1" face="Arial, Helvetica, sans-serif">Order Num</font></td>
                     <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Montant</font></td>
                     <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Date expiration</font></td>	
                     <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Collection</font></td>
            	</tr>	
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";
						
							$order_num = substr($catData[code],5,7);
						
if (($order_num <> "") && (is_numeric($order_num))){
$queryClient = "SELECT company from accounts where user_id = (SELECT user_id from orders where order_num = $order_num) ";
$resultClient=mysql_query($queryClient)	or die ( "Query failed: " . mysql_error(). $queryClient );
$DataClient=mysql_fetch_array($resultClient);
mysql_free_result($resultClient);
$Company = $DataClient['company'];
}
				



$queryCode = "SELECT count(pimary_key) as resultat from coupon_use where code = '$catData[code]'";
$resultCode =mysql_query($queryCode)	or die ( "Query failed: " . mysql_error(). $queryCode );
$DataCode=mysql_fetch_array($resultCode);
mysql_free_result($resultCode);
$resultat = $DataCode['resultat'];
if ($resultat > 0){
$resultat = "Oui";
}else{
$resultat = "Non";
}


					 print "<tr bgcolor=\"$bgcolor\">
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[code]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$Company</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$resultat</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$order_num</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[amount]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[date]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[collection]</td>
					 </tr>";
				}
				mysql_free_result($catResult);
				?>
				</table></div>
			
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>