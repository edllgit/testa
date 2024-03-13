<?php 
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


switch($_REQUEST[succ]){
	case '1587456922': $Compte = "Trois-Rivieres"; $Filtre = " AND user_ID in ('entrepotifc','entrepotsafe')";  break;
	case '1741158922': $Compte = "Drummondville";  $Filtre = " AND user_ID in ('entrepotdr','safedr')";         break;
	case '1489491922': $Compte = "Laval";          $Filtre = " AND user_ID in ('laval','lavalsafe')";           break;
	case '1448758922': $Compte = "Terrebonne";     $Filtre = " AND user_ID in ('terrebonne','terrebonnesafe')"; break;
	case '1179993922': $Compte = "Sherbrooke";     $Filtre = " AND user_ID in ('sherbrooke','sherbrookesafe')"; break;
	case '1679748922': $Compte = "Chicoutimi";     $Filtre = " AND user_ID in ('chicoutimi','chicoutimisafe')"; break;
	case '1111879922': $Compte = "Lévis";          $Filtre = " AND user_ID in ('levis','levissafe')"; 		    break;
	case '1111879922': $Compte = "Longueuil";      $Filtre = " AND user_ID in ('longueuil','longueuilsafe')";   break;
	default :          $Filtre = " AND user_ID in ('aucunResultat')"; 		
}

if ($Filtre == " AND user_ID in ('aucunResultat')"){
echo '<br>Code d\'accès invalide.';
exit();	
}




//Search errors of the day   
		   $rptQuery="SELECT * FROM erreurs_optipro 
		   WHERE  1=1 
		   $Filtre
		   AND detail NOT LIKE '%a deja ete importee pour ce client%'
		   ORDER  BY date, user_id
		   LIMIT 0,150"; 
		   
//echo $rptQuery;
?>
<html>
<head>
<title>Erreur d'importation de <?php echo $Compte; ?></title>
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
<form  method="post" name="Who_product_redirected1" id="Who_product_redirected1" action="optipro_edll.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "Erreur d'importation de $Compte";  ?></font></b></td>
            		</tr>

				<tr align="center" bgcolor="#DDDDDD">
					<td nowrap="nowrap">
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
			<th width=\"8%\"  align=\"center\">Date</th>
			<th width=\"8%\" align=\"center\">Compte</th>
			<th width=\"40%\" align=\"center\">Erreur</th>
			<th width=\"5%\" align=\"center\">Order Num Optipro</th>
			<th width=\"10%\" align=\"center\">Commande transférée ?</th>
			<th width=\"20%\" align=\"center\">Produit Demandé</th>";
	echo "</tr>";

	while ($listItem=mysql_fetch_array($rptResult)){
			echo "<tr>
			<td align=\"center\">".$listItem[date]."</td>
			<td align=\"center\">".$listItem[user_id]."</td>
			<td align=\"center\">".$listItem[detail]."</td>";
		
	  	
	  echo "<td align=\"center\">".$listItem[order_num_optipro]."</td>";
			
			
			
			if ($listItem[order_num_optipro] <> ''){
				$queryValiderPasser  = "SELECT * FROM orders WHERE user_id = '$listItem[user_id]' AND order_num_optipro = $listItem[order_num_optipro]";
				$resultValiderPasser = mysql_query($queryValiderPasser) or die  ('I cannot select items because: ' . mysql_error());
				$CountValiderPasser  = mysql_num_rows($resultValiderPasser);
				$DataValiderPasser   = mysql_fetch_array($resultValiderPasser);
				if ($CountValiderPasser > 0){
					$EtatCommande = 'Oui, #' . $DataValiderPasser[order_num];	
				}else{
					$EtatCommande = 'Non';	
				}
			}else{
			$EtatCommande = 'N/D';		
			}//End IF there is an order num optipro
			
			
			echo "<td align=\"center\">".$EtatCommande."</td>";
			echo "<td align=\"center\">".$listItem[produit_optipro]."</td>";
			echo "</tr>";
	}//END WHILE
	
	
	
	echo "</form></table>";

}else{
	echo "<div class=\"formField\">".'Aucune erreur identifiée pour la succursale de '. $Compte ."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
</body>
</html>