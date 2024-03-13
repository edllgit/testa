 <form action="fullaccountlist.php" method="POST">
 
 <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT LENS  EXCLUSIVE ACCOUNTS LIST </font>  </b></td>
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
              
               <tr><td align="center"><br /><a style="text-decoration:none;" href="fullaccountlist.php?lab=sct">Directlab St-catharines</a>&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=tr">Trois-Rivieres</a> &nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=vot">VOT</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=dr">Directlab Drummondville</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=atlantic">Directlab Atlantic</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=france">Directlab France</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=italia">Directlab Italia</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=dlabusa">Directlab USA</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=dlabpacific">Directlab Pacific</a>

<br>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetqc">Lens Net QC</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensneton">Lens Net ON</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetor">Lens Net Elite</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetusa">Lens Net USA</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetatlantic">Lens Net Atlantic</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetwest">Lens Net West</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=lensnetusa">Lens Net USA</a>

&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=netitalia">Lens Net Italia</a>
&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=netpacific">Lens Net Pacific</a>

&nbsp;&nbsp;&nbsp;<a style="text-decoration:none;" href="fullaccountlist.php?lab=afrique">Lens Net Afrique de l'Ouest</a><br />
<br />
</td></tr>
              
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
