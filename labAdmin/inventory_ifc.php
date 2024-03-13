<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "export_functions.inc.php";
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
require_once 'includes/inventory_func_ifc.inc.php';

if($_POST && isset($_POST['validate'])) {
	update_inventory_IFC();
	$_POST="";
	$messageText="<div class=\"messageText\">INVENTORY UPDATED</div>";
}

mysql_query("SET CHARACTER SET UTF8");

$lab_name=$_SESSION[labAdminData][lab_name];
$lab_id=$_SESSION[labAdminData][primary_key];

if ($_SESSION["accessid"] == 188)//Consignation Milano 6769
$FiltreRoberto = "  ifc_frames_french.collection  = 'MILANO 6769' ORDER BY model";
elseif($_SESSION["accessid"] == 187)//Consignation IFC France (Free, Free lus)
$FiltreRoberto = "  ifc_frames_french.misc_unknown_purpose  IN ('FREE','FREE PLUS')  ORDER BY misc_unknown_purpose, model";
else
$FiltreRoberto = "  1=1  ORDER BY collection";

$sql="SELECT ifc_frames_french.*, product_inventory_ifc.product_inventory_id, product_inventory_ifc.min_inventory, product_inventory_ifc.inventory, product_inventory_ifc.last_updated, product_inventory_ifc.product_id
		FROM ifc_frames_french 
		LEFT JOIN product_inventory_ifc ON (product_inventory_ifc.product_id=ifc_frames_french.ifc_frames_id && product_inventory_ifc.lab_id='$lab_id' ) 
		WHERE $FiltreRoberto ";
$frameResult=mysql_query($sql)			or die ("ERROR:".mysql_error()." sql=".$sql);
$frame_num=mysql_num_rows($frameResult);



	//CREATE EXPORT FILE//
	//$today=date("Y-m-d");
	//$filename="../tempDownloadFiles/IFC_Inventory". '-' . $today .".csv";
	//$fp=fopen($filename, "w");

	//$outputstring=Export_Inventory_IFC($lab_id);
	//fwrite($fp,$outputstring);
	//fclose($fp);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link href="admin_print.css" rel="stylesheet" type="text/css" media="print"/>
<script src="../includes/jquery-1.2.6.min.js"></script>
<script src="../includes/jquery.tablesorter.js"></script>
  
  <?php /*?><script>
  $(document).ready(function(){
    $("#tabletosort").tablesorter({sortList:[[0,0],[2,1]], widgets: ['zebra']});
  });
  </script><?php */?>


</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
	<td width="25%" class="print_zerow"><?php include_once 'adminNav.php'; ?></td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="1" bgcolor="#000000">&nbsp;</td>
    <td width="1" bgcolor="#FFFFFF">&nbsp;</td>
	<td width="75%" class="print_fullw">

	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField print_hidden">
	<tr bgcolor="#000000">
		<td align="center" colspan="5">
        <b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial"><?php echo $lab_name; ?> - <?php echo $adm_titlemast_prodinv; ?> - IFC</font></b>        </td>
	</tr>
	</table>
<?php if ($messageText!=""){ 
			echo $messageText;
			} 
		else{ $messageText=""; } ?>
        
        <h3> SVP Utiliser le serveur régulier pour faire la gestion les inventaires/ Please use the regular server to manage the inventory:</h3>
        <p align="center"><a href="http://www.direct-lens.com/labadmin">http://www.direct-lens.com/labadmin</a></p>

   <?php /*?>  <form action="inventory_ifc.php" method="post" enctype="multipart/form-data" id="form">
           <div class="formField2 print_hidden" style="text-align:right; padding:5px; margin-right:15px;"><div style="text-align:left; float:left">Number of Products: <b><?php echo  $frame_num; ?></b>&nbsp;&nbsp;<a href="<?php echo '../tempDownloadFiles/'. $filename?>">Download Csv file</a></div><input name="submit" type="submit" id="submit" value="<?php print $btn_updateinv_txt ?>" >
        
       <?php echo '&nbsp;<a href="'.str_replace(".php", "_print.php", $_SERVER['REQUEST_URI']).'" target="_blank">'.$adm_print_txt.'</a>';?>
        <input type="hidden" name="lab_id" id="lab_id" value=" <?php echo $_SESSION[labAdminData][primary_key] ?>">
        <input name="validate" type="hidden" id="validate" value="1"></div>
        
        	<div style="max-height:600px; overflow:auto">
   <?php /*?> <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField1" id="tabletosort">
    <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField1">
	<?php if ($frame_num!=0){ ?>
	<thead>

    <tr>
    	<th align="left" valign="top"><?php echo $adm_upc_txt;?></th>
    	<th align="left" valign="top"><?php echo $adm_product_txt;?></th>
        <th align="left" valign="top"><?php echo "TYPE"?></th>
        <th align="left" valign="top"><?php echo "COLOR"?></th>
        <th align="left" valign="top"><?php echo "COLLECTION";?></th>
        <th align="left" valign="top"><?php echo $adm_inventory_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_availinv_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_mininventory_txt;?></th>
        <th align="left" valign="top"><?php echo $adm_lastupdated_txt;?></th>
	</tr>
	</thead>
	<tbody>
   	<?php
		
		$counter = 0;
       	while ($record=mysql_fetch_assoc($frameResult))
        {
			$bg_color = ( $counter % 2 == 0 ? '#EEEEEE' : '#FFFFFF' );
			
			print('<tr>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['upc'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['code'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['type'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['color'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['collection'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="inventory['.$record['ifc_frames_id'].']"  value="'.$record['inventory'].'"></td>');
			
			print '<input name="prod_inventory_id['.$record['ifc_frames_id'].']" type="hidden" value="';
			if (!empty($record['product_inventory_id']) ? print $record['product_inventory_id'] :print "no value");
			print '" class="formField">';
			
			print '<input name="previous_inventory['.$record['ifc_frames_id'].']" type="hidden" value="'.$record['inventory'].'">';
			print '<input name="previous_min_inventory['.$record['ifc_frames_id'].']" type="hidden" value="'.$record['min_inventory'].'">';
			
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.$record['inventory'].'</td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'"><input type="text" size="5" maxlength="6" name="min_inventory['.$record['ifc_frames_id'].']"  value="'.$record['min_inventory'].'"></td>');
			print('<td align="left" valign="top" style="background:'.$bg_color.'">'.strtolower($record['last_updated']).'</td>');
			print('</tr>');
			
			$counter++;
        }
        print('</tbody>');
	
		
    }
	else{ print('<tr><td align="center" colspan="8"><strong>'.$adm_noprodsearch_txt.'</strong></td></tr>'); }
    ?>
    
	</table>
    </form><?php */?>
    </div>
	</td>
</tr>
</table>
</body>
</html>
