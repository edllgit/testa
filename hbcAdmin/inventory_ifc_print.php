<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
require_once 'inc.functions.php';

if (isset($_GET['pp']) && intval($_GET['pp']) !== 0) {
	define("RESULTS_PER_PAGE1", intval($_GET['pp']));
}
elseif (isset($_SESSION['labAdminData']['perPage']) && intval($_SESSION['labAdminData']['perPage']) !== 0) {
	define("RESULTS_PER_PAGE1", intval($_SESSION['labAdminData']['perPage']));
}
if (!defined("RESULTS_PER_PAGE1")) define("RESULTS_PER_PAGE1", 200);
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


$qstring = "SELECT ifc_frames_french.*, product_inventory_ifc.product_inventory_id, product_inventory_ifc.min_inventory, product_inventory_ifc.inventory, product_inventory_ifc.last_updated, product_inventory_ifc.product_id
		FROM ifc_frames_french 
		LEFT JOIN product_inventory_ifc ON (product_inventory_ifc.product_id=ifc_frames_french.ifc_frames_id && product_inventory_ifc.lab_id='$assigned_lab_id' ) 
		ORDER BY code ";
			
				

	
$records = dbFetchArray($qstring);
$num_records = count($records);

# get total pages and page numbers
$pages = ceil($num_records / RESULTS_PER_PAGE1);
$page_num = ($_GET['p'] ? $_GET['p'] : 1);
$nextPage = $prevPage = 0;

if( $page_num > $pages ) $page_num = $pages;
elseif( $page_num < $page ) $page_num = 1;

if( $page_num != $pages ) $nextPage = $page_num+1;
if( $page_num > 1 ) $prevPage = $page_num-1;

	
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body onLoad="window.print();">
<?php /*?><body onLoad="window.print(); window.close();"><?php */?>
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
        <td align="left" valign="top">Type</td>
        <td align="left" valign="top">Color</td>
        <td align="left" valign="top">Collection</td>
        <td align="left" valign="top">Inventory</td>
        <td align="left" valign="top">Avail. Inventory</td>
        <td align="left" valign="top">Min. Inventory</td>
        <td align="left" valign="top">Last Updated</td>
	</tr>
   	<?php
		//p.primary_key, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, pi.product_inventory_id, pi.inventory
		$counter = 0;
        foreach($records as $record)
        {
			$bg_color = ( $counter % 2 == 0 ? '#DDDDDD' : '#EDEDED' );
			
			echo('<tr>');
			echo('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['upc'].'</td>');
			echo('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['code'].'</td>');
			echo('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['type'].'</td>');
			echo('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['color'].'</td>');
			echo('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['collection'].'</td>');
			
			echo('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="inventory['.$record['ifc_frames_id'].']"  value="'.$record['inventory'].'"></td>');

			
			print '<input name="previous_inventory['.$record['ifc_frames_id'].']" type="hidden" value="'.$record['inventory'].'">';
			
			print '<input name="previous_min_inventory['.$record['ifc_frames_id'].']" type="hidden" value="'.$record['min_inventory'].'">';
			
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['inventory'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="min_inventory['.$record['ifc_frames_id'].']"  value="'.$record['min_inventory'].'"></td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.strtolower($record['last_updated']).'</td>');
			print('</tr>');
			echo('</tr>');
			
			$counter++;
        }
	

    }
	else{ echo('<tr><td align="center" colspan="8"><strong>There are no products that match your search criteria</strong></td></tr>'); }
    ?>
	</table>
    </form>
	</td>
</tr>
</table>
</body>
</html>