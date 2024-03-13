<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
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

if($_POST) updateInventorySettings($_POST);

$qstring = "select notification_email, notification_subject, notification_message, minimum_inventory, run_cron from product_inventory_notification where lab_id='".$assigned_lab_id."'";
$records = dbFetchArray($qstring);
$recordRow = $records[0];		
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
	<form method="post" name="notification_settings" id="notification_settings" action="<?php print($_SERVER['PHP_SELF']); ?>">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
	<tr bgcolor="#000000">
		<td align="center" colspan="3">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print($assigned_lab_name); ?> <?php echo $adm_titlemast_invnotify;?></font></b>        </td>
	</tr>
    <tr bgcolor="#DDDDDD">
    	<td width="24%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="right"><?php echo $adm_notemail_txt;?></div></td>
    	<td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><input name="notification_email" type="text" id="notification_email" size="43" value="<?php if( !empty($recordRow['notification_email']) ) print($recordRow['notification_email']); ?>"></td>
    	</tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">&nbsp;</td>
      <td width="14%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td width="62%" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="right"><?php echo $adm_notsubject_txt;?></div></td>
      <td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><input name="notification_subject" type="text" id="notification_subject" size="43" value="<?php if( !empty($recordRow['notification_subject']) ) print($recordRow['notification_subject']); ?>"></td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="top" align="left" nowrap bgcolor="#DDDDDD"><div align="right"><?php echo $adm_notmsg_txt;?></div></td>
      <td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><textarea name="notification_message" cols="40" rows="7" id="notification_message"><?php if( !empty($recordRow['notification_message']) ) print($recordRow['notification_message']); ?></textarea></td>
      </tr>
    
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD"><div align="right"><?php echo $adm_minprod_txt;?></div></td>
      <td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><input name="minimum_inventory" type="text" id="minimum_inventory" size="5" value="<?php if( !empty($recordRow['minimum_inventory']) ) print($recordRow['minimum_inventory']); ?>">        
      <span style="font-size:12px; font-style:italic;"><?php echo $adm_noteifany_txt;?></span>&nbsp;</td>
      </tr>
   <tr bgcolor="#DDDDDD">
      <td valign="middle" align="left" nowrap bgcolor="#DDDDDD" style="border-bottom: 1px solid #000;"><div align="right"><?php echo $adm_autorun_txt;?></div></td>
      <td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD" style="border-bottom: 1px solid #000;">
      <select name="run_cron" id="run_cron">
      <option value="1"<?php if($recordRow['run_cron'] || count($recordRow) <= 0) print(' selected="selected"'); ?>>Yes</option>
      <option value="0"<?php if(!$recordRow['run_cron'] && count($recordRow) > 0) print(' selected="selected"'); ?>>No</option>
      </select>
      </td>
      </tr>   
      <tr>
      <td valign="middle" align="left" nowrap>&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
      <td align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
    </tr>
      
      <tr bgcolor="#000000">
		<td align="center" colspan="3">
        <b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print($assigned_lab_name); ?> <?php echo $adm_titlemast_invemails;?></font></b>        </td>
	</tr>
    
    <?php
	//get manufacturers
	$qstring = "select m.manufacturer_id, m.name, m.email, ml.selected_lab_id from manufacturer as m
				left join manufacturer_to_lab as ml on ml.manufacturer_id = m.manufacturer_id and ml.lab_id='".$assigned_lab_id."' 
				order by m.name asc";
	$records = dbFetchArray($qstring);
	
	//get lab list
	$qstring = "select primary_key, lab_name from labs where primary_key != '".$assigned_lab_id."' order by lab_name asc";
	$lab_records = dbFetchArray($qstring);
	
	$counter = 0;
	foreach($records as $record)
	{
		$labs = '<select name="selected_lab_id['.$record['manufacturer_id'].']" id="selected_lab_id">'."\n";
		$labs .= '<option value="">Send to Manufacturer</option>'."\n";
		foreach($lab_records as $lab_record)
		{
			$labs .= '<option value="'.$lab_record['primary_key'].'"'.($record['selected_lab_id'] == $lab_record['primary_key'] ? ' selected="selected"' : '').'>'.$lab_record['lab_name'].'</option>'."\n";
		}
		$labs .= '</select>'."\n";
		
		$border = ($counter == count($records)-1 ? ' style="border-bottom: 1px solid #000;"' : '');
		
		print('<tr bgcolor="#DDDDDD">');
		print('<td width="24%" valign="middle" align="left" nowrap bgcolor="#DDDDDD"'.$border.'><div align="right" style="font-size:12px;">'.$record['name'].'</div></td>');
		print('<td colspan="2" align="left" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"'.$border.'>'.$labs.'</td>');
		print('</tr>');
		
		$counter++;
	}
	?>        
    <tr>
        <td align="left" >&nbsp;</td>
        <td align="left" nowrap ><input name="submit" type="submit" id="submit" value="<?php echo $btn_updatesettings_txt; ?>" class="formField"><input name="do" type="hidden" id="doSearch" value="update settings" class="formField"></td>
        <td align="left" nowrap style="font-size:14px;"><?php if($_GET['upd']) print($adm_noteyour_txt); ?>&nbsp;</td>
    </tr>
    <tr><td colspan="3" align="left" >&nbsp;</td></tr>
	</table>
	</form>
    
    </td>
</tr>
</table>
</body>
</html>
