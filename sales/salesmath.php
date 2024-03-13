<?php
function getmytotalcommissions($cust_id, $date1, $date2){
		if($date1 == "all"){
			$startdate = "1980-01-01";
			$enddate = "2050-01-01";
		} else {
			$startdate = $date1;
			$enddate = $date2;
		}
		//convert cust id to user_id
		mysql_select_db($database_directlens);
		$query_cust = "SELECT * FROM accounts WHERE user_id = '".$cust_id."'";
		$cust = mysql_query($query_cust) or die(mysql_error());
		$row_cust = mysql_fetch_assoc($cust);
		$totalRows_cust = mysql_num_rows($cust);
		
		$myusername = $row_cust["user_id"];
		$mypercent =  $row_cust["sales_commission"];
		
		//find all orders from this customer
		mysql_select_db($database_directlens);
//previous ->	$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_processed BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_shipped BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		//compute order total costs	
		do{
			$totalcosts = $totalcosts + $row_orders["order_total"] +  $row_orders["order_shipping_cost"] ;
		} while ($row_orders = mysql_fetch_assoc($orders));
		
		//return total costs	
		$mycommission = $totalcosts * ($mypercent/100);
		return $mycommission;
}



function getmytotalcommissionsaftercredit($cust_id, $date1, $date2, $credits_amount){
		if($date1 == "all"){
			$startdate = "1980-01-01";
			$enddate = "2050-01-01";
		} else {
			$startdate = $date1;
			$enddate = $date2;
		}
		//convert cust id to user_id
		mysql_select_db($database_directlens);
		$query_cust = "SELECT * FROM accounts WHERE user_id = '".$cust_id."'";
		$cust = mysql_query($query_cust) or die(mysql_error());
		$row_cust = mysql_fetch_assoc($cust);
		$totalRows_cust = mysql_num_rows($cust);
		
		$myusername = $row_cust["user_id"];
		$mypercent =  $row_cust["sales_commission"];
		
		//find all orders from this customer
		mysql_select_db($database_directlens);
//previous ->	$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_processed BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_shipped BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		//compute order total costs	
		do{
			$totalcosts = $totalcosts + $row_orders["order_total"] +  $row_orders["order_shipping_cost"] ;
		} while ($row_orders = mysql_fetch_assoc($orders));
		
		//return total costs	
		$mycommission = ($totalcosts - $credits_amount) * ($mypercent/100);
		return $mycommission;
}

function getmycredformonth($myid,$date1, $date2, $user_id){
		
	/*	$queryUserid = "SELECT user_id from accounts WHERE sales_rep =  " . $rep;
		$resultUserId=mysql_query($queryUserid)	or die ("Could not find user");
		$listeUserId = '';
		$compteur = 0 ;
		while ($DataUserid=mysql_fetch_array($resultUserId)){
		$compteur +=1;
			if ($compteur == 1){
			$listeUserId =  '\'' . $DataUserid['user_id']. '\'';
			}else{
			$listeUserId =   $listeUserId  .  ",'" .  $DataUserid['user_id']. "'";
			}
		}*/

		//find all orders from this customer
		mysql_select_db($database_directlens);
		//$query_orders = "SELECT SUM(mcred_abs_amount) FROM memo_credits WHERE mcred_acct_user_id in ( " . $listeUserId. ")  and mcred_date BETWEEN '$date1' and '$date2'";
		$query_orders = "SELECT SUM(mcred_abs_amount) FROM memo_credits WHERE mcred_acct_user_id = '".  $user_id. "' and mcred_date BETWEEN '$date1' and '$date2'";		
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);

		//return total costs	
		return	money_format('%.2n',$row_orders["SUM(mcred_abs_amount)"]);	
}

function getmycommsformonth($myid,$mydate){
		$myyear = date('Y',strtotime($mydate));
		$mymonth = date('m',strtotime($mydate));
		//find all orders from this customer
		mysql_select_db($database_directlens);
		$query_orders = "SELECT * FROM statement_credits WHERE acct_user_id = '".$myid."' and stmt_month='$mymonth' and stmt_year='$myyear'";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
			
		//return total costs	
		return $row_orders["amount"];
}

function getmysalesformonth($cust_id,$mydate){
	$mymonthstr = date('Y',strtotime($mydate))."-".date('m',strtotime($mydate));
	
	//find all orders from this customer
		mysql_select_db($database_directlens);
		$query_orders = "SELECT * FROM orders WHERE user_id = '".$cust_id."' and order_date_shipped like '".$mymonthstr."%'";
		//echo $query_orders;
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		//compute order total costs	
		do{
			$totalcosts = $totalcosts + $row_orders["order_total"];
		} while ($row_orders = mysql_fetch_assoc($orders));
		
		return $totalcosts;
}



function getmytotalsales($cust_id,$date1,$date2){
	if($date1 == "all"){
			$startdate = "2001-01-01";
			$enddate = "2050-01-01";
		} else {
			$startdate = $date1;
			$enddate = $date2;
		}
		//convert cust id to user_id
		mysql_select_db($database_directlens);
		$query_cust = "SELECT * FROM accounts WHERE user_id = '".$cust_id."'";
		$cust = mysql_query($query_cust) or die(mysql_error());
		$row_cust = mysql_fetch_assoc($cust);
		$totalRows_cust = mysql_num_rows($cust);
		
		$myusername = $row_cust["user_id"];
		$mypercent =  $row_cust["sales_commission"];
		
		//find all orders from this customer
		mysql_select_db($database_directlens);
//		$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_processed BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$query_orders = "SELECT * FROM orders WHERE user_id = '".$myusername."' and order_date_shipped BETWEEN '".$startdate."' and '".$enddate."' order by orders.user_id asc";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		//compute order total costs	
		do{
			$totalcosts = $totalcosts + $row_orders["order_total"] + $row_orders["order_shipping_cost"];
		} while ($row_orders = mysql_fetch_assoc($orders));
		
		return $totalcosts;
}

function getgrandtotalsales($rep_id,$date1,$date2){
		if($date1 == "all"){
			$startdate = "2008-01-01";
			$enddate = "2050-01-01";
		} else {
			$startdate = $date1;
			$enddate = $date2;
		}
		//find all customers from this rep
		mysql_select_db($database_directlens);
		$query_custs = "SELECT * FROM accounts WHERE sales_rep = '".$rep_id."'";
		$custs = mysql_query($query_custs) or die(mysql_error());
		$row_custs = mysql_fetch_assoc($custs);
		$totalRows_custs = mysql_num_rows($custs);
		$costs = 0;
		
		do{
		//find all orders from this customer
		mysql_select_db($database_directlens);
//		$query_orders = "SELECT user_id,SUM(order_total) FROM orders WHERE user_id = '".$row_custs["user_id"]."' and order_date_processed BETWEEN '".$startdate."' and '".$enddate."' group by user_id";
		$query_orders = "SELECT user_id,SUM(order_total), SUM(order_shipping_cost) FROM orders WHERE user_id = '".$row_custs["user_id"]."' and order_date_shipped BETWEEN '".$startdate."' and '".$enddate."' group by user_id";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		$costs += $row_orders["SUM(order_total)"] + $row_orders["SUM(order_shipping_cost)"];
		} while ($row_custs = mysql_fetch_assoc($custs));
		
		return $costs;
}
function getgrandtotalcomms($rep_id,$date1,$date2){
			if($date1 == "all"){
			$startdate = "2008-01-01";
			$enddate = "2050-01-01";
		} else {
			$startdate = $date1;
			$enddate = $date2;
		}
		//find all customers from this rep
		mysql_select_db($database_directlens);
		$query_custs = "SELECT * FROM accounts WHERE sales_rep = '".$rep_id."'";
		$custs = mysql_query($query_custs) or die(mysql_error());
		$row_custs = mysql_fetch_assoc($custs);
		$totalRows_custs = mysql_num_rows($custs);
		$totalcosts = 0;
		do{
		//find all orders from this customer
		mysql_select_db($database_directlens);
//		$query_orders = "SELECT user_id,SUM(order_total) FROM orders WHERE user_id = '".$row_custs["user_id"]."' and order_date_processed BETWEEN '".$startdate."' and '".$enddate."' group by user_id";
		$query_orders = "SELECT user_id, SUM(order_total), SUM(order_shipping_cost) FROM orders WHERE user_id = '".$row_custs["user_id"]."' and order_date_shipped BETWEEN '".$startdate."' and '".$enddate."' group by user_id";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);

		$totalcosts += (($row_orders["SUM(order_total)"]+ $row_orders["SUM(order_shipping_cost)"] ) * ($row_custs["sales_commission"]/100));
		
		} while ($row_custs = mysql_fetch_assoc($custs));
		
		return $totalcosts;
		}
function allmyshipping($rep_id){

			$startdate = "2008-01-01";
			$enddate = "2050-01-01";
		//find all customers from this rep
		mysql_select_db($database_directlens);
		$query_custs = "SELECT * FROM accounts WHERE sales_rep = '".$rep_id."'";
		$custs = mysql_query($query_custs) or die(mysql_error());
		$row_custs = mysql_fetch_assoc($custs);
		$totalRows_custs = mysql_num_rows($custs);
		$costs = 0;
		
		do{
		//find all orders from this customer
		mysql_select_db($database_directlens);
		$query_orders = "SELECT user_id,SUM(order_shipping_cost) FROM orders WHERE user_id = '".$row_custs["user_id"]."' group by user_id";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		$costs += $row_orders["SUM(order_shipping_cost)"];
		//echo "<div>".$row_orders["user_id"]."--".$row_orders["SUM(order_total)"]."</div>";
		} while ($row_custs = mysql_fetch_assoc($custs));
		
		return $costs;
}
function allmysales($rep_id){

			$startdate = "2008-01-01";
			$enddate = "2050-01-01";
		//find all customers from this rep
		mysql_select_db($database_directlens);
		$query_custs = "SELECT * FROM accounts WHERE sales_rep = '".$rep_id."'";
		$custs = mysql_query($query_custs) or die(mysql_error());
		$row_custs = mysql_fetch_assoc($custs);
		$totalRows_custs = mysql_num_rows($custs);
		$costs = 0;
		
		do{
		//find all orders from this customer
		mysql_select_db($database_directlens);
		$query_orders = "SELECT user_id,SUM(order_total) FROM orders WHERE user_id = '".$row_custs["user_id"]."' group by user_id";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);
		$costs += $row_orders["SUM(order_total)"];
		//echo "<div>".$row_orders["user_id"]."--".$row_orders["SUM(order_total)"]."</div>";
		} while ($row_custs = mysql_fetch_assoc($custs));
		
		return $costs;
}
function allmycomms($rep_id){

			$startdate = "2008-01-01";
			$enddate = "2050-01-01";
		//find all customers from this rep
		mysql_select_db($database_directlens);
		$query_custs = "SELECT * FROM accounts WHERE sales_rep = '".$rep_id."'";
		$custs = mysql_query($query_custs) or die(mysql_error());
		$row_custs = mysql_fetch_assoc($custs);
		$totalRows_custs = mysql_num_rows($custs);
		$totalcosts = 0;
		do{
		//find all orders from this customer
		mysql_select_db($database_directlens);
		$query_orders = "SELECT user_id,SUM(order_total) FROM orders WHERE user_id = '".$row_custs["user_id"]."' group by user_id";
		$orders = mysql_query($query_orders) or die(mysql_error());
		$row_orders = mysql_fetch_assoc($orders);
		$totalRows_orders = mysql_num_rows($orders);

		$totalcosts += (($row_orders["SUM(order_total)"]) * ($row_custs["sales_commission"]/100));
		
		} while ($row_custs = mysql_fetch_assoc($custs));
		
		return $totalcosts;
		}
?>