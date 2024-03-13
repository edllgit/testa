<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include("admin_functions.inc.php");
include "../sec_connectEDLL.inc.php";
//Le fichier getlang est partagé avec le labAdmin..Ne pas modifier!
include "../includes/getlang.php";

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if ($_POST[from_coupon_form]=="add"){
	$_POST[from_coupon_form]="";

	$code		= $_POST[code];
	$type		= $_POST[type];
	$select_by	= $_POST[select_by];
	$coating	= $_POST[coating];
	$system		= $_POST['system'];
	
	if ($select_by=="product")
		$product_name=$_POST[product_name];
	else if ($select_by=="collection")
		$collection=$_POST[collection];
	else if ($select_by=="coating"){
		$product_name="";
		$collection="";
	}else if ($select_by=="coating"){
		$product_name="";
		$collection="";
	}else if ($select_by=="system"){
		$product_name="";
		$collection="";
		$coating="";
	}

	$end_date=$_POST[end_date];
	$amount=$_POST[amount];
	$description = $_POST[description];
	$query="insert into coupon_codes (description, code,type,date,amount,collection,select_by,product_name, coating,system) values ('$description','$code','$type','$end_date','$amount','$collection','$select_by','$product_name','$coating','$system')";
	//echo '<br><br>'. $query;
	$result=mysqli_query($con,$query)		or die ("Could not create new product because " . mysqli_error($con) );
		
}

if ($_POST[from_coupon_form]=="update"){
	$_POST[from_coupon_form]="";


	$code=$_POST[code];
	$type=$_POST[type];
	$system=$_POST['system'];
	$select_by=$_POST[select_by];
	
	if ($select_by=="product")
		$product_name=$_POST[product_name];
	else if ($select_by=="collection")
		$collection=$_POST[collection];
	else if ($select_by=="coating")
		$coating=$_POST[coating];
	else if ($select_by=="all"){
		$product_name="";
		$collection="";
		$system="";
	}
	

	$end_date=$_POST[end_date];
	$amount=$_POST[amount];
	$description = $_POST[description];
	$query="Update coupon_codes SET description='$description',code='$code',type='$type',date='$end_date',amount='$amount',collection='$collection', coating='$coating',select_by='$select_by',product_name='$product_name', system='$system' WHERE primary_key='$_POST[pkey]'";
	$result=mysqli_query($con,$query)		or die ("Could not create new product because " . mysqli_error($con));
}

if ($_GET[edit]=="true"){

	$pkey=$_GET[pkey];

	$query="SELECT * from coupon_codes where primary_key='$pkey'";
	$result=mysqli_query($con,$query) or die ("Could not delete product");
		
	$couponItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
}

if ($_GET[delete]=="true"){

	$pkey=$_GET[pkey];

	$query="delete from coupon_codes where primary_key='$pkey'";
	$result=mysqli_query($con,$query) or die ("Could not delete product");
}
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["end_date"]);
}

</script>
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td>
		
		<?php
				include("couponForm.inc.php");
		?>
           
				
</td>
<td>
		
		<?php
			include("couponList.inc.php");
		?>
           
				
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
