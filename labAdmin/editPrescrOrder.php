<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

include("admin_functions.inc.php");
include("edit_order_functions.inc.php");
include("../includes/calc_functions.inc.php");

//Démarrer la session
session_start();


if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



if ($_POST[update_prescription]=="true"){
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - ORDER UPDATED</font></b>";
	$_POST[update_prescription]="";

	updatePrescription($_POST[pkey],$_POST[order_product_id],$_POST[eye]);
}

$pkey=$_POST[pkey];

$prescrQuery="SELECT * FROM orders WHERE primary_key='$pkey'"; //get order's user id
$prescrResult=mysqli_query($con,$prescrQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$listItem=mysqli_fetch_array($prescrResult,MYSQLI_ASSOC);

$queryPaidBY  = "SELECT * FROM payments WHERE order_num = " . $listItem[order_num];
$resultPaidBY = mysqli_query($con,$queryPaidBY)	or die ("Could not select items");
$DataPaidBY   = mysqli_fetch_array($resultPaidBY,MYSQLI_ASSOC);
 if ($DataPaidBY[pmt_type]=='credit card'){ 
 $PmtType = 'CC';
 }else{
 $PmtType = 'Other';
 }
 

if ($listItem[lab]==37)//It's an IFC order, we look for product in ifc_Exclusive table instead of exclusives
{
$prodQuery="SELECT * FROM ifc_exclusive WHERE primary_key='$listItem[order_product_id]'"; //get product info
$prodResult=mysqli_query($con,$prodQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
}else{
$prodQuery="SELECT * FROM exclusive WHERE primary_key='$listItem[order_product_id]'"; //get product info
$prodResult=mysqli_query($con,$prodQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
}

if ($listItem[order_from] == 'ifcclubca'){
$prodQuery="SELECT * from ifc_ca_exclusive WHERE primary_key='$listItem[order_product_id]'"; //get product info
$prodResult=mysqli_query($con,$prodQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
}

if ($listItem[order_from] == 'safety'){
$prodQuery="SELECT * FROM safety_exclusive WHERE primary_key='$listItem[order_product_id]'"; //get product info
$prodResult=mysqli_query($con,$prodQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
}

$listProd=mysqli_fetch_array($prodResult,MYSQLI_ASSOC);

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>
<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">EDIT
           		           PRESCRIPTION ORDER</font></b><?php echo $message;?></td>
       		  </tr>
			<tr><td>
			<form action="editPrescrOrder.php" method="post" enctype="multipart/form-data" name="editForm" id="editForm">
			  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField2">
                <tr >
                  <td colspan="2" align="right" bgcolor="#DDDDDD">
               <?php if ($mylang == 'lang_french'){ ?>
                 No. Commande:
               <?php }else{ ?>
                  Order Num:
               <?php } ?>
                  </td>
                  <td colspan="6" align="left" bgcolor="#DDDDDD"><?php echo $listItem[order_num] ?><?php if ($listItem[redo_order_num]!=0) echo "R (".$listItem[redo_order_num].")";?></td>
                </tr>
                <tr >
                  <td colspan="2" align="right" bgcolor="#FFFFFF">P.O. Num: </td>
                  <td colspan="6" align="left" bgcolor="#FFFFFF"><?php echo $listItem[po_num] ?></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#aaaaaa"><b>Patient:</b></td>
                 
                 <td colspan="2" align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Prénom</strong>
                 <?php }else{ ?>
                 <strong>First</strong>
                 <?php } ?>
                 </td>
                  
                  <td colspan="2" align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Nom</strong>
                 <?php }else{ ?>
                 <strong>Last</strong>
                 <?php } ?>
                  </td>
                  
                  <td align="center" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Cabaret client</strong>
                 <?php }else{ ?>
                 <strong>Customer Tray</strong>
                 <?php } ?>
                  </td>
                  
                  
                  <td align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Cabaret lab</strong>
                 <?php }else{ ?>
                 <strong>Lab Tray</strong>
                 <?php } ?>
                  </td>
                  
                  <td colspan="2" align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>ID Vendeur</strong>
                 <?php }else{ ?>
                 <strong>Salesperson ID</strong>
                 <?php } ?>
                  </td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD">&nbsp;</td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="order_patient_first" type="text" class="formField2" id="order_patient_first" value="<?php echo $listItem[order_patient_first];?>" size="15"></td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="order_patient_last" type="text" class="formField2" id="order_patient_last" value="<?php echo $listItem[order_patient_last];?>" size="20"></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="patient_ref_num" type="text" class="formField2" id="patient_ref_num" value="<?php echo $listItem[patient_ref_num];?>" size="15"></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="tray_num" type="text" class="formField2" id="tray_num" value="<?php echo $listItem[tray_num];?>" size="10"></td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="salesperson_id" type="text" class="formField2" id="salesperson_id" value="<?php echo $listItem[salesperson_id];?>" size="20"></td>
                </tr>
                <tr >
                  <td colspan="8" bgcolor="#555555">
                  <?php if ($mylang == 'lang_french'){ ?>
                  <font color="#FFFFFF">Produit - <?php echo $listItem[order_product_name];echo "&nbsp;&nbsp;&nbsp;&nbsp;Oeil: ".$listItem[eye]; ?></font>
                  <?php }else{ ?>
                  <font color="#FFFFFF">Product - <?php echo $listItem[order_product_name];echo "&nbsp;&nbsp;&nbsp;&nbsp;EYE: ".$listItem[eye]; ?></font>
                  <?php } ?>
                  </td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#FFFFFF">
                   <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Traitement:</strong>
                 <?php }else{ ?>
                 <strong>Coating:</strong>
                 <?php } ?>
                  </td>
                  <td width="9%" bgcolor="#FFFFFF"><?php echo $listItem[order_product_coating] ?></td>
                  <td width="12%" align="right" bgcolor="#FFFFFF" >
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Transitions:</strong>
                 <?php }else{ ?>
                 <strong>Photochromatic:</strong>
                 <?php } ?>
                  </td>
                  <td bgcolor="#FFFFFF"><?php echo $listItem[order_product_photo] ?></td>
                  <td align="right" bgcolor="#FFFFFF">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Polarisé:</strong>
                 <?php }else{ ?>
                 <strong>Polarized:</strong>
                 <?php } ?>
                  </td>
                  <td colspan="3" bgcolor="#FFFFFF"><?php echo $listItem[order_product_polar] ?></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#aaaaaa">&nbsp;</td>
                  <td align="center" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Sphère</strong>
                 <?php }else{ ?>
                 <strong>Sphere</strong>
                 <?php } ?>
                  </td>
                  <td align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Cylindre</strong>
                 <?php }else{ ?>
                 <strong>Cylinder</strong>
                 <?php } ?>
                  </td>
                  <td align="center" bgcolor="#aaaaaa">
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Axe</strong>
                 <?php }else{ ?>
                 <strong>Axis</strong>
                 <?php } ?>
                  </td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Addition</strong></td>
                  <td align="center" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Prisme</strong>
                 <?php }else{ ?>
                 <strong>Prism</strong>
                 <?php } ?>
                  </td>
                  <td width="7%" align="center" bgcolor="#aaaaaa" class="formCellNosidesCenter">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Quantité</strong>
                 <?php }else{ ?>
                 <strong>Quantity</strong>
                 <?php } ?>
                  </td>
                  <td width="15%" align="center" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Prix</strong>
                 <?php }else{ ?>
                 <strong>Price</strong>
                 <?php } ?>
                  </td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD">R.E.</td>
                  <td align="center" bgcolor="#DDDDDD"><select name="re_sphere" class="formfield2" id="re_sphere">
				  <?php
				  
				  $min=$listProd[sphere_over_min];
				  $max=$listProd[sphere_over_max];
				  
				   if ($listItem[eye]!="L.E."){
				  
				  		for ($i=$max;$i>=$min;$i=$i-.25){
				  			if($i<=0){
							$val=$i;
							$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[re_sphere]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				 		 }
				  
				    }//END L.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  } ?>
                    </select></td>
				  
                  <td align="center" bgcolor="#DDDDDD"><select name="re_cyl" class="formfield2" id="re_cyl">
				  <?php
				  
				  $min=$listProd[cyl_over_min];
				  $max=$listProd[cyl_max];
				  
				   if ($listItem[eye]!="L.E."){
				  
				  		for ($i=$max;$i>=$min;$i=$i-.25){
				  			if($i<=0){
							$val=$i;
							$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[re_cyl]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				 	 }   
				  }//END L.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  } ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="re_axis" type="text" class="formField2" id="re_axis"  value="<?php 
				  if ($listItem[re_axis] <> "–"){
				  echo $listItem[re_axis];
				  } ?>"  size="4" maxlength="4"></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="re_add" class="formfield2" id="re_add">
				  <?php
				  
				  $min=$listProd[add_min];
				  $max=$listProd[add_max];
				  
				   if ($listItem[eye]!="L.E."){
				  
				 	 for ($i=$max;$i>=$min;$i=$i-.25){
				  		if($i<=0){
							$val=$i;
							$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[re_add]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				  		}
				   }//END L.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  } ?>
                    </select></td>
                  <td align="right" bgcolor="#DDDDDD">
                    <input name="RE_PR_IO" type="radio" value="In" <?php if ($listItem[re_pr_io]=='In') echo 'checked="checked"';?>/>
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Int</strong>
                 <?php }else{ ?>
                 <strong>In</strong>
                 <?php } ?>
                    &nbsp;
  <input name="RE_PR_IO" type="radio" value="Out"<?php if ($listItem[re_pr_io]=='Out') echo 'checked="checked"';?> />
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Ext</strong>
                 <?php }else{ ?>
                 <strong>Out</strong>
                 <?php } ?>
                    
                    &nbsp;&nbsp; 
                    <input name="re_pr_ax" type="text" class="formField2" id="re_pr_ax" 
                     value="<?php 
				  if ($listItem[re_pr_ax] <> "–"){
				  echo $listItem[re_pr_ax];
				  } ?>"
                   size="4" maxlength="4"><br><input name="RE_PR_UD" type="radio" value="Up" <?php if ($listItem[re_pr_ud]=='Up') echo 'checked="checked"';?>/>
                   <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Haut</strong>
                 <?php }else{ ?>
                 <strong>Up</strong>
                 <?php } ?>
                   &nbsp;
<input name="RE_PR_UD" type="radio" value="Down" <?php if ($listItem[re_pr_ud]=='Down') echo 'checked="checked"';?>/>
				<?php if ($mylang == 'lang_french'){ ?>
                 <strong>Bas</strong>
                 <?php }else{ ?>
                 <strong>Down</strong>
                 <?php } ?>
&nbsp;<input name="re_pr_ax2" type="text" class="formField2" id="re_pr_ax2" 
 value="<?php 
				  if ($listItem[re_pr_ax2] <> "–"){
				  echo $listItem[re_pr_ax2];
				  } ?>"
value="<?php echo $listItem[re_pr_ax2];?>" size="4" maxlength="4"></td><td rowspan="9" align="center" valign="top" class="formCellNosidesCenter"><input name="order_quantity" type="text" class="formField2" id="order_quantity" value="<?php echo $listItem[order_quantity];?>" size="4" maxlength="4"></td>
                  <td rowspan="9" align="right" valign="top"><b>$ <?php echo $listItem[order_product_price];?></b><?php 
					  
					  
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					
					$e_product_total=getExtraProdTotal($listItem[order_num]);
					
					$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_product_total)+$over_range+$listItem[extra_product_price]-$coupon_dsc;
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
				if ($over_range!=0){echo "<br> Over range: ";
				echo $over_range;}?><br>
                
				<?php if ($e_product_total!=0){echo " Extra Products: ";
				echo $e_product_total."<br>";}?>
                
				<?php if ($listItem[extra_product_price]!=0){echo " Extra item: ";
				echo $listItem[extra_product_price]."<br>";}?>
				<?php if ($listItem[coupon_dsc]!=0){echo " Coupon: -";
				echo $listItem[coupon_dsc]."<br>";}?>
                
                <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Sous-total:</strong>
                 <?php }else{ ?>
                 <strong>Subtotal:</strong>
                 <?php } ?>
                     <b><?php echo $itemSubtotal;?></b></td>
                </tr>
                <tr >
                  <td colspan="6" align="left"><strong>
                  
                  <?php if ($mylang == 'lang_french'){ ?>
                 PD de loin:
                 <?php }else{ ?>
                 Dist.PD:
                 <?php } ?>
                  
                      <input name="re_pd" type="text" class="formField2" id="re_pd"  value="<?php 
				  if ($listItem[re_pd] <> "–"){
				  echo $listItem[re_pd];
				  } ?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;
                 
                 <strong>
                 <?php if ($mylang == 'lang_french'){ ?>
                 PD de près:
                 <?php }else{ ?>
                 Near PD:
                 <?php } ?>
                 </strong>
                  <input name="re_pd_near" type="text" class="formField2" id="re_pd_near" value="<?php 
				  if ($listItem[re_pd_near] <> "–"){
				  echo $listItem[re_pd_near];
				  } ?>" size="4" maxlength="4">                  &nbsp;&nbsp;&nbsp;
                  
                  <strong>
                <?php if ($mylang == 'lang_french'){ ?>
                Hauteur:
                 <?php }else{ ?>
                 Height:
                 <?php } ?>
                  <input name="re_height" type="text" class="formField2" id="re_height"  value="<?php 
				  if ($listItem[re_height] <> "–"){
				  echo $listItem[re_height];
				  } ?>" size="4" maxlength="4"></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD"> L.E.</td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_sphere" class="formfield2" id="le_sphere">
				  <?php
				  
				  $min=$listProd[sphere_over_min];
				  $max=$listProd[sphere_over_max];
				  
				  if ($listItem[eye]!="R.E."){
				  
				 	 for ($i=$max;$i>=$min;$i=$i-.25){
				  		if($i<=0){
							$val=$i;
							$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[le_sphere]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				  	} 
				  }//END R.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  }
				  
				  ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_cyl" class="formfield2" id="le_cyl">
				  <?php
				  
				  $min=$listProd[cyl_over_min];
				  $max=$listProd[cyl_max];
				  
				   if ($listItem[eye]!="R.E."){
				  
				  		for ($i=$max;$i>=$min;$i=$i-.25){
				  			if($i<=0){
								$val=$i;
								$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[le_cyl]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				  		}
				  }//END R.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  }
				   ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="le_axis" type="text" class="formField2" id="le_axis" 
                  value="<?php 
				  if ($listItem[le_axis] <> "–"){
				  echo $listItem[le_axis];
				  } ?>"
                  
                  size="4" maxlength="4"></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_add" class="formfield2" id="le_add">
				  <?php
				  
				  $min=$listProd[add_min];
				  $max=$listProd[add_max];
				  
				   if ($listItem[eye]!="R.E."){
				  
				  		for ($i=$max;$i>=$min;$i=$i-.25){
				  			if($i<=0){
							$val=$i;
							$val=money_format('%.2n',$val);
							}
						else{
							$val=$i;
							$val=money_format('%.2n',$i);
							$val="+".$val;}
				  		echo "<option value=\"$val\"";
					
						if ($listItem[le_add]==$i) echo "selected=\"selected\"";
					
						echo">$val</option>";
				 	 } 
				   }//END R.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"\" selected=\"selected\"></option>";
				  }?>
                    </select></td>
                  <td align="right" bgcolor="#DDDDDD">
                    <input name="LE_PR_IO" type="radio" value="In" <?php if ($listItem[le_pr_io]=='In') echo 'checked="checked"';?>/>
                   <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Int</strong>
                 <?php }else{ ?>
                 <strong>In</strong>
                 <?php } ?> 
                    
                    &nbsp;
  <input name="LE_PR_IO" type="radio" value="Out" <?php if ($listItem[le_pr_io]=='Out') echo 'checked="checked"';?>/>
                    
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Ext</strong>
                 <?php }else{ ?>
                 <strong>Out</strong>
                 <?php } ?>
                    &nbsp; 
                    <input name="le_pr_ax" type="text" class="formField2" id="le_pr_ax" 
                     value="<?php 
				  if ($listItem[le_pr_ax] <> "–"){
				  echo $listItem[le_pr_ax];
				  } ?>"
                    value="<?php echo $listItem[le_pr_ax];?>" size="4" maxlength="4"><br><input name="LE_PR_UD" type="radio" value="Up" <?php if ($listItem[le_pr_ud]=='Up') echo 'checked="checked"';?>/>
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Haut</strong>
                 <?php }else{ ?>
                 <strong>Up</strong>
                 <?php } ?>
                    &nbsp;<input name="LE_PR_UD" type="radio" value="Down" <?php if ($listItem[le_pr_ud]=='Down') echo 'checked="checked"';?>/>
                    <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Bas</strong>
                 <?php }else{ ?>
                 <strong>Down</strong>
                 <?php } ?>
                    &nbsp; <input name="le_pr_ax2" type="text" class="formField2" id="le_pr_ax2" 
                     value="<?php 
				  if ($listItem[le_pr_ax2] <> "–"){
				  echo $listItem[le_pr_ax2];
				  } ?>"
                    value="<?php echo $listItem[le_pr_ax2];?>" size="4" maxlength="4"></td></tr>
                <tr >
                  <td colspan="6" align="left" bgcolor="#FFFFFF">
                  <strong>
                 <?php if ($mylang == 'lang_french'){ ?>
                 PD de loin:
                 <?php }else{ ?>
                 Dist.PD:
                 <?php } ?>
                 
                      <input name="le_pd" type="text" class="formField2" id="le_pd"  value="<?php 
				  if ($listItem[le_pd] <> "–"){
				  echo $listItem[le_pd];
				  } ?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;
                  <strong>
                  
                  <?php if ($mylang == 'lang_french'){ ?>
                 PD de près:
                 <?php }else{ ?>
                 Near PD:
                 <?php } ?>
                 
                  <input name="le_pd_near" type="text" class="formField2" id="le_pd_near"  value="<?php 
				  if ($listItem[le_pd_near] <> "–"){
				  echo $listItem[le_pd_near];
				  } ?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;
                  
                  <strong>
                 <?php if ($mylang == 'lang_french'){ ?>
                 Hauteur:
                 <?php }else{ ?>
                 Height:
                 <?php } ?>
                  <input name="le_height" type="text" class="formField2" id="le_height"  value="<?php 
				  if ($listItem[le_height] <> "–"){
				  echo $listItem[le_height];
				  } ?>" size="4" maxlength="4"></td>
                </tr>
                <tr >
                  <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>PT:
                      <input name="PT" type="text" class="formField2" id="PT" value="<?php echo $listItem[PT];?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;<strong>PA:
                  <input name="PA" type="text" class="formField2" id="PA" value="<?php echo $listItem[PA];?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;<strong>Vertex:</strong>
                  <input name="vertex" type="text" class="formField2" id="vertex" value="<?php echo $listItem[vertex];?>" size="4" maxlength="4"></td>
                </tr>
              
              
              
               <tr >
                  <td colspan="6" align="left" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Épaisseurs:</strong>
                 <?php }else{ ?>
                 <strong>Thickness</strong>
                 <?php } ?>
                  </td>
                </tr>
               
                <tr>
               <td>RE CT</td><td> <input name="RE_CT" type="text" class="formField2" id="RE_CT" value="<?php echo $listItem[RE_CT];?>" size="4" maxlength="4"></td>
               <td>LE CT</td><td> <input name="LE_CT" type="text" class="formField2" id="LE_CT" value="<?php echo $listItem[LE_CT];?>" size="4" maxlength="4"></td>
               </tr>
               <tr>
               <td>RE ET</td><td> <input name="RE_ET" type="text" class="formField2" id="RE_ET" value="<?php echo $listItem[RE_ET];?>" size="4" maxlength="4"></td>
               <td>LE ET</td><td> <input name="LE_ET" type="text" class="formField2" id="LE_ET" value="<?php echo $listItem[LE_ET];?>" size="4" maxlength="4"></td>
                </tr>
                
                
                
               <tr >
                  <td colspan="6" align="left" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Courbure de base</strong>
                 <?php }else{ ?>
                 <strong>Base curve</strong>
                 <?php } ?>
                  </td>
                </tr>
               
                 <tr>
                   <td>
				 <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Courbure de base:</strong>
                 <?php }else{ ?>
                 <strong>Base curve:</strong>
                 <?php } ?>
                 </td><td> <input name="base_curve" type="text" class="formField2" id="base_curve" value="<?php echo $listItem[base_curve];?>" size="4" maxlength="4"></td>
                </tr>
              
              
                <tr >
                  <td colspan="6" align="left" bgcolor="#aaaaaa">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Monture:</strong>
                 <?php }else{ ?>
                 <strong>FRAME</strong>
                 <?php } ?>
                  </td>
                </tr>
                <tr >
                  <td colspan="6" align="left" bgcolor="#DDDDDD"><strong>&nbsp;&nbsp;&nbsp;A: </strong>
                    <input name="frame_a" type="text" class="formField2" id="frame_a" value="<?php echo $listItem[frame_a];?>" size="4" maxlength="4" >                    
                    &nbsp;&nbsp;&nbsp;<strong>B: </strong>
                    <input name="frame_b" type="text" class="formField2" id="frame_b" value="<?php echo $listItem[frame_b];?>" size="4" maxlength="4" >
                    <strong>&nbsp;&nbsp;&nbsp;ED: </strong><strong>
                    <input name="frame_ed" type="text" class="formField2" id="frame_ed" value="<?php echo $listItem[frame_ed];?>" size="4" maxlength="4">
                    &nbsp;&nbsp;&nbsp;DBL: </strong>&nbsp;
                    <input name="frame_dbl" type="text" class="formField2" id="frame_dbl" value="<?php echo $listItem[frame_dbl];?>" size="4" maxlength="4">
                    &nbsp;&nbsp;<strong>Type:</strong><span class="formfield2">
                    <select name="frame_type" class="formfield2" id="frame_type" >
                      <option value=""<?php if ($listItem['frame_type']=="") echo "selected=\"selected\"";?>>None</option>
                      <option value="Nylon Groove"<?php if ($listItem['frame_type']=="Nylon Groove") echo "selected=\"selected\"";?>>Nylon Groove</option>
                      <option value="Metal Groove"<?php if ($listItem['frame_type']=="Metal Groove") echo "selected=\"selected\"";?>>Metal Groove</option>
                      <option value="Plastic"<?php if ($listItem['frame_type']=="Plastic") echo "selected=\"selected\"";?>>Plastic</option>
                      <option value="Metal"<?php if ($listItem['frame_type']=="Metal") echo "selected=\"selected\"";?>>Metal</option>
                      <option value="Edge Polish"<?php if ($listItem['frame_type']=="Edge Polish") echo "selected=\"selected\"";?>>Edge Polish</option>   
                      <option value="Drill and Notch"<?php if ($listItem['frame_type']=="Drill and Notch") echo "selected=\"selected\"";?>>Drill and Notch</option>
                    </select>
                    </span></td>
                </tr>
                <tr>
                  <td colspan="2" align="right" bgcolor="#FFFFFF">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Instructions spéciales:</strong>
                 <?php }else{ ?>
                 <strong>Special Instructions:</strong>
                 <?php } ?>
                  </td>
                  <td colspan="4" align="left" bgcolor="#FFFFFF"><input name="special_instructions" type="text" class="formField2" id="special_instructions" value="<?php echo $listItem[special_instructions];?>" size="40"></td>
                </tr>
                
                
                  <tr>
                  <td colspan="2" align="right" bgcolor="#FFFFFF">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Note Interne:</strong>
                 <?php }else{ ?>
                 <strong>Internal Note:</strong>
                 <?php } ?>
                  </td>
                  <td colspan="4" align="left" bgcolor="#FFFFFF"><input name="INTERNAL_NOTE" type="text" class="formField2" id="INTERNAL_NOTE" value="<?php echo $listItem[internal_note];?>" size="40"></td>
                </tr>
                
               <tr>
                  <td colspan="2" align="right" bgcolor="#FFFFFF">
                  <?php if ($mylang == 'lang_french'){ ?>
                 <strong>Centre optique:</strong>
                 <?php }else{ ?>
                 <strong>Optical Center:</strong>
                 <?php } ?>
                  </td>
                  <td colspan="4" align="left" bgcolor="#FFFFFF"><input name="optical_center" type="text" class="formField2" id="optical_center" value="<?php echo $listItem[optical_center];?>" size="10"></td>
                </tr>
                
                
                
                   <tr align="center">
                	<td colspan="8"><?php  if ($mylang == 'lang_french'){
					echo "<strong>VALIDATION DE SÉCURITÉ</strong>";
					}else {
					echo "<strong>SECURITY VALIDATION</strong>";
					} ?></td>
                </tr>
                <tr align="center">
                	<td align="center"  bgcolor="#FF0000" colspan="8">
					<?php  if ($mylang == 'lang_french'){
					echo "<b>Pour savegarder vos modifications, vous devez entrer votre mot de passe d'employé</b>";
					}else {
					echo "<b>To update this order, you need to type your employee password</b>";
					} ?>
                    <input name="redo_password" type="password" class="formField2" id="redo_password" value="" size="6" maxlength="6" max="6"></td>
                </tr>
				
			
                <tr>
                  <td colspan="8" align="center" bgcolor="#AAAAAA"><input name="Cancel" type="button" class="formField2" id="Cancel"
                  <?php if ($mylang == 'lang_french'){ 
                  	echo ' value="Annuler"';
				  }else{
				  	echo ' value="Cancel"';
				  }
				 ?>
                  
                   onClick="window.open('report.php', '_top')">			    
			  &nbsp;&nbsp;
			  <input name="Submit" type="submit" class="formField2" 
              <?php if ($mylang == 'lang_french'){ 
                  	echo ' value="Mettre à jour"';
				  }else{
				  	echo ' value="Update"';
				  }?>
              
              >
			  <input name="update_prescription" type="hidden" id="update_prescription" value="true">
			  <input name="order_product_id" type="hidden" id="pkey" value="<?php echo $listItem[order_product_id];?>">
              <input name="eye" type="hidden" id="eye" value="<?php echo $listItem[eye];?>">
			  <input name="pkey" type="hidden" id="pkey" value="<?php echo $_POST[pkey];?>"></td>
                </tr>
              </table>
			</form>
			</td></tr>
			<tr><td><div class="formField">
		<a href="display_order.php?order_num=<?php echo $listItem[order_num];?>&po_num=<?php echo $listItem[po_num];?>">
        <?php if ($mylang == 'lang_french'){ ?>
        <strong>Retour</strong>
        <?php }else{ ?>
        <strong>Back to Order</strong>
        <?php } ?>
        </a>
	</div></td>
  </tr>
</table></td>
    </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>