<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../Connections/directlens.php";
include "../includes/getlang.php";

$anneeetmois = date("Y-m");//Donne exemple 2010-05
$jour = date("d");//Donne 18
$datecomplete = $anneeetmois  . '-' . $jour ;

 if ($mylang == 'lang_french'){
		$heading="Rapport d'utilisation des M�mos cr�dits";
 }else {
		$heading="Memo credits Usage report";	
 }
	
		
if ($_POST[date_from] != "All" && $_POST[date_to] != "All" ){//select between these dates
	 if ($mylang == 'lang_french'){
			$dateInfo = " pour l'intervalle de dates: " . $_POST[date_from] . " - " . $_POST[date_to];	
	 }else {
			$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];	
	 }	
}	
	
$heading.=$dateInfo;
//$heading=ucwords($heading);

$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>


</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="reports_memo_cred.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
					<?php if ($mylang == 'lang_french'){
					echo 'Rapport d\'utilisation des M�mos cr�dits' ;
					}else {
					echo 'Memo credits Usage report' ;
					}
					?> 
					
					</font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="15%"><div align="right">
				<?php if ($mylang == 'lang_french'){
				echo 'De';
				}else {
				echo 'Date From';
				}
				?>
					</div></td>
					<td width="15%"><input name="date_from" type="text" class="formField" id="date_from" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> size="11">
					</td>
					<td width="15%"><div align="right">
		<?php if ($mylang == 'lang_french'){
		echo 'Jusqu\'au';
		}else {
		echo 'Through';
		}
		?>
						
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> size="11">
					</td>
					
					<td>
			
			<?php if ($mylang == 'lang_french'){
					echo 'Chercher dans un seul compte:';
					}else {
					echo 'Search in a specific account:';
					}
					?> 
			
			
			<select name="account" id="account" class="formField">
				<option value=""><?php echo '';?></option>
				<?php
	mysql_query("SET CHARACTER SET UTF8");
	$query="select primary_key, company, last_name, first_name from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company, last_name";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
			</select>
					</td>
					
					</tr>

				<tr >
					<td align="right" colspan="4"><div align="right">
					 
					<input align="middle" name="rpt_search" type="submit" id="rpt_search" 
					
					<?php if ($mylang == 'lang_french'){
					echo 'value="Rechercher"' ;
					}else {
					echo 'value="search orders"' ;
					}
					?> 
					class="formField">
					</div>
					</td>
				</tr>		
</form>
<?php 
echo "<table width=\"30%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
echo '<tr><td colspan="5">&nbsp;</td></tr><tr><td colspan="5" align="center">'. $heading . '</td></tr>';
echo '<tr><td colspan="5">&nbsp;</td></tr>';

mysql_query("SET CHARACTER SET UTF8");
$queryCode = "SELECT memo_codes.mc_description, memo_codes.memo_code  FROM  memo_codes
WHERE memo_codes.mc_lab = 21  ORDER BY memo_codes.mc_description";


		
$rptCode=mysql_query($queryCode) or die  ('I cannot select items because: ' . mysql_error());

	echo "<tr  align=\"left\"><th  align=\"left\">";
	if ($mylang == 'lang_french'){
	echo  'Raison</th><th  align=\"left\">Montant total</th>';
	}else {
	echo  'Reason</th><th  align=\"left\">Total Amount</th>';
	}
	echo "</tr>";

while ($listCodes=mysql_fetch_array($rptCode))
{

//echo '<br><br><br>';

	mysql_query("SET CHARACTER SET UTF8");
	$querySum = "SELECT memo_credits.*, memo_codes.mc_description, SUM(memo_credits.mcred_abs_amount/2) as lasomme  FROM  accounts,  memo_credits, memo_codes
	WHERE accounts.user_id = memo_credits.mcred_acct_user_id  AND memo_credits.mcred_memo_code ='". $listCodes['memo_code'] . "' AND accounts.terms = 'agree' AND memo_credits.mcred_memo_code = memo_codes.memo_code  AND accounts.approved = 'approved' AND accounts.main_lab =". $_SESSION[lab_pkey];
	
		
	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select between these dates
			$date_from=date("Y-m-d",strtotime($_POST[date_from]));
			$date_to=date("Y-m-d",strtotime($_POST[date_to]));
			$querySum.=" AND memo_credits.mcred_date between '$date_from' and '$date_to'";
		}

	if ($_POST[account] != ''){
		$querySum .= " AND accounts.primary_key = " . $_POST[account] ;
		}
		
		
		$rptSum=mysql_query($querySum) or die  ('I cannot select items because: ' . mysql_error());
	
	$listSum=mysql_fetch_array($rptSum);	
		
		

		if ($listSum['lasomme'] != 0){
		echo "<td align=\"left\">" 		. $listCodes['mc_description'] 	. "</td>" ;
		$laSomme = 	money_format('%.2n',$listSum['lasomme']);
		echo "<td  align=\"left\">" 	. $laSomme   . "$</td></tr>" ;
		}
		

//echo '<br><br>'. $querySum . '<br><br>';
}//End while listCodes
echo "</table>";
?>
</td>
	  </tr>
</table><br><br></table>
  <p>&nbsp;</p>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
