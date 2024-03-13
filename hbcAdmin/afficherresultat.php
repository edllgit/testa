<?php

include("../Connections/sec_connect.inc.php");

$leuser_id = $_REQUEST[leuser_id];

echo '<br>CLIENT:' . $leuser_id . '<br>';




echo '<br>AVRIL 2011<br>';

$rptQuery=" SELECT *
FROM `orders` WHERE user_id = '$leuser_id' AND order_date_processed BETWEEN '2011-04-01' AND '2011-04-08'";

$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 avril: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-04-09'
AND '2011-04-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 avril: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-04-17'
AND '2011-04-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 avril: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-04-25'
AND '2011-04-30'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 avril: ' .  $ordersnum . ' commandes<br>';






echo '<br>MAI  2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-05-01'
AND '2011-05-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 mai: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-05-09'
AND '2011-05-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 mai: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-05-17'
AND '2011-05-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 mai: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-05-25'
AND '2011-05-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 31 mai: ' .  $ordersnum . ' commandes<br>';










echo '<br>JUIN 2011<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-06-01'
AND '2011-06-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 juin: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-06-09'
AND '2011-06-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 juin: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-06-17'
AND '2011-06-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 juin: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-06-25'
AND '2011-06-30'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 juin: ' .  $ordersnum . ' commandes<br>';
















echo '<br>JUILLET 2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-07-01'
AND '2011-07-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 juillet: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-07-09'
AND '2011-07-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 juillet: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-07-17'
AND '2011-07-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 juillet: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-07-25'
AND '2011-07-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 31 juillet: ' .  $ordersnum . ' commandes<br>';









echo '<br>AOUT 2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-08-01'
AND '2011-08-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 aout: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-08-09'
AND '2011-08-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 aout: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-08-17'
AND '2011-08-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 aout: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-08-25'
AND '2011-08-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 31 aout: ' .  $ordersnum . ' commandes<br>';








echo '<br>SEPTEMBRE 2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-09-01'
AND '2011-09-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 septembre: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-09-09'
AND '2011-09-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 septembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-09-17'
AND '2011-09-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 septembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-09-25'
AND '2011-09-30'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 septembre: ' .  $ordersnum . ' commandes<br>';












echo '<br>OCTOBRE 2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-10-01'
AND '2011-10-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 octobre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-10-09'
AND '2011-10-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 octobre: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-10-17'
AND '2011-10-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 octobre: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-10-25'
AND '2011-10-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 septembre: ' .  $ordersnum . ' commandes<br>';








echo '<br>NOVEMBRE 2011<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-11-01'
AND '2011-11-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 novembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-11-09'
AND '2011-11-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 novembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-11-17'
AND '2011-11-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 novembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-11-25'
AND '2011-11-30'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 novembre: ' .  $ordersnum . ' commandes<br>';












echo '<br>DECEMBRE 2011<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-12-01'
AND '2011-12-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 decembre: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-12-09'
AND '2011-12-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 decembre: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-12-17'
AND '2011-12-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 decembre: ' .  $ordersnum . ' commandes<br>';




$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2011-12-25'
AND '2011-12-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 31 decembre: ' .  $ordersnum . ' commandes<br>';











echo '<br>JANVIER 2012<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-01-01'
AND '2012-01-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 janvier: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-01-09'
AND '2012-01-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 janvier: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-01-17'
AND '2012-01-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 janvier: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-01-25'
AND '2012-01-31'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 31 janvier: ' .  $ordersnum . ' commandes<br>';










echo '<br>FEVRIER 2012<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-02-01'
AND '2012-02-07'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 7 février: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-02-08'
AND '2012-02-15'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '8 au 15 février: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-02-16'
AND '2012-01-23'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '16 au 23 février: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-01-24'
AND '2012-01-29'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '24 au 29 février: ' .  $ordersnum . ' commandes<br>';







echo '<br>MARS 2012<br>';

$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-03-01'
AND '2012-03-08'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '1 au 8 mars: ' .  $ordersnum . ' commandes<br>';


$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-03-09'
AND '2012-03-16'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '9 au 16 mars: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-03-17'
AND '2012-03-24'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '17 au 24 mars: ' .  $ordersnum . ' commandes<br>';



$rptQuery="SELECT *
FROM `orders`
WHERE user_id = '$leuser_id'
AND order_date_processed
BETWEEN '2012-03-25'
AND '2012-03-30'";
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$ordersnum=mysql_num_rows($rptResult);
echo '25 au 30 mars: ' .  $ordersnum . ' commandes<br>';
	
	
			
?>