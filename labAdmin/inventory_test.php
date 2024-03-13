<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
global $dbh;
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

if( isset($_GET['sf_product']) && !empty($_GET['sf_product']) && ($_GET['sf_product'] != "any"))
{
	$sf_product = $_GET['sf_product'];
}			

if( isset($_GET['sf_sphere']) && !empty($_GET['sf_sphere']) && ($_GET['sf_sphere'] != "any"))
{
	$sf_sphere = $_GET['sf_sphere'];
}			

if( isset($_GET['sf_cylinder']) && !empty($_GET['sf_cylinder']) && ($_GET['sf_cylinder'] != "any"))
{
	$sf_cylinder = $_GET['sf_cylinder'];
}			

if( isset($_GET['sf_sphere1']) && !empty($_GET['sf_sphere1']) && ($_GET['sf_sphere1'] != "any"))
{
	$sf_sphere1 = $_GET['sf_sphere1'];
}			

if( isset($_GET['sf_cylinder1']) && !empty($_GET['sf_cylinder1']) && ($_GET['sf_cylinder1'] != "any"))
{
	$sf_cylinder1 = $_GET['sf_cylinder1'];
}			

	
$product_dd_q = "SELECT product_name FROM products GROUP BY product_name ORDER BY product_name ASC";
$records = dbFetchArray($product_dd_q);
$products_dd_opts = array('any' => "Any");
foreach($records as $record) {
	if ($record['product_name'] != "In-Focus" && $record['product_name'] != "Innovative 1.6" && $record['product_name'] != "Innovative 1.7") $products_dd_opts[$record['product_name']] = $record['product_name'];
}

$products_dd_opts_str = "";
foreach ($products_dd_opts as $k => $v) {
	$products_dd_opts_str .= "<option value='".$k."'".($k == $sf_product ? " selected='selected'" : "").">".$v."</option>";
}
//cyl_add
$product_dd_q = "SELECT cyl_add FROM products GROUP BY cyl_add ORDER BY cyl_add ASC";
$records = dbFetchArray($product_dd_q);
$products_dd_opts = array('any' => "Any");
foreach($records as $record) {
	$products_dd_opts[$record['cyl_add']] = $record['cyl_add'];
}

$cylinder_dd_opts_str = "";
foreach ($products_dd_opts as $k => $v) {
	$cylinder_dd_opts_str .= "<option value='".$k."'".($k == $sf_cylinder ? " selected='selected'" : "").">".$v."</option>";
}
$cylinder_dd_opts_str1 = "";
foreach ($products_dd_opts as $k => $v) {
	$cylinder_dd_opts_str1 .= "<option value='".$k."'".($k == $sf_cylinder1 ? " selected='selected'" : "").">".$v."</option>";
}


//sph_base
$product_dd_q = "SELECT sph_base FROM products GROUP BY sph_base ORDER BY sph_base ASC";
$records = dbFetchArray($product_dd_q);
$products_dd_opts = array('any' => "Any");
foreach($records as $record) {
	$products_dd_opts[$record['sph_base']] = $record['sph_base'];
}

$sphere_dd_opts_str = "";
foreach ($products_dd_opts as $k => $v) {
	$sphere_dd_opts_str .= "<option value='".$k."'".($k == $sf_sphere ? " selected='selected'" : "").">".$v."</option>";
}
$sphere_dd_opts_str1 = "";
foreach ($products_dd_opts as $k => $v) {
	$sphere_dd_opts_str1 .= "<option value='".$k."'".($k == $sf_sphere1 ? " selected='selected'" : "").">".$v."</option>";
}


$assigned_lab_id   = $_SESSION['labAdminData']['primary_key'];
$assigned_lab_name = $_SESSION['labAdminData']['lab_name'];

if($_POST && !isset($_POST['min_inv_val'])) {
	updateInventory($_POST);
}

$qstring = "select 
			
			p.primary_key, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, pi.last_updated,
		    
			pi.product_inventory_id, pi.inventory, if(pi.last_updated != '0000-00-00 00:00:00', date_format(pi.last_updated, '%m-%d-%Y %h:%i %p'), '') as last_updated, 
			pi.order_total, (pi.inventory-pi.order_total) as avail_inventory, pi.min_inventory
			
			from products as p
			
			left join product_inventory as pi on pi.product_id = p.primary_key && pi.lab_id='".$assigned_lab_id."'
			
			where p.type='stock'";



if( isset($_GET['sf_product']) && !empty($_GET['sf_product']) && ($_GET['sf_product'] != "any"))
{
	$qstring .= " and p.product_name = '".mysql_escape_string($_GET['sf_product'])."'";
	$sf_product = $_GET['sf_product'];
}			

if( isset($_GET['sf_sphere']) && !empty($_GET['sf_sphere']) && ($_GET['sf_sphere'] != "any"))
{
	$qstring .= " and p.sph_base >= '".mysql_escape_string($_GET['sf_sphere'])."'";
	$sf_sphere = $_GET['sf_sphere'];
}			
if( isset($_GET['sf_sphere1']) && !empty($_GET['sf_sphere1']) && ($_GET['sf_sphere1'] != "any"))
{
	$qstring .= " and p.sph_base <= '".mysql_escape_string($_GET['sf_sphere1'])."'";
	$sf_sphere1 = $_GET['sf_sphere1'];
}			

if( isset($_GET['sf_cylinder']) && !empty($_GET['sf_cylinder']) && ($_GET['sf_cylinder'] != "any"))
{
	$qstring .= " and p.cyl_add >= '".mysql_escape_string($_GET['sf_cylinder'])."'";
	$sf_cylinder = $_GET['sf_cylinder'];
}			
if( isset($_GET['sf_cylinder1']) && !empty($_GET['sf_cylinder1']) && ($_GET['sf_cylinder1'] != "any"))
{
	$qstring .= " and p.cyl_add <= '".mysql_escape_string($_GET['sf_cylinder1'])."'";
	$sf_cylinder1 = $_GET['sf_cylinder1'];
}			

			
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
//echo $qstring;	
$records = dbFetchArray($qstring);

$min_inv_val = isset($_POST['min_inv_val']) ? intval($_POST['min_inv_val']) : false;
if ($min_inv_val !== false) {
	foreach($records as $record) {
		if (intval($record['product_inventory_id']))
        	$query = "UPDATE product_inventory SET min_inventory = ".$min_inv_val." WHERE product_inventory_id = ".$record['product_inventory_id'];
        else $query = "INSERT INTO product_inventory (lab_id, product_id, order_total, min_inventory) VALUES (".intval($assigned_lab_id).", ".intval($record['primary_key']).", 0, ".intval($min_inv_val).")";
        //echo $query."<br />";
        @mysql_query($query, $dbh);
   	}
	$records = dbFetchArray($qstring);
}


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
<link href="admin_print.css" rel="stylesheet" type="text/css" media="print"/>
<script src="../includes/jquery-1.2.6.min.js"></script>
<script src="../includes/jquery.tablesorter.js"></script>
  
  <script>
  $(document).ready(function(){
    $("#tabletosort").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
  });
  </script>


</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td width="25%" class="print_zerow"><?php include_once 'adminNav.php'; ?></td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="1" bgcolor="#000000">&nbsp;</td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
	<td width="75%" class="print_fullw">
	<form method="get" name="product_search" id="product_search" action="<?php print($_SERVER['PHP_SELF']); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
	<tr bgcolor="#000000">
		<td align="center" colspan="5">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print($assigned_lab_name); ?> <?php echo $adm_titlemast_prodinv; ?></font></b>        </td>
	</tr>
    <tr bgcolor="#DDDDDD">
    	<td width="20%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="left"><?php echo $adm_productsearch_txt; ?></div></td>
    	<td colspan="2" align="left" valign="middle" nowrap="nowrap"><?php echo $adm_availinv_txt; ?></td>
        <td width="30%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td width="30%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD"><input name="k" type="text" id="k" size="40" value="<?php if( !empty($_GET['k']) ) print($_GET['k']); ?>"></td>
      <td width="10%" align="left" valign="middle" nowrap="nowrap"><select name="as" id="as">
        <option value="">-- any --</option>
        <option value="&gt;"<?php if($_GET['as'] == '>') print(' selected="selected"'); ?>>&gt;</option>
        <option value="&gt;="<?php if($_GET['as'] == '>=') print(' selected="selected"'); ?>>&gt;=</option>
        <option value="&lt;"<?php if($_GET['as'] == '<') print(' selected="selected"'); ?>>&lt;</option>
        <option value="&lt;="<?php if($_GET['as'] == '<=') print(' selected="selected"'); ?>>&lt;=</option>
        <option value="="<?php if($_GET['as'] == '=') print(' selected="selected"'); ?>>==</option>
      </select>
      </td>
      <td align="left" valign="middle" nowrap="nowrap"><input name="ai" type="text" id="ai" size="14" value="<?php print($_GET['ai']); ?>"></td>
      <td align="left" valign="middle" nowrap="nowrap"><select name="s" id="s">
        <option value="0"<?php if(!$_GET['s']) print(' selected="selected"'); ?>>Show All Products</option>
        <option value="1"<?php if($_GET['s']=='1') print(' selected="selected"'); ?>>Show Products Updated Today Only</option>
      </select>      </td>
      <td align="left" valign="middle" nowrap="nowrap"><input name="submit" type="submit" id="submit" value="<?php $btn_search_txt;?>" class="formField"><input name="do" type="hidden" id="doSearch" value="search" class="formField"></td>
    </tr>
    <tr>
        <td align="left" >&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
    </tr>
    <tr><td colspan="3" align="left" >&nbsp;</td></tr>
	</table>
	</form>
	<form method="get" name="product_search1" id="product_search1" action="<?php print($_SERVER['PHP_SELF']); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
    <tr bgcolor="#DDDDDD">
    	<td width="5%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="left"><?php $adm_product_txt;?></div></td>
    	<td width="5%" align="left" valign="middle" nowrap="nowrap"><?php $adm_sphere_txt;?></td>
        <td width="5%" align="left" valign="middle" nowrap="nowrap"><?php $adm_cylinder_txt;?></td>
        <td width="5%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
        <td width="80%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">
      	<select name="sf_product"><?php echo $products_dd_opts_str?></select>
      </td>
      <td align="left" valign="middle" nowrap="nowrap">
      	<?php echo $adm_from_txt;?> <select name="sf_sphere"><?php echo $sphere_dd_opts_str?></select> <?php echo  $adm_to_txt;?> <select name="sf_sphere1"><?php echo $sphere_dd_opts_str1?></select>
      </td>
      <td align="left" valign="middle" nowrap="nowrap">
      	<?php echo $adm_from_txt;?> <select name="sf_cylinder"><?php echo $cylinder_dd_opts_str?></select> <?php echo $adm_to_txt;?> <select name="sf_cylinder1"><?php echo $cylinder_dd_opts_str1?></select>
      </td>
      <td align="left" valign="middle" nowrap="nowrap"><input name="submit" type="submit" id="submit" value="<?php echo $btn_search_txt;?>" class="formField"><input name="do" type="hidden" id="doSearch" value="search" class="formField"></td>
      <td width="80%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr>
        <td align="left" colspan="3">&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
        <td align="left" nowrap >&nbsp;</td>
    </tr>
    <tr><td colspan="5" align="left" >&nbsp;</td></tr>
	</table>
	</form>
	
	<form method="post" name="min_inv_upd" id="min_inv_upd" action="<?php echo buildLink($_SERVER['PHP_SELF'], array(), array()); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
    <tr bgcolor="#DDDDDD">
    	<td width="1%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="left" style="white-space: nowrap;"><?php echo $adm_setmininv_txt;?>&nbsp;</div></td>
    	<td width="1%" align="left" valign="middle" nowrap="nowrap"><input type="text" name="min_inv_val" value="" />&nbsp;</td>
        <td width="1%" align="left" valign="middle" nowrap="nowrap"><input name="submit" type="submit" id="submit" value="<?php echo $btn_update_txt;?>" class="formField"><input name="do" type="hidden" id="doSearch" value="update" class="formField"></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td align="left" colspan="4">&nbsp;</td>
    </tr>
	</table>
	</form>
	
	<!-- buildLink($_SERVER['PHP_SELF'], array('p' => $nextPage), array()) -->
	<?php
	
	
	?>
	<script type="text/javascript">
		function changePP(val) {
			switch (val) {
				case '20': window.location.href = '<?php print( buildLink($_SERVER['PHP_SELF'], array('pp' => '20'), array()))?>';  break;
				case '30': window.location.href = '<?php print( buildLink($_SERVER['PHP_SELF'], array('pp' => '30'), array()))?>';  break;
				case '40': window.location.href = '<?php print( buildLink($_SERVER['PHP_SELF'], array('pp' => '40'), array()))?>';  break;
				case '50': window.location.href = '<?php print( buildLink($_SERVER['PHP_SELF'], array('pp' => '50'), array()))?>';  break;
				case '60': window.location.href = '<<?php print( buildLink($_SERVER['PHP_SELF'], array('pp' => '60'), array()))?>';  break;
			}
		}
	</script>
    
    <form action="<?php print( buildLink($_SERVER['PHP_SELF'], array(), array()) ); ?>" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
    <tr class="print_hidden">
    	<td colspan="3" style="background:#000000;"><span style="color:#ffffff;"><?php echo $adm_prodresults_txt;?></span></td>
    	<td colspan="4" style="background:#000000; text-align: right;"><span style="color:#ffffff;"><?php echo $adm_howmany_txt;?> </span><select name="pp" onChange="changePP(this.value);"><?php echo $perPagesText?></select></td>
        <td align="right" style="background:#000000;">
        <span style="color:#ffffff;">
        <?php if( $prevPage > 0 ) print('<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $prevPage), array()).'" style="color:#ffffff;">&lt;&lt; Prev</a>&nbsp;|'); ?>
        <?php print('&nbsp;Page <strong>'.$page_num.'</strong> of <strong>'.$pages.'</strong>&nbsp;'); ?>
        <?php if( $nextPage > 0 ) print('|&nbsp;<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $nextPage), array()).'" style="color:#ffffff;">Next &gt;&gt;</a>'); ?>
        </span>
        </td>
    </tr>
    </table>
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField1" id="tabletosort">
	<?php if($num_records > 0){ ?>
	<thead>

    <tr>
    	<th align="left" valign="top"><?php echo $adm_upc_txt;?></th>
    	<th align="left" valign="top"><?php echo $adm_product_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_coating_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_sphere_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_cylinder_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_inventory_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_availinv_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_mininventory_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_lastupdated_txt;?></th>
	</tr>
	</thead>
	<tbody>
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
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['min_inventory'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.strtolower($record['last_updated']).'</td>');
			print('</tr>');
			
			$counter++;
        }
        print('</tbody>');
	
		print('<tr class="print_hidden"><td colspan="8" style="border-top: 1px solid #000;">&nbsp;</td><td align="left" style="border-top: 1px solid #000;"><input name="submit" type="submit" id="submit" value="'.$btn_updateinv_txt.'" class="formField"><input name="do" type="hidden" id="doSearch" value="update inventory" class="formField">&nbsp;<a href="'.str_replace(".php", "_print.php", $_SERVER['REQUEST_URI']).'" target="_blanck">'.$adm_print_txt.'</a>&nbsp;<!--<a href="#" onclick="window.print();">'.$adm_printv2_txt.'</a>--></td></tr>');
    }
	else{ print('<tr><td align="center" colspan="8"><strong>'.$adm_noprodsearch_txt.'</strong></td></tr>'); }
    ?>
    
	</table>
    </form>
	</td>
</tr>
</table>
</body>
</html>
