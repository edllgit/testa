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
if (isset($_GET['pp']) && intval($_GET['pp']) !== 0) {
	define("RESULTS_PER_PAGE1", intval($_GET['pp']));
}
elseif (isset($_SESSION['labAdminData']['perPage']) && intval($_SESSION['labAdminData']['perPage']) !== 0) {
	define("RESULTS_PER_PAGE1", intval($_SESSION['labAdminData']['perPage']));
}
if (!defined("RESULTS_PER_PAGE1")) define("RESULTS_PER_PAGE1", 20);
$_SESSION['labAdminData']['perPage'] = RESULTS_PER_PAGE1;

	$perPages = array(20, 30, 40, 50, 60);
	$perPagesText = "";
	foreach ($perPages as $k => $v) {
		if (RESULTS_PER_PAGE1 == $v) {
			$perPagesText .= "<option value='".$v."' selected='selected'>".$v."</option>";
		}
		else {
			$perPagesText .= "<option value='".$v."'>".$v."</option>";
		}
	}



$assigned_lab_id   = $_SESSION['labAdminData']['primary_key'];
$assigned_lab_name = $_SESSION['labAdminData']['lab_name'];

if($_POST) updateInventory($_POST);

$qstring = "select 
			
			p.primary_key, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, pi.last_updated,
		    
			pi.product_inventory_id, pi.inventory, if(pi.last_updated != '0000-00-00 00:00:00', date_format(pi.last_updated, '%m-%d-%Y %h:%i %p'), '') as last_updated, 
			pi.order_total, (pi.inventory-pi.order_total) as avail_inventory
			
			from products as p
			
			left join product_inventory as pi on pi.product_id = p.primary_key && pi.lab_id='".$assigned_lab_id."'
			
			where p.type='stock'";
			
			
if( ( isset($_GET['as']) && !empty($_GET['as']) ) && ( isset($_GET['ai']) && !empty($_GET['as']) ) )
{
	$qstring .= " and (pi.inventory-pi.order_total) ".$_GET['as']." ".$_GET['ai'];
}			

if($_GET['s'] == '1')
{
	$qstring .= " and (pi.last_updated >= '".date('Y-m-d', time())." 00:00:00' and pi.last_updated <= '".date('Y-m-d', time())." 11:59:59') and pi.lab_id='".$assigned_lab_id."'";
}
			
if($_GET['k'])
{
	$keywords = explode(',', $_GET['k']);
	
	$arr_tmp = array();
	foreach($keywords as $keyword)
	{
		$str_tmp = "(p.product_name like '%".$keyword."%' || p.coating_brand like '%".$keyword."%' ||
					 p.sph_base like '%".$keyword."%' || p.cyl_add like '%".$keyword."%' || right_opc like '%".$keyword."%')";
		array_push($arr_tmp, $str_tmp);
	}
	
	if( count($arr_tmp) > 0 )
	{
		$qstring .= " and ".implode(' and ', $arr_tmp);
	}
}

$qstring .= " group by p.right_opc";
$qstring .= " order by p.product_name asc";
//if( $_GET['test'] ) print($qstring);
	
$records = dbFetchArray($qstring);
$num_records = count($records);

if( $_GET['test'] )
{
	print($qstring);
	print('<pre>');
	print_r($num_records);
}
# get total pages and page numbers
$pages = ceil($num_records / RESULTS_PER_PAGE1);
$page_num = ($_GET['p'] ? $_GET['p'] : 1);
$nextPage = $prevPage = 0;

if( $page_num > $pages ) $page_num = $pages;
elseif( $page_num < $page ) $page_num = 1;

if( $page_num != $pages ) $nextPage = $page_num+1;
if( $page_num > 1 ) $prevPage = $page_num-1;

# limited query
if( $num_records > 0 ){
	$qstring .= " limit ".(($page_num * RESULTS_PER_PAGE1) - RESULTS_PER_PAGE1).", ".RESULTS_PER_PAGE1;
	$records = dbFetchArray($qstring);
}			
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body onLoad="window.print(); window.close();">
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td width="100%">
    <form action="<?php print( buildLink($_SERVER['PHP_SELF'], array(), array()) ); ?>" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
    <tr class="print_hidden">
    	<td colspan="8" style="background:#000000;"><span style="color:#ffffff;">Product Search Results</span></td>
    </tr>
	<?php if($num_records > 0){ ?>
    <tr>
    	<td align="left" valign="top">UPC</td>
    	<td align="left" valign="top">Product</td>
        <td align="left" valign="top">Coating</td>
        <td align="left" valign="top">Sphere</td>
        <td align="left" valign="top">Cylinder</td>
        <td align="left" valign="top">Inventory</td>
        <td align="left" valign="top">Avail. Inventory</td>
        <td align="left" valign="top">Last Updated</td>
	</tr>
   	<?php
		//p.primary_key, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, pi.product_inventory_id, pi.inventory
		$counter = 0;
        foreach($records as $record)
        {
			$bg_color = ( $counter % 2 == 0 ? '#DDDDDD' : '#EDEDED' );
			
			print('<tr>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['right_opc'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['product_name'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['coating_brand'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['sph_base'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['cyl_add'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="inventory['.( !empty($record['product_inventory_id']) ? 'upd_'.$record['product_inventory_id'].'_' : '' ).$record['primary_key'].']" value="'.( !empty($record['inventory']) ? $record['inventory'] : '0' ).'"></td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['avail_inventory'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.strtolower($record['last_updated']).'</td>');
			print('</tr>');
			
			$counter++;
        }
	

    }
	else{ print('<tr><td align="center" colspan="8"><strong>There are no products that match your search criteria</strong></td></tr>'); }
    ?>
	</table>
    </form>
	</td>
</tr>
</table>
</body>
</html>
