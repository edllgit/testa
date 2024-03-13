<?php
include("../Connections/sec_connect.inc.php");

		
$rptQuery= "UPDATE ORDERS 
SET prescript_lab = 30
WHERE  lab = 37 and prescript_lab = 0";
	
echo $rptQuery;
$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error());

echo 'Réussi';
?>