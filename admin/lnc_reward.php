<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
//if ($_SESSION[labAdminData][username]==""){
//	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
//	exit();
//}
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


if ($_POST[amount] <> ''){
			//verifier s il le montant est positif, si oui on insert.
			if ($_POST[amount] > 0) 
			{
			$detail = mysql_real_escape_string($_POST[detail]);
			$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, amount, datetime,user_id) VALUES ('$_SESSION[access_admin_id]','$detail','$_POST[amount]', '$datecomplete', '$Data[user_id]')" ;
			$resultinsert=mysql_query($queryInsert)		or die (mysql_error());
			$nouveauTotal = $_POST[amount] + $Data[lnc_reward_points];
			$queryUpdate = "UPDATE accounts  SET   lnc_reward_points = '$nouveauTotal' WHERE primary_key = '$pkey'";
			$resultUpdate=mysql_query($queryUpdate)		or die (mysql_error());
					}else {
							//vérifier si le montant ne dépasse pas le solde du compte
						
							
							$nouveauTotal = $Data[lnc_reward_points] + $_POST[amount];
							
							if ($nouveauTotal >= 0)
							{
							$detail = mysql_real_escape_string($_POST[detail]);
							$queryInsert = "INSERT INTO lnc_reward_history (access_id, detail, amount, datetime,user_id) VALUES ('$_SESSION[access_admin_id]','$detail','$_POST[amount]', '$datecomplete', '$Data[user_id]')" ;
							$resultInsert=mysql_query($queryInsert)		or die (mysql_error());
									
							$queryUpdate = "UPDATE accounts SET   lnc_reward_points = '$nouveauTotal' WHERE primary_key = '$pkey'";
							$resultUpdate=mysql_query($queryUpdate)		or die (mysql_error());
							}else{
							echo '<p style="border:10 px dashed;"  align="center"><font color="#FF0000">UNABLE TO SUSBTRACT AN AMOUNT LARGER THAN THE CURRENT AVAILABLE QUANTITY OF POINTS ('. $Data[lnc_reward_points]. ' POINTS AVAILABLE)</font></p>';
							}
		
			}	
}

?>

<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("amount", "detail");
	// field description to appear in the dialog box
	var fieldDescription = Array("Amount", "Detail");
	// dialog message
	var alertMsg = "Please fill in the :\n";
	
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

	if (alertMsg.length != l_Msg){
		alert(alertMsg);
		return false;
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
	<form action="lnc_reward.php" method="post" name="form1" id="form1">
			<?php echo 'SELECT AN ACCOUNT TO SEE THE POINT BALANCE';?><br />
			<select  name="acctName" id="acctName" class="formField">
				<option value=""><?php echo $adm_selectaccount_txt;?></option>
				<?php
	$query="select primary_key,user_id, company, last_name, first_name, lnc_reward_points from accounts where approved='approved' and product_line IN ('lensnetclub','aitlensclub','mybbgclub','directlens') order by company";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		print "<option value=\"$accountList[primary_key]\"> $accountList[company],  $accountList[lnc_reward_points] Pts,  $accountList[first_name] $accountList[last_name]:  <b>$accountList[user_id]</b></option>";
//print "<option value=\"$accountList[primary_key]\"> $accountList[lnc_reward_points] Pts,     $accountList[user_id]</option>";

}
?>
			</select>
			<input type="submit" name="Submit" value="<?php echo $btn_go_txt;?>" class="formField" />
			<br />
			
		</form>
 <?php if ($pkey <> ''){  ?>       
<div  style="border:1px solid black;" style="width:350px;" >
<br><br>
<b>Selected account:</b>
<b><?php echo $Data[company];?></b><br>
Login: <b><?php echo $Data[user_id];?></b>

<?php
$pkey = $_POST[acctName];
$query="select  lnc_reward_points, user_id, company from accounts
WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);
?>
<p>Numbers of points in this customer account: <b><?php echo $Data[lnc_reward_points];?> Point<?php if($Data[lnc_reward_points] > 0) echo 's';  ?></b></p>
</div>
 

<br>Detail of the activity in the account:
     
     <?php
$HistoryQuery="select * from lnc_reward_history WHERE user_id= '" . $Data[user_id] . "' ORDER BY lnc_reward_id"; 
$HistoryResult=mysql_query($HistoryQuery)	or die  ('I cannot select items because: ' . mysql_error());
$nbrResult = mysql_num_rows($HistoryResult);

if ($nbrResult > 0) {
?>
       
       
     
            <table width="70%" border="3" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Who</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Amount</font></b></td>
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Date</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Detail</font></b></td>
                    <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Edit Detail</font></b></td>
				</tr>
			<tr><td>
			

			<?php 
			
			while ($HistoryData=mysql_fetch_array($HistoryResult)){
			$num_rows = mysql_num_rows($HistoryResult);
			
		$QueryAccess="select * from access_admin WHERE id=" . $HistoryData['access_id'] ; 
		$ResultAccess=mysql_query($QueryAccess)	or die  ('I cannot select items because: ' . mysql_error());
		$DataAccess=mysql_fetch_array($ResultAccess);
		
			echo '<tr><td align="center">';
			if ($DataAccess[name] <> ''){
			echo 'Admin: ' .$DataAccess[name];
			}else{
			echo  'Customer';
			}
			echo  '</td>';
			echo '<td align="center">';
			echo $HistoryData['amount'];
			echo  '</td>';
			
			echo '<td align="center">';
		 	echo  substr($HistoryData['datetime'],0,10);
			echo  '</td>';
			
		    echo   '<td align="center">';
		    echo $HistoryData['detail']. ' ';
		    echo  '</td>';
			
			echo   '<td align="center"><a  href="edit_reward.php?id='. $HistoryData['lnc_reward_id']. '">Edit</a>';
		    echo  '</td>';
		  
		   echo '<tr>';
			}//End WHILE
			 ?>
			
			
            
          </td> </table> 
           
           
           <?php  
		   }else {
		   echo '<b>No activity in this account yet</b>';
		   } 
		    $queryID = "Select * from access where id='" . $_SESSION["accessid"] . "'";
			$resultID=mysql_query($queryID)	or die  ('I cannot select items because: ' . mysql_error());
			$DataID=mysql_fetch_array($resultID);
		
		   ?>
            <br><br><br>Add activity to the account:
            
            <form action="lnc_reward.php" method="post" name="update_reward" id="update_reward" onSubmit="formCheck(this)" >
			<input type="hidden" name="acctName" value="<?php echo $_POST[acctName];?>" class="formField" />
            <input type="hidden" name="access_id" value="<?php echo $DataID[id]?>" class="formField" />
			<table>
            <tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Who</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Amount</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Detail</font></b></td>
			</tr>
            
            <tr bgcolor="#FFFFFF">
            		<td align="center"><b><?php echo 'Admin';?></b></td>
					<td align="center">  <input type="text" name="amount" size="8" value="" class="formField" /></td>
					<td colspan="2" align="center"><input size="40" type="text" name="detail" value="" class="formField" /></td>
                    <td><input type="submit" name="Submit"  value="<?php echo 'Apply';?>" class="formField" /></td>
			</tr>
            
           
			<br />
			</form>
            
            
            
   <?php } ?>            
            
            
			
			</td></tr>
		
		
</table>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
