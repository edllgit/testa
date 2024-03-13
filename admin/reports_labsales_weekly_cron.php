<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
//$labQuery="SELECT primary_key, reports_email from labs WHERE primary_key = 8";
$labQuery="SELECT primary_key, lab_name from labs";
$labResult=mysql_query($labQuery)	or die  ('I cannot select items because: ' . mysql_error());
$labcount=mysql_num_rows($labResult);
if($labcount != 0){
	$day_of_week=date("w");
	$date_of_week=date("Y-m-d");
	switch($day_of_week){
		case 0:
			$week_start=$date_of_week;
		break;
		case 1:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -1 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
		case 2:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -2 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
		case 3:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -3 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
		case 4:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -4 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
		case 5:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -5 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
		case 6:
			$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -6 DAY)");
			$week_start=mysql_result($startQuery, 0, 0);
		break;
	}
	$sunday_date = $week_start;
	$monday_date=date("Y-m-d", strtotime("$week_start + 1 day"));
	$tuesday_date=date("Y-m-d", strtotime("$week_start + 2 day"));
	$wednesday_date=date("Y-m-d", strtotime("$week_start + 3 day"));
	$thursday_date=date("Y-m-d", strtotime("$week_start + 4 day"));
	$friday_date=date("Y-m-d", strtotime("$week_start + 5 day"));
	$saturday_date=date("Y-m-d", strtotime("$week_start + 6 day"));
	
	$endQuery=mysql_query("SELECT DATE_ADD('$week_start', INTERVAL 6 DAY)");
	$week_end=mysql_result($endQuery, 0, 0);
	$fileOutput= chr(34)."Weekly Sales Report for week of $week_start - $week_end".chr(34).chr(13);
		
	while($labData=mysql_fetch_assoc($labResult)){//step thru each lab and build order rpt for each lab
		$lab_pkey=$labData[primary_key];
		$reports_email = $labData[reports_email];
		$lab_name=$labData[lab_name];

		$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, orders.order_num, orders.lab, orders.order_total, orders.order_date_processed from orders

		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) ";

		$rptQuery.="WHERE orders.lab='$lab_pkey' AND orders.order_num != '0' AND orders.order_status!='cancelled' AND orders.order_date_processed between '$week_start' and '$week_end'";
		$heading="$lab_name Sales Report for week of $week_start - $week_end";
	
		$rptQuery.=" group by order_num order by company";
		$rptResult=mysql_query($rptQuery)
			or die  ('I cannot select items because: ' . mysql_error());
		$ordercount=mysql_num_rows($rptResult);
		if($ordercount == 0){
			$fileOutput.= chr(34).$heading.chr(34).chr(13);
			$fileOutput.= chr(34)."There were no orders found".chr(34).chr(13);
		}else{
			//csv file headings
			$fileOutput.= chr(34).$heading.chr(34).chr(13);
	$fileOutput.= chr(34)."Account No".chr(34).chr(44).chr(34)."Company".chr(34).chr(44).chr(34)."Sunday ($sunday_date)".chr(34).chr(44).chr(44).chr(34)."Monday ($monday_date)".chr(34).chr(44).chr(44).chr(34)."Tuesday ($tuesday_date)".chr(34).chr(44).chr(44).chr(34)."Wednesday ($wednesday_date)".chr(34).chr(44).chr(44).chr(34)."Thursday ($thursday_date)".chr(34).chr(44).chr(44).chr(34)."Friday ($friday_date)".chr(34).chr(44).chr(44).chr(34)."Saturday ($saturday_date)".chr(34).chr(44).chr(44).chr(34)."Total".chr(34).chr(13);
	$fileOutput.= chr(44).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(44).chr(13);
			
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
		
			$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$SunOrderTotal.chr(34).chr(44).chr(34).$SunAmtTotalDisplay.chr(34).chr(44).chr(34).$MonOrderTotal.chr(34).chr(44).chr(34).$MonAmtTotalDisplay.chr(34).chr(44).chr(34).$TueOrderTotal.chr(34).chr(44).chr(34).$TueAmtTotalDisplay.chr(34).chr(44).chr(34).$WedOrderTotal.chr(34).chr(44).chr(34).$WedAmtTotalDisplay.chr(34).chr(44).chr(34).$ThuOrderTotal.chr(34).chr(44).chr(34).$ThuAmtTotalDisplay.chr(34).chr(44).chr(34).$FriOrderTotal.chr(34).chr(44).chr(34).$FriAmtTotalDisplay.chr(34).chr(44).chr(34).$SatOrderTotal.chr(34).chr(44).chr(34).$SatAmtTotalDisplay.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(44).chr(13);//print the last line
		}//END IF ORDERCOUNT
	}//END WHILE LABDATA
}//END IF LABCOUNT
if ($fileOutput!=""){
	email_report($fileOutput, "orders@direct-lens.com");
}
?>
