<?php


$acctQuery="SELECT accounts.* FROM accounts
LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
WHERE accounts.user_id='$accUserId'";

$acctResult=mysql_query($acctQuery)
		or die  ($lbl_error1_txt . mysql_error()."SQL=".$acctQuery);
$listItem=mysql_fetch_assoc($acctResult);
$acctQuery="";



?>