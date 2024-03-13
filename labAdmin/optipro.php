<?php 
require_once(__DIR__.'/../constants/url.constant.php');
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";

session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


//Search by Selected Criterias

if ($_POST[order_num_optipro] <> ''){
	$order_num_optipro = $_POST[order_num_optipro];
	$rptQuery="SELECT * FROM erreurs_optipro 
			   WHERE order_num_optipro = $order_num_optipro 
			   ORDER  BY user_id, order_num_optipro, erreur_id";
} 

			  

   //echo $rptQuery;

?>
<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="100%">
<form  method="post" name="Who_product_redirected1" id="Who_product_redirected1" action="optipro.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Recherche parmis les erreurs d\'importation Optipro'; ?></font></b></td>
            		</tr>

				<tr align="center" bgcolor="#DDDDDD">
					<td nowrap="nowrap">
					Numéro de facture Optipro: &nbsp;&nbsp;<input name="order_num_optipro" type="text" id="order_num_optipro" size="25" class="formField">&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Lancer la recherche'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
				</tr>

			
</form>



	</table>


<?php 		
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}	

if (($usercount != 0) && ($_POST["rpt_search"] <> '')){//some products were found
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
	echo "<tr>
			<th width=\"8%\"  align=\"center\">Date</th>
			<th width=\"8%\" align=\"center\">Compte</th>
			<th width=\"40%\" align=\"center\">Erreur</th>
			<th width=\"5%\" align=\"center\">Order Num Optipro</th>
			<th width=\"20%\" align=\"center\">Produit Demandé</th>
			<th width=\"10%\" align=\"center\">Modifier le produit (Ifc.ca)</th>
			<th width=\"10%\" align=\"center\">Modifier le produit (SAFE)</th>";
	echo "</tr>";

	while ($listItem=mysql_fetch_array($rptResult)){
			echo "<tr>
			<td align=\"center\">".$listItem[date]."</td>
			<td align=\"center\">".$listItem[user_id]."</td>
			<td align=\"center\">".$listItem[detail]."</td>
			<td align=\"center\">".$listItem[order_num_optipro]."</td>
			<td align=\"center\">".$listItem[produit_optipro]."</td>";
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"".constant('DIRECT_LENS_URL')."/admin/update_exclusive_product_ifc.php?pkey=". $listItem[cle_produit]. "\">Modifier Produit IFC"."</td>";
			}else {
			echo '<td>&nbsp;</td>';	
			}
			
			if ($listItem[cle_produit] <> ''){
			echo "<td align=\"center\"><a target=\"_blank\" href=\"".constant('DIRECT_LENS_URL')."/admin/update_exclusive_product_safety.php?pkey=". $listItem[cle_produit]. "\">Modifier Produit SAFE"."</td>";
			}else{
			echo '<td>&nbsp;</td>';		
			}
			
			echo "</tr>";
	}//END WHILE
	
	
	
	echo "</form></table>";

}else{
	if ($_POST["rpt_search"] <> '')
	echo "<div class=\"formField\">".'Aucune erreur identifiée'."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
</body>
</html>