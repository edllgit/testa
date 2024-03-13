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

$qstring = "select i.product_inventory_id, i.lab_id, dl.lab_name, dl.lab_email, l.lab_name as selected_lab_name, m.manufacturer_id, p.primary_key as product_id, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, p.left_opc, p.mfg as manufacturer, m.email, 
			if(ml.selected_lab_id IS NULL, '', ml.selected_lab_id) as selected_lab_id, if(l.lab_email IS NULL, '', l.lab_email) as selected_lab_email, 
			i.inventory, i.order_total, (i.inventory - i.order_total) as available, pi.minimum_inventory, i.sent, (i.inventory-i.order_total) as avail_inventory
			from product_inventory as i 
			left join product_inventory_notification as pi on pi.lab_id = i.lab_id
			left join products as p on p.primary_key = i.product_id
			left join manufacturer as m on upper(m.name) = upper(p.mfg)
			left join manufacturer_to_lab as ml on ml.manufacturer_id = m.manufacturer_id
			left join labs as l on l.primary_key = ml.selected_lab_id
			left join labs as dl on dl.primary_key = i.lab_id
			where i.lab_id = '".$assigned_lab_id."' and i.sent != 1 and (i.inventory - i.order_total) <= pi.minimum_inventory and p.primary_key IS NOT NULL";


$qstring = "select i.product_inventory_id, i.lab_id, dl.lab_name, dl.lab_email, l.lab_name as selected_lab_name, m.manufacturer_id, p.primary_key as product_id, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, p.right_opc, p.left_opc, p.mfg as manufacturer, m.email, 
			if(ml.selected_lab_id IS NULL, '', ml.selected_lab_id) as selected_lab_id, if(l.lab_email IS NULL, '', l.lab_email) as selected_lab_email, 
			i.inventory, i.order_total, (i.inventory - i.order_total) as available, i.min_inventory as minimum_inventory, i.sent, (i.inventory-i.order_total) as avail_inventory
			from product_inventory as i 
			left join product_inventory_notification as pi on pi.lab_id = i.lab_id
			left join products as p on p.primary_key = i.product_id
			left join manufacturer as m on upper(m.name) = upper(p.mfg)
			left join manufacturer_to_lab as ml on ml.manufacturer_id = m.manufacturer_id
			left join labs as l on l.primary_key = ml.selected_lab_id
			left join labs as dl on dl.primary_key = i.lab_id
			where i.lab_id = '".$assigned_lab_id."' and i.sent != 1 and (i.inventory - i.order_total) < i.min_inventory and p.primary_key IS NOT NULL";


$products = dbFetchArray($qstring);	

$page_num = $pages = 1;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body>
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
		<td width="100%" align="center">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo($assigned_lab_name); ?> <?php echo $adm_titlemast_orderproc;?></font></b>        </td>
	</tr>
	</table>
	</form>
    
    <form action="<?php print( buildLink('cron.inventory.php', array(), array()) ); ?>" method="post">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
    <tr>
    	<td colspan="7" style="background:#000000;"><span style="color:#ffffff;"><?php echo $adm_prodorder_txt;?></span></td>
        <td align="right" style="background:#000000;" >
        <span style="color:#ffffff;">
        <?php if( $prevPage > 0 ) print('<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $prevPage), array()).'" style="color:#ffffff;">&lt;&lt; Prev</a>&nbsp;|'); ?>
        <?php print('&nbsp;Page <strong>'.$page_num.'</strong> of <strong>'.$pages.'</strong>&nbsp;'); ?>
        <?php if( $nextPage > 0 ) print('|&nbsp;<a href="'.buildLink($_SERVER['PHP_SELF'], array('p' => $nextPage), array()).'" style="color:#ffffff;">Next &gt;&gt;</a>'); ?>
        </span>
        </td>
    </tr>
	<?php if(count($products) > 0){ ?>
    <tr>
    	<td align="left" valign="top"><?php echo $adm_upc_txt;?></td>
    	<td align="left" valign="top"><?php echo $adm_product_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_coating_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_sphere_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_cylinder_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_orderaster_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_availinv_txt;?></td>
        <td align="left" valign="top"><?php echo $adm_mininventory_txt;?></td>
	</tr>
   	<?php
		//p.primary_key, p.product_name, p.coating_brand, p.sph_base, p.cyl_add, pi.product_inventory_id, pi.inventory
		$counter = 0;
        foreach($products as $record)
        {		
			$bg_color = ( $counter % 2 == 0 ? '#DDDDDD' : '#EDEDED' );
			
        	print('<tr>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['right_opc'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['product_name'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['coating_brand'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['sph_base'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['cyl_add'].'</td>');
			
			$num_to_order = $record['minimum_inventory'] + abs($record['inventory'] - $record['order_total']);
        	print('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="order['.$record['product_inventory_id'].']" value="'.$num_to_order.'"></td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['avail_inventory'].'</td>');
        	print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['minimum_inventory'].'</td>');
			print('</tr>');
			
			$counter++;
        }
	
		print('<tr><td colspan="7" style="border-top: 1px solid #000; font-size:11px;">'.$adm_procblurb1_txt.'</td><td align="left" valign="top" style="border-top: 1px solid #000;"><input name="do" type="submit" id="doSearch" value="process order" class="formField"></td></tr>');
    }
	else{ print('<tr><td align="center" colspan="8"><strong>'.$adm_bprocblurb2_txt.'</strong></td></tr>'); }
    ?>
	</table>
    <input type="hidden" name="lab_id" id="lab_id" value="<?php print(md5($assigned_lab_id)); ?>">
    </form>
	</td>
</tr>
</table>
</body>
</html>
