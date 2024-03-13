<?php 
	$fileOutput= chr(34).$_SESSION["heading"].chr(34).chr(13);
	$week_start = $_SESSION["week_start"];
	$sunday_date = $week_start;
	$monday_date=date("Y-m-d", strtotime("$week_start + 1 day"));
	$tuesday_date=date("Y-m-d", strtotime("$week_start + 2 day"));
	$wednesday_date=date("Y-m-d", strtotime("$week_start + 3 day"));
	$thursday_date=date("Y-m-d", strtotime("$week_start + 4 day"));
	$friday_date=date("Y-m-d", strtotime("$week_start + 5 day"));
	$saturday_date=date("Y-m-d", strtotime("$week_start + 6 day"));
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
	echo "<td colspan=\"18\"><font color=\"white\">$heading</font></td>";
	echo "</tr>";
	echo "<tr>
			<td align=\"center\">Account No</td>
			<td align=\"center\">Company</td>
			<td align=\"center\" colspan=\"2\">Sunday ($sunday_date)</td>
			<td align=\"center\" colspan=\"2\">Monday ($monday_date)</td>
			<td align=\"center\" colspan=\"2\">Tuesday ($tuesday_date)</td>
			<td align=\"center\" colspan=\"2\">Wednesday ($wednesday_date)</td>
			<td align=\"center\" colspan=\"2\">Thursday ($thursday_date)</td>
			<td align=\"center\" colspan=\"2\">Friday ($friday_date)</td>
			<td align=\"center\" colspan=\"2\">Saturday ($saturday_date)</td>
			<td align=\"center\" colspan=\"2\">Total</td>";
	echo "</tr>";
	$fileOutput.= chr(34)."Account No".chr(34).chr(44).chr(34)."Company".chr(34).chr(44).chr(34)."Sunday ($sunday_date)".chr(34).chr(44).chr(44).chr(34)."Monday ($monday_date)".chr(34).chr(44).chr(44).chr(34)."Tuesday ($tuesday_date)".chr(34).chr(44).chr(44).chr(34)."Wednesday ($wednesday_date)".chr(34).chr(44).chr(44).chr(34)."Thursday ($thursday_date)".chr(34).chr(44).chr(44).chr(34)."Friday ($friday_date)".chr(34).chr(44).chr(44).chr(34)."Saturday ($saturday_date)".chr(34).chr(44).chr(44).chr(34)."Total".chr(34).chr(13);
	$fileOutput.= chr(44).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(13);
	echo "<tr>
			<td align=\"center\" colspan=\"2\">&nbsp;</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>
			<td align=\"center\">Orders</td>
			<td align=\"center\">Amount</td>";
	echo "</tr>";
	$amtTotal=0;			  
	$orderTotal=0;
	$SunOrderTotal=0;
	$SunAmtTotal=0;			  
	$MonOrderTotal=0;
	$MonAmtTotal=0;			  
	$TueOrderTotal=0;
	$TueAmtTotal=0;			  
	$WedOrderTotal=0;
	$WedAmtTotal=0;			  
	$ThuOrderTotal=0;
	$ThuAmtTotal=0;			  
	$FriOrderTotal=0;
	$FriAmtTotal=0;			  
	$SatOrderTotal=0;
	$SatAmtTotal=0;			  

	while ($listItem=mysql_fetch_array($rptResult)){

		if(!isset($currentCompany)){
			$currentCompany=$listItem["company"];
			$currentAcct=$listItem["account_num"];
		}

		if($currentCompany!=$listItem["company"]){//if the company changes, print out the totals, zero out the counters and add the first totals of the new company
			$amtTotalDisplay=money_format('%.2n',$amtTotal);
			$SunAmtTotalDisplay=money_format('%.2n',$SunAmtTotal);
			$MonAmtTotalDisplay=money_format('%.2n',$MonAmtTotal);
			$TueAmtTotalDisplay=money_format('%.2n',$TueAmtTotal);
			$WedAmtTotalDisplay=money_format('%.2n',$WedAmtTotal);
			$ThuAmtTotalDisplay=money_format('%.2n',$ThuAmtTotal);
			$FriAmtTotalDisplay=money_format('%.2n',$FriAmtTotal);
			$SatAmtTotalDisplay=money_format('%.2n',$SatAmtTotal);
			echo "<tr>
			<td align=\"center\">$currentAcct</td>
			<td align=\"center\">$currentCompany</td>
			<td align=\"center\">$SunOrderTotal</td>
			<td align=\"center\">$SunAmtTotalDisplay</td>
			<td align=\"center\">$MonOrderTotal</td>
			<td align=\"center\">$MonAmtTotalDisplay</td>
			<td align=\"center\">$TueOrderTotal</td>
			<td align=\"center\">$TueAmtTotalDisplay</td>
			<td align=\"center\">$WedOrderTotal</td>
			<td align=\"center\">$WedAmtTotalDisplay</td>
			<td align=\"center\">$ThuOrderTotal</td>
			<td align=\"center\">$ThuAmtTotalDisplay</td>
			<td align=\"center\">$FriOrderTotal</td>
			<td align=\"center\">$FriAmtTotalDisplay</td>
			<td align=\"center\">$SatOrderTotal</td>
			<td align=\"center\">$SatAmtTotalDisplay</td>
			<td align=\"center\">$orderTotal</td>
			<td align=\"center\">$amtTotalDisplay</td>";
			echo "</tr>";
			$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$SunOrderTotal.chr(34).chr(44).chr(34).$SunAmtTotalDisplay.chr(34).chr(44).chr(34).$MonOrderTotal.chr(34).chr(44).chr(34).$MonAmtTotalDisplay.chr(34).chr(44).chr(34).$TueOrderTotal.chr(34).chr(44).chr(34).$TueAmtTotalDisplay.chr(34).chr(44).chr(34).$WedOrderTotal.chr(34).chr(44).chr(34).$WedAmtTotalDisplay.chr(34).chr(44).chr(34).$ThuOrderTotal.chr(34).chr(44).chr(34).$ThuAmtTotalDisplay.chr(34).chr(44).chr(34).$FriOrderTotal.chr(34).chr(44).chr(34).$FriAmtTotalDisplay.chr(34).chr(44).chr(34).$SatOrderTotal.chr(34).chr(44).chr(34).$SatAmtTotalDisplay.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(44).chr(13);
			$amtTotal=0;			  
			$orderTotal=0;
			$SunOrderTotal=0;
			$SunAmtTotal=0;			  
			$MonOrderTotal=0;
			$MonAmtTotal=0;			  
			$TueOrderTotal=0;
			$TueAmtTotal=0;			  
			$WedOrderTotal=0;
			$WedAmtTotal=0;			  
			$ThuOrderTotal=0;
			$ThuAmtTotal=0;			  
			$FriOrderTotal=0;
			$FriAmtTotal=0;			  
			$SatOrderTotal=0;
			$SatAmtTotal=0;			  
			$currentCompany=$listItem["company"];
			$currentAcct=$listItem["account_num"];

			$weekday=date("w",strtotime($listItem["order_date_processed"]));
			switch($weekday){
				case 0:
					$SunOrderTotal++;
					$SunAmtTotal=$listItem["order_total"];			  
				break;
				case 1:
					$MonOrderTotal++;
					$MonAmtTotal=$listItem["order_total"];			  
				break;
				case 2:
					$TueOrderTotal++;
					$TueAmtTotal=$listItem["order_total"];			  
				break;
				case 3:
					$WedOrderTotal++;
					$WedAmtTotal=$listItem["order_total"];			  
				break;
				case 4:
					$ThuOrderTotal++;
					$ThuAmtTotal=$listItem["order_total"];			  
				break;
				case 5:
					$FriOrderTotal++;
					$FriAmtTotal=$listItem["order_total"];			  
				break;
				case 6:
					$SatOrderTotal++;
					$SatAmtTotal=$listItem["order_total"];			  
				break;
			}
			$amtTotal=$listItem["order_total"];			  
			$orderTotal++;

		}else{//if it's still the same account, add in the totals
			$weekday=date("w",strtotime($listItem["order_date_processed"]));
			switch($weekday){
				case 0:
					$SunOrderTotal++;
					$SunAmtTotal= bcadd($SunAmtTotal, $listItem["order_total"], 2);			
				break;
				case 1:
					$MonOrderTotal++;
					$MonAmtTotal= bcadd($MonAmtTotal, $listItem["order_total"], 2);			
				break;
				case 2:
					$TueOrderTotal++;
					$TueAmtTotal= bcadd($TueAmtTotal, $listItem["order_total"], 2);			
				break;
				case 3:
					$WedOrderTotal++;
					$WedAmtTotal= bcadd($WedAmtTotal, $listItem["order_total"], 2);			
				break;
				case 4:
					$ThuOrderTotal++;
					$ThuAmtTotal= bcadd($ThuAmtTotal, $listItem["order_total"], 2);			
				break;
				case 5:
					$FriOrderTotal++;
					$FriAmtTotal= bcadd($FriAmtTotal, $listItem["order_total"], 2);			
				break;
				case 6:
					$SatOrderTotal++;
					$SatAmtTotal= bcadd($SatAmtTotal, $listItem["order_total"], 2);			
				break;
			}
			$amtTotal= bcadd($amtTotal, $listItem["order_total"], 2);			
			$orderTotal++;
		}//END IF NOT CURRENT ACCT
	}//END WHILE
	$amtTotalDisplay=money_format('%.2n',$amtTotal);
	$SunAmtTotalDisplay=money_format('%.2n',$SunAmtTotal);
	$MonAmtTotalDisplay=money_format('%.2n',$MonAmtTotal);
	$TueAmtTotalDisplay=money_format('%.2n',$TueAmtTotal);
	$WedAmtTotalDisplay=money_format('%.2n',$WedAmtTotal);
	$ThuAmtTotalDisplay=money_format('%.2n',$ThuAmtTotal);
	$FriAmtTotalDisplay=money_format('%.2n',$FriAmtTotal);
	$SatAmtTotalDisplay=money_format('%.2n',$SatAmtTotal);
	echo "<tr>
	<td align=\"center\">$currentAcct</td>
	<td align=\"center\">$currentCompany</td>
	<td align=\"center\">$SunOrderTotal</td>
	<td align=\"center\">$SunAmtTotalDisplay</td>
	<td align=\"center\">$MonOrderTotal</td>
	<td align=\"center\">$MonAmtTotalDisplay</td>
	<td align=\"center\">$TueOrderTotal</td>
	<td align=\"center\">$TueAmtTotalDisplay</td>
	<td align=\"center\">$WedOrderTotal</td>
	<td align=\"center\">$WedAmtTotalDisplay</td>
	<td align=\"center\">$ThuOrderTotal</td>
	<td align=\"center\">$ThuAmtTotalDisplay</td>
	<td align=\"center\">$FriOrderTotal</td>
	<td align=\"center\">$FriAmtTotalDisplay</td>
	<td align=\"center\">$SatOrderTotal</td>
	<td align=\"center\">$SatAmtTotalDisplay</td>
	<td align=\"center\">$orderTotal</td>
	<td align=\"center\">$amtTotalDisplay</td>";
	echo "</tr>";
	$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$SunOrderTotal.chr(34).chr(44).chr(34).$SunAmtTotalDisplay.chr(34).chr(44).chr(34).$MonOrderTotal.chr(34).chr(44).chr(34).$MonAmtTotalDisplay.chr(34).chr(44).chr(34).$TueOrderTotal.chr(34).chr(44).chr(34).$TueAmtTotalDisplay.chr(34).chr(44).chr(34).$WedOrderTotal.chr(34).chr(44).chr(34).$WedAmtTotalDisplay.chr(34).chr(44).chr(34).$ThuOrderTotal.chr(34).chr(44).chr(34).$ThuAmtTotalDisplay.chr(34).chr(44).chr(34).$FriOrderTotal.chr(34).chr(44).chr(34).$FriAmtTotalDisplay.chr(34).chr(44).chr(34).$SatOrderTotal.chr(34).chr(44).chr(34).$SatAmtTotalDisplay.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(44).chr(13);
		
	echo "</table>";
?>
