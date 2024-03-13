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
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>

</head>

<body onLoad="doOnLoad();">
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td width="25%"><?php include_once 'adminNav.php'; ?></td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="1" bgcolor="#000000">&nbsp;</td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
	<td width="75%">
	<form method="get" name="product_search" id="product_search" action="<?php print($_SERVER['PHP_SELF']); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
	<tr bgcolor="#000000">
		<td align="center" colspan="4">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print($assigned_lab_name); ?> <?php echo $adm_prodinvrpt_txt;?></font></b></td>
	</tr>
    <tr bgcolor="#DDDDDD">
      <td width="17%" align="left" valign="top" nowrap><div align="right"><?php echo $adm_daterange_txt;?></div></td>
      <td width="22%" align="left" valign="top" nowrap="nowrap"><input name="date_from" type="text" class="formField" id="date_from" value="<?php print($date_from_original); ?>" size="11" readonly>
      </td>
    	<td width="9%" align="left" valign="top" nowrap="nowrap"><?php echo $adm_custname_txt;?></td>
    	<td width="52%" align="left" valign="top" nowrap="nowrap"><select name="cid" id="cid">
          <option value=""><?php echo $adm_allcust_txt;?></option>
          <?php 
	  $qstring = "select primary_key, company, first_name, last_name, company from accounts where main_lab='".$assigned_lab_id."' and approved='approved' order by last_name asc, first_name asc";
	  $customers = dbFetchArray($qstring);
	  
	  if( count($customers) > 0 )
	  {
	  	foreach($customers as $customer)
		{
			print('<option value="'.strtolower($customer['first_name']).'"'.($_GET['cid'] == strtolower($customer['first_name']) ? ' selected="selected"' : '').'>'.$customer['last_name'].', '.$customer['first_name'].' ('.$customer['company'].')</option>'."\n");
		}
	  }
	  ?>
        </select></td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td align="right" valign="middle" nowrap><?php echo $adm_to_txt;?></td>
      <td align="left" valign="middle" nowrap="nowrap"><input name="date_to" type="text" class="formField" id="date_to" value="<?php print($date_to_original); ?>" size="11" readonly>
      </td>
      <td align="left" valign="middle" nowrap="nowrap"><?php echo $adm_prodname_txt;?></td>
      <td align="left" valign="middle" nowrap="nowrap"><select name="pid" id="pid">
        <option value=""><?php echo $adm_allprods_txt;?></option>
        <?php 
	  $qstring = "select distinct product_name from products  where type='stock'  order by product_name asc";
	
	  $products = dbFetchArray($qstring);
	  
	  if( count($products) > 0 )
	  {
	  	foreach($products as $product)
		{
			print('<option value="'.$product['product_name'].'"'.($_GET['pid'] == $product['product_name'] ? ' selected="selected"' : '').'>'.$product['product_name'].'</option>'."\n");
		}
	  }
	  ?>
      </select><?php  // echo '<br> Query: '.   $qstring  . '<br>'; ?></td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap"><?php echo $adm_indexnumsym_txt;?></td>
      <td align="left" valign="middle" nowrap="nowrap">
      <select name="index" id="index">
      <option value=""><?php echo $adm_allind_txt;?></option>
      <?php 
	  $qstring = "select order_product_index from orders group by order_product_index order by order_product_index asc";
	  $indexes = dbFetchArray($qstring);

	  if( count($indexes) > 0 )
	  {
	  	foreach($indexes as $index)
		{
			print('<option value="'.$index['order_product_index'].'"'.($_GET['index'] == $index['order_product_index'] ? ' selected="selected"' : '').'>'.number_format(round($index['order_product_index'], 2), 2).'</option>'."\n");
		}
	  }
	  ?>
      </select>      </td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">
      <input name="do" type="submit" id="doSearch" value="<?php echo $btn_search_txt;?>" class="formField">
      <input name="do" type="submit" id="doSearch" value="<?php echo $btn_searchexport_txt;?>" class="formField" onClick="document.getElementById('export').value=1;">
      <input type="hidden" name="export" id="export" value="0">      </td>
    </tr>
    
    <tr>
        <td align="left" >&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
    </tr>
    <tr><td colspan="3" align="left" >&nbsp;</td></tr>
	</table>
	</form>
    
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
    <tr>
    	<td colspan="5" style="background:#000000;"><span style="color:#ffffff;"><?php echo $adm_reportsearch_txt;?></span></td>
        <td align="right" style="background:#000000;">
        <span style="color:#ffffff;">
        <?php if( $prevPage > 0 ) print('<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $prevPage), array('export')).'" style="color:#ffffff;">&lt;&lt; Prev</a>&nbsp;|'); ?>
        <?php print('&nbsp;Page <strong>'.$page_num.'</strong> of <strong>'.$pages.'</strong>&nbsp;'); ?>
        <?php if( $nextPage > 0 ) print('|&nbsp;<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $nextPage), array('export')).'" style="color:#ffffff;">Next &gt;&gt;</a>'); ?>
        </span>        </td>
    </tr>
	<?php if($num_records > 0){ ?>
    <tr>
    	<td align="left" valign="top"><?php echo $adm_ordernumsym_txt;?></td>
    	<td align="left" valign="top"><?php echo $adm_custname_txt;?></td>
    	<td align="left" valign="top"><?php echo $adm_manfacturer_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_indexnumsym_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_prodname_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_totalnumsym_txt;?></td>
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
        	print('<tr>');
			print('<td align="right" colspan="6"><a href="'.str_replace(".php", "_print.php", $_SERVER['REQUEST_URI']).'" target="_blanck">'.$adm_print_txt.'</a></td>');
			print('</tr>');
    }
	else{ print('<tr><td align="center" colspan="6"><strong>'.$adm_noresults_txt.'</strong></td></tr>'); }
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
