<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$labQuery="SELECT primary_key, lab_name, reports_email from labs";
$labResult=mysql_query($labQuery)	or die  ('I cannot select items because: ' . mysql_error());
$labcount=mysql_num_rows($labResult);
if($labcount != 0){
	while($labData=mysql_fetch_assoc($labResult)){//step thru each lab and build order rpt for each lab
		$lab_pkey=$labData[primary_key];
		$reports_email = $labData[reports_email];
		$lab_name=$labData[lab_name];

		$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, orders.order_num, orders.lab, orders.order_total, orders.order_date_processed from orders

		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) ";

		$day_of_week=date("l");
		$date_of_week=date("Y-m-d");
//		$day_of_week="Monday";
//		$date_of_week="2010-03-01";
		$rptQuery.="WHERE orders.lab='$lab_pkey' AND orders.order_num != '0' AND orders.order_status!='cancelled' AND orders.order_date_processed = '$date_of_week'";
		$heading="$lab_name Daily Sales Report for $day_of_week, $date_of_week";
	
		$rptQuery.=" group by order_num order by company";
		$rptResult=mysql_query($rptQuery)	or die  ('I cannot select items because: ' . mysql_error());
		$ordercount=mysql_num_rows($rptResult);
		if($ordercount == 0){
			$fileOutput= chr(34).$heading.chr(34).chr(13);
			$fileOutput.= chr(34)."There were no orders found".chr(34).chr(13);
		}else{
			//csv file headings
			$fileOutput= chr(34).$heading.chr(34).chr(13);
			$fileOutput.= chr(34)."Account No".chr(34).chr(44).chr(34)."Company".chr(34).chr(44).chr(34)."Totals".chr(34).chr(13);
			$fileOutput.= chr(44).chr(44).chr(34)."Orders".chr(34).chr(44).chr(34)."Amount".chr(34).chr(13);
			
			$amtTotal=0;			  
			$orderTotal=0;
			while ($listItem=mysql_fetch_array($rptResult)){
				if(!isset($currentCompany)){
					$currentCompany=$listItem["company"];
					$currentAcct=$listItem["account_num"];
				}
				if($currentCompany!=$listItem["company"]){//if the company changes, print out the totals, zero out the counters and add the first totals of the new company
					$amtTotalDisplay=money_format('%.2n',$amtTotal);
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
			}//END WHILE LISTITEM
			$amtTotalDisplay=money_format('%.2n',$amtTotal);
			$fileOutput.= chr(34).$currentAcct.chr(34).chr(44).chr(34).$currentCompany.chr(34).chr(44).chr(34).$orderTotal.chr(34).chr(44).chr(34).$amtTotalDisplay.chr(34).chr(13);//print the last line
		}//END IF ORDERCOUNT
		if (($reports_email != "")&&($fileOutput!="")){
			//email_report($fileOutput, "dbeaulieu@direct-lens.com");
			email_report($fileOutput, $reports_email);
		}
	}//END WHILE LABDATA
}//END IF LABCOUNT
?>
