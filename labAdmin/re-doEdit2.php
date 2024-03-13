<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");

include("edit_order_functions.inc.php");
include("redo_order_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("../includes/calc_functions.inc.php");

include("../includes/est_ship_date_functions.inc.php");

$pkey=$_GET[pkey];

if ($_POST[update_redo]=="true"){
	$pkey=$_POST[pkey];
	$message="<b><font color=\"#FF0000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\"> - REDO ORDER UPDATED</font></b>";
	$_POST[update_redo]="";

	updateRedoOrder($_POST[pkey]);
	
}

$prescrQuery="select * from orders WHERE primary_key='$pkey'"; //get order's user id
$prescrResult=mysql_query($prescrQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$listItem=mysql_fetch_array($prescrResult);

$prodQuery="select * from exclusive WHERE primary_key='$listItem[order_product_id]'"; //get product info
$prodResult=mysql_query($prodQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$listProd=mysql_fetch_array($prodResult);

//CREAT OR UPDATE EST SHIPPING DATE

$new_est_ship_date=calculateEstShipDate($listItem['order_date_processed'],$listItem['order_product_id']);
addNewEstShipDate($new_est_ship_date,$listItem['primary_key'],$listItem['order_num'],$listItem['order_date_processed']);

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript" type="text/javascript">

function checkProduct(theForm)
{
	if(theForm.product_id.value==""){//NO Prod
		alert("NO PRODUCT SELECTED");
		return false;
	}

}
</script>

</head>
<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">EDIT
           		           REDO PRESCRIPTION ORDER</font></b><?php echo $message;?></td>
       		  </tr>
			<tr><td>
			<form action="re-doEdit.php" method="post" enctype="multipart/form-data" name="redoForm" id="redoForm" onSubmit="return checkProduct(this)">
			  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField2">
                <tr >
                  <td colspan="2" align="right" bgcolor="#FFFFFF">Redo Order Num:</td>
                  <td colspan="6" align="left" bgcolor="#FFFFFF"><?php echo $listItem[redo_order_num] ?></td>
                </tr>
                <tr >
                  <td colspan="2" align="right" bgcolor="#DDDDDD">This Order Num:</td>
                  <td colspan="6" align="left" bgcolor="#DDDDDD"><?php echo $listItem[order_num] ?></td>
                </tr>
                <tr >
                  <td colspan="2" align="right" bgcolor="#FFFFFF">P.O. Num: </td>
                  <td colspan="6" align="left" bgcolor="#FFFFFF"><?php echo $listItem[po_num] ?>
                  <input name="po_num" type="text" class="formField2" value="<?php echo $listItem[po_num];?>" size="20"></td>
                </tr>
                
                 <tr >
                  <td colspan="2" align="right" bgcolor="#FF0000"><b>Redo reason:</b></td>
                  <td colspan="6" align="left" bgcolor="#FF0000">  <select name="redo_reason_id" class="formField2" id="redo_reason_id">
                      
<?php

  		$queryRedo="select * from redo_reasons   	  ORDER by redo_reason_number"; /* select all openings */
		echo $queryRedo;
		$resultRedo=mysql_query($queryRedo)			or die ("Could not select items");
		
		 if ($mylang == 'lang_french'){
			echo "<option value=\"\">IMPORTANT, SELECTIONNER UNE RAISON POUR CE REDO</option>";
			}else {
			echo "<option value=\"\">IMPORTANT, SELECT AN REASON FOR THIS REDO</option>";
			}
		
		
		 while ($DataRedoReason=mysql_fetch_array($resultRedo)){
			 echo "<option value=\"$DataRedoReason[redo_reason_id]\"";
			 echo ">";
		 if ($mylang == 'lang_french'){
			$name=stripslashes($DataRedoReason[redo_reason_fr]);
			echo "$name</option>";
			}else {
			$name=stripslashes($DataRedoReason[redo_reason_en]);
			echo "$name</option>";
			}
		
		 }
			?>
 </select>
</td>
                </tr>
                
                
                <tr >
                  <td align="right" bgcolor="#aaaaaa"><b>Patient:</b></td>
                  <td colspan="2" align="center" bgcolor="#aaaaaa"><strong>First</strong></td>
                  <td colspan="2" align="center" bgcolor="#aaaaaa"><strong>Last</strong></td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Ref Number </strong></td>
                  <td colspan="2" align="center" bgcolor="#aaaaaa"><b>Salesperson
                      ID </b></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD">&nbsp;</td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="order_patient_first" type="text" class="formField2" id="order_patient_first" value="<?php echo $listItem[order_patient_first];?>" size="15"></td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="order_patient_last" type="text" class="formField2" id="order_patient_last" value="<?php echo $listItem[order_patient_last];?>" size="20"></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="patient_ref_num" type="text" class="formField2" id="patient_ref_num" value="<?php echo $listItem[patient_ref_num];?>" size="15"></td>
                  <td colspan="2" align="center" bgcolor="#DDDDDD">
                  
                  <select name="salesperson_id" class="formField2" id="salesperson_id">
                        <option value="" selected="selected"><?php echo $lbl_slsperson1;?></option>
<?php
  		$query="select sales_id,first_name,last_name from salespeople WHERE acct_user_id='$listItem[user_id]' AND removed!='Yes' ORDER by last_name,first_name"; /* select all openings */
		$result=mysql_query($query)
			or die ("Could not select items");
		 while ($saleslistItem=mysql_fetch_array($result)){
			 echo "<option value=\"$saleslistItem[sales_id]\"";
			 if ($listItem[salesperson_id]==$saleslistItem[sales_id]) echo " selected=\"selected\"";
			 echo ">";
			 $name=stripslashes($saleslistItem[first_name])." ".stripslashes($saleslistItem[last_name]);
			 echo "$name</option>";
			 }
 ?>
</select>
                      
			</td>
                </tr>
                <tr >
                  <td colspan="8" bgcolor="#555555"><font color="#FFFFFF">Product
                      - <?php
                    $prodQuery="SELECT * FROM exclusive WHERE prod_status='active'   order by index_v, product_name"; /* select all openings */
					$prodResult=mysql_query($prodQuery)	or die('Could not select items because: ' . mysql_error());
						//echo $listItem[order_product_id];
						?>
                      
                      <select name="product_id" class="formField2" id="product_id">
                        <option  value="">PRODUCT NO LONGER AVAILABLE</option>
		        <?php while ($listProducts=mysql_fetch_array($prodResult)){
				
					$name=stripslashes($listProducts[product_name]);
					if ($listProducts[primary_key]==$listItem[order_product_id]) {
					echo "<option value=\"$listProducts[primary_key]\" selected >" .  $name  . "</option>";
					}else{
					echo "<option value=\"$listProducts[primary_key]\" >" . $name  . "</option>";
					}	
					
				}?>
		        </select>
                
                      <div align="right" style="float:right">EYE:&nbsp;
                      <input name="eye" type="radio" value="Both"<?php if ($listItem[eye]=="Both") echo " checked"?> />
                      Both&nbsp;
                      <input name="eye" type="radio" value="R.E."<?php if ($listItem[eye]=="R.E.") echo " checked"?>/>
                      Right Only&nbsp;
                      <input name="eye" type="radio" value="L.E."<?php if ($listItem[eye]=="L.E.") echo " checked"?>/>
                     Left Only</div></font></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#FFFFFF"><strong>Material:</strong></td>
                  <td width="9%" bgcolor="#FFFFFF">
                    <select name="order_product_index" class="formField2">
                      <?php
 				$query="select index_v from exclusive group by index_v asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
 				while ($listItemIndex=mysql_fetch_array($result)){
				echo "<option value=\"$listItemIndex[index_v]\"";
				if ($listItemIndex[index_v]==$listItem[order_product_index]) echo "selected=\"selected\"";
				echo ">";
 				$name=stripslashes($listItemIndex[index_v]);
				echo "$name</option>";}?>
                    </select>
                </td>
                  <td width="12%" align="right" bgcolor="#FFFFFF" ><strong>Coating:</strong></td>
                  <td bgcolor="#FFFFFF"><select name="order_product_coating" class="formField2">
                      <?php 
    			$query="select coating from exclusive group by coating asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
				while ($listItemCoat=mysql_fetch_array($result)){
				echo "<option value=\"$listItemCoat[coating]\"";
				if ($listItemCoat[coating]==$listItem[order_product_coating]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemCoat[coating]);
				echo "$name</option>";}?>
                    </select>
                  </td>
                  <td align="right" bgcolor="#FFFFFF"><strong>Photochromatic:</strong></td>
                  <td align="left" bgcolor="#FFFFFF"><select name="order_product_photo" class="formField2">
                      <option value="None" <?php if ($listItem[order_product_photo]=="None") echo "selected=\"selected\"";?>>None</option>
                      <?php
  				$query="select photo from exclusive group by photo asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
 				while ($listItemPhoto=mysql_fetch_array($result)){
 				if ($listItemPhoto[photo]!="None"){
				echo "<option value=\"$listItemPhoto[photo]\"";
				if ($listItemPhoto[photo]==$listItem[order_product_photo]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemPhoto[photo]);
				echo "$name</option>";}}?>
                    </select>
                  </td>
                  <td align="right" bgcolor="#FFFFFF"><strong>Polarized:</strong></td>
                  <td align="left" bgcolor="#FFFFFF"><select name="order_product_polar" class="formField2">
                      <option value="None" <?php if ($listItem[order_product_polar]=="None") echo "selected=\"selected\"";?>>None</option>
                      <?php
  				$query="select polar from exclusive group by polar asc"; /* select all openings */
				$result=mysql_query($query)
					or die ("Could not select items");
 				while ($listItemPolar=mysql_fetch_array($result)){
 				if ($listItemPolar[polar]!="None"){
 				echo "<option value=\"$listItemPolar[polar]\"";
				if ($listItemPolar[polar]==$listItem[order_product_polar]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemPolar[polar]);
				echo "$name</option>";}}?>
                    </select>
               </td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#aaaaaa">&nbsp;</td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Sphere</strong></td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Cylinder</strong></td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Axis</strong></td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Addition</strong></td>
                  <td align="center" bgcolor="#aaaaaa"><strong>Prism</strong></td>
                  <td width="7%" align="center" bgcolor="#aaaaaa" class="formCellNosidesCenter"><strong>Quantity</strong></td>
                  <td width="15%" align="center" bgcolor="#aaaaaa"><strong>Price</strong></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD">R.E.</td>
                  <td align="center" bgcolor="#DDDDDD"><select name="re_sphere" class="formField2" id="re_sphere">
				  <?php
				  
				  $min=-15.75;
				  $max=14.75;
				  
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
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  } ?>
                    </select></td>
				  
                  <td align="center" bgcolor="#DDDDDD"><select name="re_cyl" class="formField2" id="re_cyl">
				  <?php
				  
				  $min=-8.75;
				  $max=6.75;
				  
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
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  } ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="re_axis" type="text" class="formField2" id="re_axis" value="<?php	if ($listItem[re_axis]>0)
				 echo $listItem[re_axis];?>" size="4" maxlength="4"></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="re_add" class="formField2" id="re_add">
				  <?php
				  
				  $max=3.5;
				  $min=0;

				  
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
					
						echo ">$val</option>";
				  		}
				   }//END L.E. CONDITIONAL
				  else{
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  } ?>
                    </select></td>
                  <td align="right" bgcolor="#DDDDDD">
                    <input name="RE_PR_IO" type="radio" value="In" <?php if ($listItem[re_pr_io]=='In') echo 'checked="checked"';?>/>In&nbsp;
  <input name="RE_PR_IO" type="radio" value="Out"<?php if ($listItem[re_pr_io]=='Out') echo 'checked="checked"';?> />
                    Out <input name="RE_PR_IO" type="radio" value="None"<?php if ($listItem[re_pr_io]=='None') echo 'checked="checked"';?> />
                    None
&nbsp;&nbsp;<input name="re_pr_ax" type="text" class="formField2" id="re_pr_ax" value="<?php	if ($listItem[re_pr_ax]>0)
				 echo $listItem[re_pr_ax];?>" size="3" maxlength="3"><br><input name="RE_PR_UD" type="radio" value="Up" <?php if ($listItem[re_pr_ud]=='Up') echo 'checked="checked"';?>/>Up&nbsp;
<input name="RE_PR_UD" type="radio" value="Down" <?php if ($listItem[re_pr_ud]=='Down') echo 'checked="checked"';?>/>
Down 
<input name="RE_PR_UD" type="radio" value="None" <?php if ($listItem[re_pr_ud]=='None') echo 'checked="checked"';?>/> 
None
<input name="re_pr_ax2" type="text" class="formField2" id="re_pr_ax2" value="<?php	if ($listItem[re_pr_ax2]>0)
				 echo $listItem[re_pr_ax2];?>" size="3" maxlength="3"></td><td rowspan="7" align="center" valign="top" class="formCellNosidesCenter"><input name="order_quantity" type="text" class="formField2" id="order_quantity" value="<?php echo $listItem[order_quantity];?>" size="4" maxlength="4"></td>
                  <td rowspan="7" align="right" valign="top"><b>$ <?php echo $listItem[order_product_price];?></b><?php 
					  
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
                    Subtotal: <b><?php echo $itemSubtotal;?></b></td>
                </tr>
                <tr >
                  <td colspan="6" align="left"><strong>Dist.
                    PD:
                      <input name="re_pd" type="text" class="formField2" id="re_pd" value="<?php	if ($listItem[re_pd]>0)
				 echo $listItem[re_pd];?>"size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;<strong>Near
                      PD:</strong>
                  <input name="re_pd_near" type="text" class="formField2" id="re_pd_near" value="<?php	if ($listItem[re_pd_near]>0)
				 echo $listItem[re_pd_near];?>" size="4" maxlength="4">                  &nbsp;&nbsp;&nbsp;<strong>Height:</strong>
                  <input name="re_height" type="text" class="formField2" id="re_height" value="<?php	if ($listItem[re_height]>0)
				 echo $listItem[re_height];?>" size="4" maxlength="4"></td>
                </tr>
                <tr >
                  <td align="right" bgcolor="#DDDDDD"> L.E.</td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_sphere" class="formField2" id="le_sphere">
				  <?php
				  
				  $min=-15.75;
				  $max=14.75;
				  
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
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  }
				  
				  ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_cyl" class="formfield2" id="le_cyl">
				  <?php
				  
				  $min=-8.75;
				  $max=6.75;
				  
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
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  }
				   ?>
                    </select></td>
                  <td align="center" bgcolor="#DDDDDD"><input name="le_axis" type="text" class="formField2" id="le_axis" value="<?php	if ($listItem[le_axis]>0)
				 echo $listItem[le_axis];?>"  size="4" maxlength="4"></td>
                  <td align="center" bgcolor="#DDDDDD"><select name="le_add" class="formField2" id="le_add">
				  <?php
				  
				  $max=3.5;
				  $min=0;
				  
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
				  
				  echo "<option value=\"-\" selected=\"selected\">-</option>";
				  }?>
                    </select></td>
                  <td align="right" bgcolor="#DDDDDD">
                    <input name="LE_PR_IO" type="radio" value="In" <?php if ($listItem[le_pr_io]=='In') echo 'checked="checked"';?>/>
                    In&nbsp;
  <input name="LE_PR_IO" type="radio" value="Out" <?php if ($listItem[le_pr_io]=='Out') echo 'checked="checked"';?>/>
                    Out 
                    <input name="LE_PR_IO" type="radio" value="None" <?php if ($listItem[le_pr_io]=='None') echo 'checked="checked"';?>/>
                    None 
                  <input name="le_pr_ax" type="text" class="formField2" id="le_pr_ax" value="<?php	if ($listItem[le_pr_ax]>0)
				 echo $listItem[le_pr_ax];?>" size="3" maxlength="3"><br><input name="LE_PR_UD" type="radio" value="Up" <?php if ($listItem[le_pr_ud]=='Up') echo 'checked="checked"';?>/>Up&nbsp;<input name="LE_PR_UD" type="radio" value="Down" <?php if ($listItem[le_pr_ud]=='Down') echo 'checked="checked"';?>/>
                  Down 
                  <input name="LE_PR_UD" type="radio" value="None" <?php if ($listItem[le_pr_ud]=='None') echo 'checked="checked"';?>/>Down&nbsp; <input name="le_pr_ax2" type="text" class="formField2" id="le_pr_ax2" value="<?php	if ($listItem[le_pr_ax2]>0)
				 echo $listItem[le_pr_ax2];?>" size="3" maxlength="3"></td></tr>
                <tr >
                  <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>Dist.
                    PD:
                      <input name="le_pd" type="text" class="formField2" id="le_pd" value="<?php echo $listItem[le_pd];?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;<strong>Near
                      PD:
                  <input name="le_pd_near" type="text" class="formField2" id="le_pd_near" value="<?php echo $listItem[le_pd_near];?>" size="4" maxlength="4">
                  </strong>&nbsp;&nbsp;&nbsp;<strong>Height:</strong>
                  <input name="le_height" type="text" class="formField2" id="le_height" value="<?php	if ($listItem[le_height]>0)
				 echo $listItem[le_height];?>" size="4" maxlength="4"></td>
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
                  <td colspan="2" align="right" bgcolor="#FFFFFF"><b>Special
                      Instructions :</b></td>
                  <td colspan="4" align="left" bgcolor="#FFFFFF"><input name="special_instructions" type="text" class="formField2" id="special_instructions" value="<?php echo $listItem[special_instructions];?>" size="40"></td>
                </tr>
                <tr >
                  <td colspan="2" align="right" bgcolor="#DDDDDD"><b>Additional
                      Item:</b></td>
                  <td colspan="4" align="left" bgcolor="#DDDDDD"><input name="extra_product" type="text" class="formField2" id="extra_product" value="<?php echo $listItem[extra_product];?>" size="40">                    
                    <b>&nbsp;&nbsp;&nbsp;Amount:$</b>                    <input name="extra_product_price" type="text" class="formField2" id="extra_product_price" value="<?php echo $listItem[extra_product_price];?>" size="4"></td>
                </tr>
                <tr >
                  <td colspan="8" bgcolor="#AAAAAA"><strong>EXTRA PRODUCTS</strong></td>
                </tr>
                <tr >
                  <td colspan="8" align="left" bgcolor="#FFFFFF">ENGRAVING:
                 <?php 
				  
				$query="SELECT engraving FROM extra_product_orders WHERE category='Engraving' AND order_num='$listItem[order_num]'";
				$engravingResult=mysql_query($query)
							or die  ('I cannot select items because: ' . mysql_error());
				$engravingItem=mysql_fetch_array($engravingResult);
				  ?>
                  <input name="engraving" type="text" class="formField2" id="order_patient_first2" value="<?php echo $engravingItem[engraving];?>" size="4" maxlength="2"></td>
                </tr>
                <tr >
                  <td colspan="8" align="left" bgcolor="#DDDDDD">TINT:
                   <?php 
				  
				$query="SELECT tint,tint_color,from_perc,to_perc FROM extra_product_orders WHERE category='Tint' AND order_num='$listItem[order_num]'";
				$tintResult=mysql_query($query)
							or die  ('I cannot select items because: ' . mysql_error());
				$tintItem=mysql_fetch_array($tintResult);
				  ?>
                   <select name="tint" class="formField2">
                     <option value="None" >None</option>
                     <option value="Solid" <?php if ($tintItem[tint]=="Solid") echo "selected=\"selected\"";?>>Solid</option>
                     <option value="Gradient" <?php if ($tintItem[tint]=="Gradient") echo "selected=\"selected\"";?>>Gradient</option>
                   </select>
                      From 
                   <input name="from_perc" type="text" class="formField2" value="<?php echo $tintItem[from_perc];?>" size="4" maxlength="4" />
%     To
<input name="to_perc" type="text" class="formField2" value="<?php echo $tintItem[to_perc];?>" size="4" maxlength="4">
%         
<select name="tint_color" class="formField2">
  <option value="None" >None</option>
  <option value="Brown"<?php if ($tintItem[tint_color]=="Brown") echo "selected=\"selected\"";?>>Brown</option>
  <option value="Gray"<?php if ($tintItem[tint_color]=="Gray") echo "selected=\"selected\"";?>>Gray</option>
</select>
</td>
                </tr>
                <tr >
                  <td colspan="8" align="left" bgcolor="#FFFFFF">EDGING:
                  <?php 
				  
				$query="SELECT job_type FROM extra_product_orders WHERE category='Edging' AND order_num='$listItem[order_num]'";
				$edgingResult=mysql_query($query)
							or die  ('I cannot select items because: ' . mysql_error());
				$edgingItem=mysql_fetch_array($edgingResult);
				  ?>Job Type 
                  <select name="job_type" class="formField2">
                    <option value="Uncut">Uncut</option>
                    <option value="Edge and Mount"<?php if ($edgingItem[job_type]=="Edge and Mount") echo "selected=\"selected\"";?>>Edge and Mount</option>
                  </select>
                  </td>
                </tr>
                <tr >
                  <td colspan="8" align="left" bgcolor="#B8B8B8">FRAME:&nbsp;<?php 
				  
				$query="SELECT * FROM extra_product_orders WHERE category='Frame' AND order_num='$listItem[order_num]'";
				$frameResult=mysql_query($query)
							or die  ('I cannot select items because: ' . mysql_error());
				$frameItem=mysql_fetch_array($frameResult);
				
				  ?>
                  <select name="order_type" class="formField2" id="order_type">
                  <option value="None">None</option>
                      <option value="Provide"<?php if ($frameItem[order_type]=="Provide") echo "selected=\"selected\"";?>>Provide</option>
                      <option value="To Follow"<?php if ($frameItem[order_type]=="To Follow") echo "selected=\"selected\"";?>>To Follow</option>
                  </select>
&nbsp;&nbsp;</td>
                </tr>
                <tr >
                  <td colspan="8" align="left" bgcolor="#FFFFFF">Eye: A: 
                  
                  <?php if  ($frameItem[ep_frame_a]=='') {
				 
				  $QueryFrameOrder = "SELECT frame_a,frame_b,frame_ed,frame_dbl FROM ORDERS WHERE order_num ='$listItem[order_num]'";
				  $FrameOrderResult=mysql_query($QueryFrameOrder)		or die  ('I cannot select items because: ' . mysql_error());
				  $DataFrame=mysql_fetch_array($FrameOrderResult);
				 
				 
				  $Frame_A   = $DataFrame['frame_a'];
				  $Frame_B   = $DataFrame['frame_b'];
				  $Frame_ED  = $DataFrame['frame_ed'];
				  $Frame_DBL = $DataFrame['frame_dbl'];
				   }else {
				  $Frame_A   = $frameItem['ep_frame_a'];
				  $Frame_B   = $frameItem['ep_frame_b'];
				  $Frame_ED  = $frameItem['ep_frame_ed'];
				  $Frame_DBL = $frameItem['ep_frame_dbl'];
				   }

				  ?>
                    <input name="frame_a" type="text" class="formField2" id="frame_a" value="<?php echo $Frame_A;?>" size="4" maxlength="4" >
&nbsp;&nbsp;&nbsp;B:
<input name="frame_b" type="text" class="formField2" id="frame_b" value="<?php echo $Frame_B;?>" size="4" maxlength="4" >
&nbsp;&nbsp;&nbsp;ED:
<input name="frame_ed" type="text" class="formField2" id="frame_ed" value="<?php echo $Frame_ED ;?>" size="4" maxlength="4">
&nbsp;&nbsp;&nbsp;DBL: &nbsp;
<input name="frame_dbl" type="text" class="formField2" id="frame_dbl" value="<?php echo $Frame_DBL;?>" size="4" maxlength="4">
&nbsp;&nbsp;Temple:
<input name="temple" type="text" class="formField2" id="temple" value="0" size="4" />
Type:
<select name="frame_type" class="formField2" id="frame_type" >
  <option value="Nylon Groove"<?php if ($listItem['frame_type']=="Nylon Groove") echo "selected=\"selected\"";?>>Nylon Groove</option>
  <option value="Metal Groove"<?php if ($listItem['frame_type']=="Metal Groove") echo "selected=\"selected\"";?>>Metal Groove</option>
  <option value="Plastic"<?php if ($listItem['frame_type']=="Plastic") echo "selected=\"selected\"";?>>Plastic</option>
  <option value="Metal"<?php if ($listItem['frame_type']=="Metal") echo "selected=\"selected\"";?>>Metal</option>
  <option value="Edge Polish"<?php if ($listItem['frame_type']=="Edge Polish") echo "selected=\"selected\"";?>>Edge Polish</option>
  <option value="Drill and Notch"<?php if ($listItem['frame_type']=="Drill and Notch") echo "selected=\"selected\"";?>>Drill and Notch</option>
</select>
</td>
</tr>
<tr >
                  <td colspan="8" align="center" bgcolor="#DDDDDD"> 
                  Supplier:
                      <input name="supplier" type="text" class="formField2" value="<?php echo $frameItem[supplier];?>" size="12"/>
&nbsp;&nbsp;Shape Model:
<select name="model" class="formField2">
  <?php 
    			$modelquery="select model_num from frames group by model_num asc"; /* select all openings */
				$modelresult=mysql_query($modelquery)
					or die ("Could not select items");
				while ($listItemModel=mysql_fetch_array($modelresult)){
				echo "<option value=\"$listItemModel[model_num]\"";
				if ($listItemModel[model_num]==$frameItem[model]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemModel[model_num]);
				echo "$name</option>";}?>
</select>
&nbsp;&nbsp;&nbsp;Frame Model:
                  <select name="temple_model_num" class="formField2" id="temple_model_num">
                    <?php 
    			$templequery="select collection_code from frames_colors group by collection_code"; /* select all openings */
				$templeresult=mysql_query($templequery)
					or die ("Could not select items");
				while ($listItemTemple=mysql_fetch_array($templeresult)){
				echo "<option value=\"$listItemTemple[collection_code]\"";
				if ($listItemTemple[collection_code]==$frameItem[temple_model_num]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemTemple[collection_code]);
				echo "$name</option>";}?>
                  </select>
                  &nbsp;&nbsp;&nbsp;Color:
<select name="color" class="formField2">
  <?php 
    			$colorquery="select frame_color from frames_colors group by frame_color"; /* select all openings */
				$colorresult=mysql_query($colorquery)
					or die ("Could not select items");
				while ($listItemColor=mysql_fetch_array($colorresult)){
				echo "<option value=\"$listItemColor[frame_color]\"";
				if ($listItemColor[frame_color]==$frameItem[color]) echo "selected=\"selected\"";
				echo ">";
				$name=stripslashes($listItemColor[frame_color]);
				echo "$name</option>";}?>
</select></td>
                </tr>
                <tr >
                  <td colspan="8" align="center" bgcolor="#AAAAAA"><input name="Cancel" type="button" class="formField2" id="Cancel" value="Cancel" onClick="window.open('report.php', '_top')">			    
			  &nbsp;&nbsp;
			  <input name="Submit" type="submit" class="formField2" value="Update">
			  <input name="update_redo" type="hidden" value="true">
			  <input name="order_num" type="hidden" value="<?php echo $listItem[order_num] ?>">
			  <input name="user_id" type="hidden" value="<?php echo $listItem[user_id];?>">
			  <input name="pkey" type="hidden" value="<?php echo $pkey;?>"></td>
                </tr>
              </table>
			</form>
			</td></tr>
			<tr><td><div class="formField">
		<a href="display_order.php?order_num=<?php echo $listItem[order_num];?>&po_num=<?php echo $listItem[po_num];?>">Back to Order</a>
	</div></td>
  </tr>
</table></td>
    </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>

