<?php 
	//csv file headings
	$fileOutput= chr(34).$_SESSION["heading"].chr(34).chr(13);
	$fileOutput.= chr(34)."Account No".chr(34).chr(44).chr(34)."Company".chr(34).chr(44).chr(34)."Totals".chr(34).chr(13);
	$fileOutput.= chr(44).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(13);
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
	echo "<td colspan=\"18\"><font color=\"white\">$heading</font></td>";
	echo "</tr>";
	echo "<tr>
			<td align=\"center\">Account No</td>
			<td align=\"center\">Company</td>
			<td align=\"center\" colspan=\"2\">Totals</td>";
	echo "</tr>";
	echo "<tr>
			<td align=\"center\" colspan=\"2\">&nbsp;</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>";
	echo "</tr>";
	$amtTotal=0;			  
	$orderTotal=0;

	while ($listItem=mysql_fetch_array($rptResult)){

		if(!isset($currentCompany)){
			$currentCompany=$listItem["company"];
			$currentAcct=$listItem["account_num"];
		}

		if($currentCompany!=$listItem["company"]){//if the company changes, print out the totals, zero out the counters and add the first totals of the new company
			$amtTotalDisplay=money_format('%.2n',$amtTotal);
			echo "<tr>
			<td align=\"center\">$currentAcct</td>
			<td align=\"center\">$currentCompany</td>
			<td align=\"center\">$orderTotal</td>
			<td align=\"center\">$amtTotalDisplay</td>";
			echo "</tr>";
			$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(13);
			$amtTotal=0;			  
			$orderTotal=0;
			$currentCompany=$listItem["company"];
			$currentAcct=$listItem["account_num"];
			$amtTotal=$listItem["order_total"];			  
			$orderTotal++;

		}else{//if it's still the same account, add in the totals
			$amtTotal= bcadd($amtTotal, $listItem["order_total"], 2);			
			$orderTotal++;
		}//END IF NOT CURRENT ACCT
	}//END WHILE
	$amtTotalDisplay=money_format('%.2n',$amtTotal);
	echo "<tr>
	<td align=\"center\">$currentAcct</td>
	<td align=\"center\">$currentCompany</td>
	<td align=\"center\">$orderTotal</td>
	<td align=\"center\">$amtTotalDisplay</td>";
	echo "</tr>";
	$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(13);
	echo "</table>";
?>
