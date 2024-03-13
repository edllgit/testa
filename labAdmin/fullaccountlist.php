<?php require_once('../Connections/directlens.php'); ?>
<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_GET[sort_by]!=""){
		$query="select * from accounts order by ".$_GET[sort_by];
		$_GET[sort_by]="";
		}
	else{
		$query="select accounts.*, labs.lab_name from accounts, labs where user_id NOT IN ('grmstock','grm64364','grm64362','rgiguere') AND accounts.main_lab = labs.primary_key  ";
		}
			
	if ($_REQUEST['accounts'] != "") {

	switch($_REQUEST['accounts']){
		
	case 'approved':
	$query = $query . " and accounts.approved='approved'";
	break;
	
	case 'pending':
	$query = $query . " and accounts.approved='pending'";
	break;
	}
}




if ($_REQUEST['lab'] != "") {


$query = $query . " and accounts.main_lab=".  $_REQUEST['lab'] ;
		
}

$query = $query . " order by company ";

$catResult=mysql_query($query)	or die ( "Query failed: " . mysql_error());
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
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="100%">
		
		
		 <form action="fullaccountlist.php" method="POST">
 
 <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT LENS  ACCOUNTS LIST </font>  </b></td>
       		  </tr>
              
              <tr><td align="center"><a style="text-decoration:none;" href="fullaccountlist.php?accounts=all">ALL</a>&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?accounts=approved">APPROVED</a> &nbsp;&nbsp;&nbsp; <a style="text-decoration:none;" href="fullaccountlist.php?accounts=pending">PENDING</a></td></tr>
              
              
              
             <tr> 
             <td>
              <p align="center">
<select name="lab" id="lab" class="formField">
	<option value="">Select a lab to see the access related to this lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; 
		 echo ">$labList[lab_name]</option>";
}?>

 </select>
 <input type="submit" name="filter" id="filter" value="Filter" class="formField">
  </p>    
              </td>
              
              </tr>
              
           
              
              </table><div id="displayBox">
            	 <table width="100%" border="0" cellpadding="2" cellspacing="0"><tr bgcolor="#DDDDDD">

           		  <td nowrap>Account #</font></td>
            		<td nowrap><font size="1" face="Arial, Helvetica, sans-serif">First Name</font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif">Last Name</font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif">Company</font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif">City</font></td>
           		  <td><font size="1" face="Arial, Helvetica, sans-serif">Phone</font></td>
            		
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Email</font></td>
                    
                    <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Sales Rep.</font></td>
                    
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">User_id</font></td>	
                    
            
            		<td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Approved</font></td>	
                    
                    <td bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Main Lab</font></td>
            		
            	</tr>	
            	<?php
				while($catData=mysql_fetch_array($catResult)){
					$count++;
					
					
					if ($catData[sales_rep] <> 0)
					{
					$queryRep= "SELECT * from sales_reps WHERE id = " .  $catData[sales_rep];
					$ResultRep=mysql_query($queryRep)	or die ( "Query failed: " . mysql_error());
					$dataRep=mysql_fetch_array($ResultRep);
					}else{
					$dataRep[rep_name] = '-';
					}
					
					
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					 echo "<tr bgcolor=\"$bgcolor\">
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[account_num]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[first_name]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[last_name]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[company]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[bill_city]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[phone]</td>
					 
				
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[email]</td>
					 
					  <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$dataRep[rep_name]</td>
					 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[user_id]</td>";
					 
					echo "<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">" ;
					
					if ($catData[approved] == 'approved') {
					echo 'Yes';}else {
					echo 'No';
					}
					echo "</td>";
					 
					echo " <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData[lab_name]</td>
					 
					 </tr>";
				}?>
				</table></div>
                </form>

		
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>