<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $ladate);
			
if($_POST[acctPrimaryKey]){/* primary key posted from side nav accounts form */
$pkey=$_POST[acctPrimaryKey];
$query="select  lnc_reward_points, user_id, company from accounts WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);
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
	<form action="lnc_reward.php" method="post" name="form1" id="form1">
			<?php echo 'SELECT AN ACCOUNT TO SEE THE POINT BALANCE';?><br />
			<select  name="acctPrimaryKey" id="acctPrimaryKey" class="formField">
				<option value=""><?php echo $adm_selectaccount_txt;?></option>
				<?php
	$query="select primary_key,user_id, company, last_name, first_name, lnc_reward_points  from accounts where main_lab= " . $_SESSION[lab_pkey]. " and approved='approved' and product_line IN ('aitlensclub','lensnetclub') order by user_id";
	
	$result=mysql_query($query)		or die ($adm_error1_txt);
	
	while ($accountList=mysql_fetch_array($result)){
	echo "<option value=\"$accountList[primary_key]\"> $accountList[lnc_reward_points] Pts,  $accountList[company], $accountList[first_name] $accountList[last_name]:  <b>$accountList[user_id]</b></option>";
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
$query="select  lnc_reward_points, user_id, company from accounts WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);
?>
<p>Numbers of points in this customer account: <b><?php echo $Data[lnc_reward_points];?> Point<?php if($Data[lnc_reward_points] > 1) echo 's';  ?></b></p>
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
				</tr>
			<tr>
            
            <td>
			<?php 
			while ($HistoryData=mysql_fetch_array($HistoryResult)){
			$num_rows = mysql_num_rows($HistoryResult);
			
		$QueryAccess="select * from access WHERE id=" . $HistoryData['access_id'] ; 
		$ResultAccess=mysql_query($QueryAccess)	or die  ('I cannot select items because: ' . mysql_error());
		$DataAccess=mysql_fetch_array($ResultAccess);
		
			echo '<tr><td align="center">';
			if ($DataAccess[name] <> ''){
			echo $DataAccess[name];
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
		
		   } ?>              
</td></tr>
</table>
</td>
</tr>
</table>
<p>&nbsp;</p>
</body>
</html>