<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Statuses Updates</title>
</head>

<body>

<?php 
if ($_POST['theupdates'] <> '')


$today = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $today);

echo 'Date: ' .  $datecomplete .'<br><br>';
echo $_POST['theupdates'] ;
?>

</body>
</html>
