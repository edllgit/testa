<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $ladate);
			
if($_POST[acctName]){/* primary key posted from side nav accounts form */
$pkey=$_POST[acctName];
}
$pkey = $_POST[acctName];
$query="select  lnc_reward_points, user_id, company from accounts
WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);

if (($_POST[detail] <> '') && (is_numeric($_POST[amount]))){
				//verifier s il le montant est positif, si oui on insert.
				if ($_POST[id] <> '')
				{
				
				$queryAncienData = "Select * from lnc_reward_history where lnc_reward_id=" .  $_POST[id]  ;
				$resultAncienData=mysql_query($queryAncienData)	or die  ('I cannot select items because: ' . mysql_error());
				$DataAncien=mysql_fetch_array($resultAncienData);
				$UserIdClient = $DataAncien[user_id];
				
				
				
				$AncienBonus = $DataAncien[amount];
				$NouveauBonus = $_POST[amount];
				$Difference = $NouveauBonus - $AncienBonus;
				echo '<br>Nouveau: ' . $NouveauBonus ;
				echo '<br>Ancien: ' . $AncienBonus ;
				echo '<br>Difference: ' . $Difference.'<br>' ;
				
				
				$queryCompteClient = "Select lnc_reward_points from accounts where user_id= '" .  $UserIdClient. "'"  ;
				$resultCompteClient=mysql_query($queryCompteClient)	or die  ('I cannot select items because: ' . mysql_error());
				$DataCompteClient=mysql_fetch_array($resultCompteClient);
				$SoldeActuelOptiPoints = $DataCompteClient[lnc_reward_points];
				$NouveauSoldeOptiPts = $SoldeActuelOptiPoints + $Difference;
				
				echo '<br>Solde actuel: ' . $SoldeActuelOptiPoints ;
				echo '<br>Modification a faire: ' . $Difference ;
				echo '<br>Nouveau solde opti pts: ' . $NouveauSoldeOptiPts ;
				
				$detail = mysql_real_escape_string($_POST[detail]);
				$queryInsert = "UPDATE lnc_reward_history SET detail = '$detail',	amount =  '$_POST[amount]' WHERE lnc_reward_id =" .  $_POST[id] ;
				$queryUpdatePts = "UPDATE accounts SET lnc_reward_points = $NouveauSoldeOptiPts WHERE user_id ='" .  $UserIdClient. "'" ;
				echo $queryInsert;
				echo '<br>'. $queryUpdatePts;

				
				
				$resultinsert=mysql_query($queryInsert)		or die (mysql_error());
				$resultUpdatePts=mysql_query($queryUpdatePts)		or die (mysql_error());
				//Redirection vers /admin/lnc_reward.php
				header("Location:lnc_reward.php");
				}
	}

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
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
	
<div  style="border:1px solid black;" style="width:350px;" >

<?php
$pkey = $_POST[acctName];
$query="select  lnc_reward_points, user_id, company from accounts
WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);

		    $queryID = "Select * from access where id='" . $_SESSION["accessid"] . "'";
			$resultID=mysql_query($queryID)	or die  ('I cannot select items because: ' . mysql_error());
			$DataID=mysql_fetch_array($resultID);
			
			$QueryLncHisto = "Select * from lnc_reward_history where lnc_reward_id=" . $_REQUEST["id"] ;
			$resultHistorique=mysql_query($QueryLncHisto)	or die  ('I cannot select items because: ' . mysql_error());
			$DataLncHistorique=mysql_fetch_array($resultHistorique);
		   ?>
            <br>Edit activity: <?php  echo $DataLncHistorique['detail']; ?>
            
            <form action="edit_reward.php" method="post" name="update_reward" id="update_reward" onSubmit="formCheck(this)" >
			<input type="hidden" name="acctName" value="<?php echo $_POST[acctName];?>" class="formField" />
            <input type="hidden" name="id" value="<?php echo $DataLncHistorique[lnc_reward_id]; ?>" class="formField" />
			<table>
            <tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Who</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Amount</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Detail</font></b></td>
			</tr>
            
            <tr bgcolor="#FFFFFF">
            		<td align="center"><b><?php echo 'Admin';?></b></td>
					<td align="center"><input size="5"  maxlength="5" type="text" name="amount" value="<?php echo $DataLncHistorique[amount];?>" class="formField" /></td>
                    <input value="<?php echo $DataLncHistorique[lnc_reward_id]?>" type="hidden" name="id"  class="formField" />
					<td colspan="2" align="center"><input size="150"  maxlength="250" value= "<?php echo $DataLncHistorique[detail]?>" type="text" name="detail" value="" class="formField" /></td>
                    <td><input type="submit" name="Submit"   value="Update" class="formField" /></td>
			</tr>
            
           
			<br />
			</form>
            

			</td></tr>
		
		
</table>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>