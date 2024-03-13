<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

/****
p (tbl products)
pi (tbl product_inventory)
l (tbl labs)
****/
require_once 'inc.functions.php';

$assigned_lab_id   = $_SESSION['labAdminData']['primary_key'];
$assigned_lab_name = $_SESSION['labAdminData']['lab_name'];

$qstring = "select a.company, a.last_name, a.first_name, p.mfg, p.d_index, p.product_name, o.order_num, o.order_product_index, o.order_quantity
			from accounts as a left join orders as o on o.user_id = lower(a.first_name)
			left join products as p on p.primary_key = o.order_product_id where o.lab='".$assigned_lab_id."'";

$set = 0; 

$date_to_original = $_GET['date_to'];
$date_to = explode('/', $_GET['date_to']);
$date_to = $date_to[2].'-'.$date_to[0].'-'.$date_to[1];

$date_from_original = $_GET['date_to'];
$date_from = explode('/', $_GET['date_from']);
$date_from = $date_from[2].'-'.$date_from[0].'-'.$date_from[1];

# check dates passed
if( isset($_GET['date_from']) && isset($_GET['date_to']) )
{
	$set = 1; $qstring .= " and (o.order_item_date >= '".$date_from." 00:00:01' and o.order_item_date <= '".$date_to." 11:59:59')";	
}
elseif( isset($_GET['date_to']) )
{
	$set = 1; $qstring .= " and o.order_item_date >= '".$date_from." 00:00:01'";	
}
elseif( isset($_GET['date_from']) )
{
	$set = 1; $qstring .= " and o.order_item_date <= '".$date_to." 11:59:59'";	
}

# check customer passed
if( isset($_GET['cid']) && !empty($_GET['cid']) ){ $set = 1; $qstring .= " and o.user_id = '".$_GET['cid']."'"; }

# check product passed
if( isset($_GET['pid']) && !empty($_GET['pid']) ){ $set = 1; $qstring .= " and o.order_product_name  = '".$_GET['pid']."'"; }

# check index passed
if( isset($_GET['index']) && !empty($_GET['index']) ){ $set = 1; $qstring .= " and o.order_product_index = '".$_GET['index']."'"; }
		
$qstring .= " group by o.order_num order by a.last_name asc, a.first_name asc, p.product_name asc";

if( $set )
{			
	$records = dbFetchArray($qstring);
	$num_records = count($records);
	
	if( $_GET['export'] )
	{
		define('NL', "\n");
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"Product_Inventory_Report_".date("mdY", time()).".csv\"");
		
		print('Order #,Customer Name,Manufacturer,Index #,Product Name,Total # Ordered'.NL);
		
		if($num_records > 0)
			foreach($records as $record)		
				print($record['order_num'].',"'.$record['last_name'].', '.$record['first_name'].'",'.$record['mfg'].','.$record['order_product_index'].','.$record['product_name'].','.$record['order_quantity'].NL);					
		
		exit;				
	}
	
	# get total pages and page numbers
	$pages = ceil($num_records / RESULTS_PER_PAGE);
	$page_num = ($_GET['p'] ? $_GET['p'] : 1);
	$nextPage = $prevPage = 0;
	
	if( $page_num > $pages ) $page_num = $pages;
	elseif( $page_num < $page ) $page_num = 1;
	
	if( $page_num != $pages ) $nextPage = $page_num+1;
	if( $page_num > 1 ) $prevPage = $page_num-1;
	
	# limited query
	if( $num_records > 0 ){
		$qstring .= " limit ".(($page_num * RESULTS_PER_PAGE) - RESULTS_PER_PAGE).", ".RESULTS_PER_PAGE;
		$records = dbFetchArray($qstring);
	}	
}		

//print($qstring);
//print('<pre>');
//print_r($records);	
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>

</head>

<body onLoad="window.print(); window.close();">
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td>
    
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
    <tr>
    	<td colspan="5" style="background:#000000;"><span style="color:#ffffff;">Report Search Results</span></td>
        <td align="right" style="background:#000000;">
        <span style="color:#ffffff;">
        <?php if( $prevPage > 0 ) print('<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $prevPage), array('export')).'" style="color:#ffffff;">&lt;&lt; Prev</a>&nbsp;|'); ?>
        <?php print('&nbsp;Page <strong>'.$page_num.'</strong> of <strong>'.$pages.'</strong>&nbsp;'); ?>
        <?php if( $nextPage > 0 ) print('|&nbsp;<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $nextPage), array('export')).'" style="color:#ffffff;">Next &gt;&gt;</a>'); ?>
        </span>        </td>
    </tr>
	<?php if($num_records > 0){ ?>
    <tr>
    	<td align="left" valign="top">Order #</td>
    	<td align="left" valign="top">Customer Name</td>
    	<td align="left" valign="top">Manufacturer</td>
        <td align="left" valign="top">Index #</td>
        <td align="left" valign="top">Product Name</td>
        <td align="left" valign="top">Total # Ordered</td>
	</tr>
   	<?php	
		$counter = 0;
        foreach($records as $record)
        {			
			$bg_color = ( $counter % 2 == 0 ? '#DDDDDD' : '#EDEDED' );
			
        	print('<tr>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['order_num'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['last_name'].', '.$record['first_name'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['mfg'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['order_product_index'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['product_name'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['order_quantity'].'</td>');
			print('</tr>');
			
			$counter++;
        }
    }
	else{ print('<tr><td align="center" colspan="6"><strong>There are no results that match your search criteria</strong></td></tr>'); }
    ?>
	</table>
	</td>
</tr>
</table>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
