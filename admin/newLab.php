<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$heading="ADMIN NEW LAB FORM";
if($_SESSION[formVars])
	$heading="ADMIN NEW LAB FORM - User ID exists, please try a different User ID";

if ($_POST[editLab] == "Edit Lab"){
	$pkey = $_POST[pkey];
	edit_lab($pkey);
}
if ($_POST[deleteLab] == "Delete Lab"){
	$pkey = $_POST[pkey];
	delete_lab($pkey);
}
if($_POST[lab])
	$pkey=$_POST[lab];
	
$query="select * from labs where primary_key = '$pkey'";
$labResult=mysql_query($query)
	or die ("Could not find lab");
$labData=mysql_fetch_array($labResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("lab_name", "address1", "city", "zip", "phone", "email");
	// field description to appear in the dialog box
	var fieldDescription = Array("Lab Name", "Address 1", "City", "Zip", "Phone", "Email");
	// dialog message
	var alertMsg = "Please enter:\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "select-one":
				if (obj.selectedIndex == "" || obj.options[obj.selectedIndex].text == ""){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "select-multiple":
				if (obj.selectedIndex == -1){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			default:
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length != l_Msg){
		alert(alertMsg);
		return false;
	}else{
	var goodEmail=document.form3.email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.biz)|(\..{2,2}))$)\b/gi);
	if (!goodEmail){
		var emailMsg="The email address you've entered doesn't appear to be valid. \nPlease edit the email field and resubmit.\n";
		alert(emailMsg);
		return false;
		}	
	}
}
// -->
</script>
<style type="text/css">
<!--
.style1 {font-size: 7px}
-->
</style>
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%"><form name="form3" method="post" action="getLab.php" onSubmit="return formCheck(this);" class="formField">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php print $heading; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Lab
						Name						
					</div></td>
					<td align="left">
						<input name="lab_name" type="text" id="lab_name" size="20" class="formField" value="<?php print $_SESSION["formVars"]["lab_name"]; ?>">					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Country 
					</div></td>
					<td colspan="3" align="left">
						<select name = "country" id="country" class="formField">
							<option value = "CA" <?php if($_SESSION["formVars"]["country"]=="CA") print " selected"; ?>>Canada</option>
							<option value = "IT" <?php if($_SESSION["formVars"]["country"]=="IT") print " selected"; ?>>Italy</option>
							<option value = "US" <?php if($_SESSION["formVars"]["country"]=="US") print " selected"; ?>>United
							States</option>
						</select>					</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right">
												Address 1						
					</div></td>
					<td align="left">
						<input name="address1" type="text" id="address1" size="20" class="formField" value="<?php print $_SESSION["formVars"]["address1"]; ?>">					</td>
					<td align="left" nowrap><div align="right">
												Address 2						
					</div></td>
					<td colspan="3" align="left">
						<input name="address2" type="text" id="address2" size="20" class="formField" value="<?php print $_SESSION["formVars"]["address2"]; ?>">					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												City						
					</div></td>
					<td align="left">
						<input name="city" type="text" id="city" size="20" class="formField" value="<?php print $_SESSION["formVars"]["city"]; ?>">					</td>
					<td align="left" nowrap><div align="right">
												State/Province						
					</div></td>
					<td align="left"><input name="state" type="text" id="state" size="20" class="formField" value="<?php print $_SESSION["formVars"]["state"]; ?>"></td>
					<td align="left"><div align="right">
						 Zip/Postal Code 
					</div></td>
					<td align="left">
						<input name="zip" type="text" id="zip" size="10" class="formField" value="<?php print $_SESSION["formVars"]["zip"]; ?>" />					</td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
												Phone						
					</div></td>
					<td align="left">
						<input name="phone" type="text" id="phone" size="20" class="formField" value="<?php print $_SESSION["formVars"]["phone"]; ?>">					</td>
					<td align="left" nowrap><div align="right">
												Tollfree
						Phone						
					</div></td>
					<td align="left">
						<input name="tollfree_phone" type="text" id="tollfree_phone" size="20" class="formField" value="<?php print $_SESSION["formVars"]["tollfree_phone"]; ?>">					</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Fax						
					</div></td>
					<td align="left">
						<input name="fax" type="text" id="fax" size="20" class="formField" value="<?php print $_SESSION["formVars"]["fax"]; ?>">					</td>
					<td align="left" nowrap><div align="right">
						Tollfree
						Fax 												
					</div></td>
					<td align="left">
						<input name="tollfree_fax" type="text" id="tollfree_fax" size="20" class="formField" value="<?php print $_SESSION["formVars"]["tollfree_fax"]; ?>">					</td>
					<td align="left"><div align="right">
						Fax Order Notification:<br>
	<span class="style1">fax number required</span>
					</div></td>
					<td align="left"><input name="fax_notify" type="checkbox" value="yes" <?php if($_SESSION["formVars"]["fax_notify"]=="yes") print " checked"; ?>></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
												Email						
					</div></td>
					<td align="left">
						<input name="email" type="text" id="email" size="35" class="formField" value="<?php print $_SESSION["formVars"]["email"]; ?>">
							
						<div align="right">						</div></td>
					<td align="left"><div align="right">Buying Level</div></td>
					<td align="left"><select name="buying_level" class="formField" id="buying_level">
					  <?php
	$query="select buying_level from buying_levels";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
	print "<option value=\"$labList[buying_level]\""; if($labData["buying_level"]==$labList[buying_level]) print " selected"; print ">$labList[buying_level]</option>";
}
?>
					  </select></td>
					<td align="left"><div align="right">
						Stock Shipping Charge
					</div></td>
					<td align="left"><input name="ship_chg_stock" type="text" id="ship_chg_stock" size="6" value="<?php print $_SESSION["formVars"]["ship_chg_stock"]; ?>" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right"> Late Notifications Emails </div></td>
				  <td colspan="2" align="left"><input name="notification_email" type="text" id="notification_email" size="60" value="<?php print $_SESSION["formVars"]["notification_email"]; ?>" class="formField" />
				    <div align="right"> </div></td>
				  <td><div align="right">Reports Email</div></td>
				  <td colspan="2" align="left"><input name="reports_email" type="text" id="reports_email" size="35" value="<?php print $_SESSION["formVars"]["reports_email"]; ?>" class="formField" /></td>
			  </tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" bgcolor="#FFFFFF" ><div align="right">
						Login
					</div></td>
					<td align="left" bgcolor="#FFFFFF"><input name="username" type="text" id="username" size="20" class="formField">					</td>
					<td align="left" nowrap bgcolor="#FFFFFF"><div align="right">
						Password
					</div></td>
					<td align="left" bgcolor="#FFFFFF"><input name="password" type="text" id="password" size="20" class="formField">					</td>
					<td align="left" bgcolor="#FFFFFF"><div align="right">
						RX Shipping Charge
					</div></td>
					<td align="left" bgcolor="#FFFFFF"><input name="ship_chg_rx" type="text" id="ship_chg_rx" size="6" value="<?php print $_SESSION["formVars"]["ship_chg_rx"]; ?>" class="formField" /></td>
				</tr>
			
            
            
            		   
                    
                      <tr><td>&nbsp;</td></tr>     
                    
				<tr bgcolor="#FFFFFF">
					<td align="left" bgcolor="#DDDDDD" ><div align="right">
						<b>Lab Prefs</b></font>
					</div></td>
					<td align="left" bgcolor="#DDDDDD">&nbsp;</td>
					<td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right">
					<?php	echo 'Precision/Vision Pro' ;?>
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><select name="precision_vp_lab" class="formField" id="precision_vp_lab">
							  <option   value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["precision_vp_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
					</select></td>
					<td align="left" bgcolor="#DDDDDD"><div align="right">
						<?php	echo 'Infocus' ;?>
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><select name="infocus_lab" class="formField" id="infocus_lab">
							  <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["infocus_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
					</select></td>
				</tr>
                
                
				
				
				
				<tr bgcolor="#DDDDDD">
                  <td align="left" bgcolor="#FFFFFF" ><div align="right"> 
				  	  <?php	echo 'My World' ;?></div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="innovative_lab" class="formField" id="innovative_lab">
                        <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovative_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><div align="right"> 
                   	   <?php	echo 'Vision Pro Poly' ;?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="visionpropoly_lab" id="visionpropoly_lab" class="formField">
                        <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["visionpropoly_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" bgcolor="#FFFFFF"><div align="right"> 
				  		<?php	echo 'Generation' ;?> </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="generation_lab" id="generation_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["generation_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		<?php	echo 'TrueHd' ;?>
                    </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="truehd_lab" id="truehd_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["truehd_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right"> 
                 	    <?php	echo 'Easy Fit Hd' ;?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="easy_fit_lab" id="easy_fit_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["easy_fit_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                                    </select></td>
				  <td align="left" bgcolor="#DDDDDD"><div align="right">  
				  		<?php	echo 'Other' ;?> </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="other_lab" id="other_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["other_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
                  </select></td>
			  </tr>
              
              <tr >
				  <td align="left"><div align="right">
				  		<?php	echo 'Vot' ;?> </div>
                  </td>
				  <td align="left"><select name="vot_lab" id="vot_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["vot_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
				 
				  <td align="left"><div align="right">
				 		<?php	echo 'Eco' ;?> </div></div></td>
				  <td align="left"><select name="eco_lab" id="eco_lab" class="formField">
				     <option value="">Select a Lab</option> <?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
				  <td align="left" bgcolor="#FFFFFF" ><div align="right"> 
                 		<?php	echo 'Vision Eco' ;?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="visioneco_lab" id="visioneco_lab" class="formField">
                  <option value="">Select a Lab</option>
				    <?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["visioneco_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
                
				
              
              
				<tr bgcolor="#DDDDDD">
                
                 <td align="left" nowrap="nowrap"><div align="right">
                 	    <?php	echo 'Glass' ;?></div></td>
				  <td align="left"><select name="glass_lab" id="glass_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["glass_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right"> 
                      	<?php	echo 'Glass 2' ;?></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="glass_2_lab" id="glass_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["glass_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                     <td align="left"><div align="right">
					 	<?php	echo 'Glass 3' ;?></div></td>
				  <td align="left"><select name="glass_3_lab" id="glass_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["glass_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				
			  </tr>
              
              
              <tr  bgcolor="#FFFFFF">
				
				  <td bgcolor="#FFFFFF" align="left"><div align="right">
              	       <?php	echo 'Rodenstock' ;?>
                  </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="rodenstock_lab" id="rodenstock_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["rodenstock_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				 <td bgcolor="#FFFFFF" align="left"><div align="right">
               		   <?php	echo 'Rodenstock HD' ;?>
                 </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="rodenstock_hd_lab" id="rodenstock_hd_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["rodenstock_hd_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                     <td bgcolor="#FFFFFF" align="left"><div align="right">
                   	  <?php	echo 'Innovation FF' ;?>
                   </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="innovation_ff_lab" id="innovation_ff_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
              
              
			  
              <tr bgcolor="#DDDDDD">
                
                 <td align="left" nowrap="nowrap"><div align="right">
               		   <?php	echo 'Innovation FF HD' ;?>
                 </div></td>
				  <td align="left"><select name="innovation_ff_hd_lab" id="innovation_ff_hd_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_hd_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">
                       <?php	echo 'Innovation DS' ;?>
                     </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="innovation_ds_lab" id="innovation_ds_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovation_ds_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                     <td align="left"><div align="right">
                      <?php	echo 'Innovation II DS' ;?>
                     </div></td>
				  <td align="left"><select name="innovation_ii_ds_lab" id="innovation_ii_ds_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovation_ii_ds_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				
			  </tr>
              
              <tr>
                  <td align="left"><div align="right">
                       <?php	echo 'Svision' ;?>
                  </div></td>
				  <td align="left"><select name="svision_lab" id="svision_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["svision_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                       <td align="left"><div align="right">
                        <?php	echo 'Svision 2' ;?>
                       </div></td>
				  <td align="left"><select name="svision_2_lab" id="svision_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["svision_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                       <td align="left"><div align="right">
                       <?php	echo 'Svision 3' ;?>
                       </div></td>
				  <td align="left"><select name="svision_3_lab" id="svision_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["svision_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                    
              </tr>
              
              
              
              
              
                 
              <tr>
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
                  		<?php	echo 'Conant' ;?>
                  </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="conant_lab" id="conant_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["conant_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                       <td bgcolor="#DDDDDD"  align="left"><div align="right">
                      <?php	echo 'Optovision' ;?>
                       </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="optovision_lab" id="optovision_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["optovision_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                    
                       
                      <td bgcolor="#DDDDDD"  align="left"><div align="right">
                       <?php	echo 'Selection Rx' ;?>
                      </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="selection_rx_lab" id="selection_rx_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["selection_rx_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
              </tr>
              
              
              
                    <tr><td>&nbsp;</td></tr>     
              
              
              
              
              
				<tr bgcolor="#DDDDDD">
				<td align="left" bgcolor="#DDDDDD" ><div align="right">
						<b>Privates Collections</b>
					</div></td>
					<td align="left" bgcolor="#DDDDDD">&nbsp;</td>
                
                
				  <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right">
                 	  <?php	echo 'Private 1 / Grm Swiss' ;?>
                 </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_1_lab" id="private_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_1_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td align="left" bgcolor="#DDDDDD"><div align="right">
                   	<?php	echo 'Private 2 / Grm HKO' ;?>
                 </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_2_lab" id="private_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                
			  </tr>
			  
			  
			  
			  
			  
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 	  <?php	echo 'Private 3 / Easy One' ;?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_3_lab" id="private_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				   <td align="left" bgcolor="#FFFFFF" ><div align="right">
                   	 <?php	echo 'Private 4 /Essilor' ;?>
                   </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_4_lab" id="private_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_4_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
					
				 <td align="left" bgcolor="#FFFFFF" ><div align="right">
                	    <?php	echo 'Private 5 / Copie de Other' ;?>
                </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_5_lab" id="private_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_5_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
              
              
              
              
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                    	 <?php	echo 'Private 6 / GRM Drummond' ;?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_6_lab" id="private_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_6_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				   <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		 <?php	echo 'Private 7 / GRM SCT' ;?>
                   </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_7_lab" id="private_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_7_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
					
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		 <?php	echo 'Private 8 /GRM Conant' ;?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_8_lab" id="private_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_8_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
			  
              
                   
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 		 <?php	echo 'Nesp' ;?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="nesp_lab" id="nesp_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["nesp_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
       
       
       
       
       <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					 <?php	echo 'Private Grm 1 (Nesp)' ;?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_grm_1_lab" id="private_grm_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_grm_1_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
                    
                    
                    
					
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 		 <?php	echo 'Private Grm 2 (Infinity)' ;?>
                  </b></div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_grm_2_lab" id="private_grm_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_grm_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
			<tr>
            
            
            
              <td align="left" bgcolor="#DDDDDD" ><div align="right">
            		  <?php	echo 'Private Grm 3' ;?>
             </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_grm_3_lab" id="private_grm_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["private_grm_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
            
                
			  <tr><td>&nbsp;</td></tr>   
                <tr><td>&nbsp;</td></tr>   
			   <tr><th>Lensnet Club</th></tr>
	       
           
           
			  <tr >
				  <td bgcolor="#DDDDDD" align="left"><div align="right">
                  	   <?php	echo 'Eco 1 / HKO' ;?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_1_lab" id="eco_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_1_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
                		 <?php	echo 'Eco 2 / Conant OMC' ;?>
                  </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_2_lab" id="eco_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
				      
				 <td  bgcolor="#DDDDDD"align="left"><div align="right">
                 		 <?php	echo 'Eco 3 / Swiss' ;?>
                 </div></td>
				  <td  bgcolor="#DDDDDD"align="left"><select  name="eco_3_lab" id="eco_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
			 
				  
				<tr >
				 
                   <td align="left"><div align="right">
                 	    <?php	echo 'Eco 4 / DR' ;?>
                   </div></td>
				  <td align="left"><select  name="eco_4_lab" id="eco_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_4_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>   
                    
				 <td align="left"><div align="right">
                	    <?php	echo 'Eco 5 / Vot' ;?>
                 </div></td>
				  <td align="left"><select  name="eco_5_lab" id="eco_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_5_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
				      
				 <td align="left"><div align="right">
                		 <?php	echo 'Eco 6 / Local' ;?>
                 </div></td>
				  <td align="left"><select name="eco_6_lab" id="eco_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_6_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
              
              
              
              
              
              <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
               		   <?php	echo 'Eco 7 Kodak HKO' ;?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_7_lab" id="eco_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_7_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
                		 <?php	echo 'Eco OR / Infinissima' ;?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_or_lab" id="eco_or_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_or_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
                      <?php	echo 'Eco Conant / Shanghai' ;?>
                     </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_conant_lab" id="eco_conant_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_conant_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>                  
			  </tr>
			   

                 <tr >			      
				 <td align="left"><div align="right">
            		    <?php	echo 'Eco 8 / SCT' ;?>
                </div></td>
				  <td align="left"><select  name="eco_8_lab" id="eco_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_8_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                        
					 <td align="left"><div align="right">
                   	    <?php	echo 'Eco 9/ Eco Private Brantford (godin33club)' ;?>
                     </div></td>
				  <td align="left"><select  name="eco_9_lab" id="eco_9_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_9_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                        
                       <td   align="left"><div align="right"><a style="text-decoration:none;" title="Cree pour permettde d emettre des coupons uniquement sur cette collection" href="#">
                      <?php	echo 'Innovative +' ;?>
                       </a></div></td>
				  <td align="left"><select name="innovative_plus_lab" id="innovative_plus_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["innovative_plus_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
              
              
              
              
              
                          <tr >			      
				 <td bgcolor="#DDDDDD"  align="left"><div align="right">
                	<?php	echo 'Eco 10 (Natural)' ;?>
                 </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="eco_10_lab" id="eco_10_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["eco_10_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                        
				<td  bgcolor="#DDDDDD" align="left"><div align="right">&nbsp;</div></td>
				<td  bgcolor="#DDDDDD" align="left">&nbsp;</td>
                    

                        
                   <td  bgcolor="#DDDDDD"   align="left"><div align="right">&nbsp;</div></td>
				  <td  bgcolor="#DDDDDD" align="left">&nbsp;</td>
                  
                  
			  </tr>
              
              
              
              <tr><td>&nbsp;</td></tr>
               <tr><td>&nbsp;</td></tr>
                  <tr><th>MydenClub</th></tr>
                     
                 <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Infinysim  =  Swiss" href="#">
                	<?php echo 'Den 1';?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="den_1_lab" id="den_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_1_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Econo FF, Pro EZ, Sola One =  HKO" href="#">
						<?php echo 'Den 2';?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="den_2_lab" id="den_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="My Den FF Active =  Swiss" href="#">
                    	<?php echo 'Den 3';?>
                    </a></div></td>
				  <td  bgcolor="#DDDDDD" align="left"><select  name="den_3_lab" id="den_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td align="left">&nbsp;&nbsp;</td>
                  
                  
			  </tr>
              
              
                  <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="Identity by Optotech =  HKO" href="#">
                		<?php echo 'Den 4';?>
                 </a></div></td>
				  <td align="left"><select  name="den_4_lab" id="den_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_4_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right">
                    	<?php echo 'Den 5';?>
                     </div></td>
				  <td align="left"><select name="den_5_lab" id="den_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_5_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
				  
				        
					 <td align="left"><div align="right">
                     	<?php echo 'Den 6';?>
                     </div></td>
				  <td align="left"><select name="den_6_lab" id="den_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["den_6_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
			  </tr>
              
              
              
              
              
              
              <tr><td>&nbsp;</td></tr>
               <tr><td>&nbsp;</td></tr>
                  <tr><th>BBG</th></tr>
                     
                 <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="My World = Swiss" href="#">
                 		 <?php echo 'Bbg 1';?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_1_lab" id="bbg_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_1_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Precision HD = Swiss" href="#">
                        <?php echo 'Bbg 2';?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_2_lab" id="bbg_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_2_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Innovative by Optotech =  HKO" href="#">
                        <?php echo 'Bbg 3';?>
                    </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_3_lab" id="bbg_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_3_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td bgcolor="#DDDDDD" align="left">&nbsp;&nbsp;</td>
                  
                  
			  </tr>
              
              
                  <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="Compact Ultra, Lens Choice, SolaOne, Sola Easy =  HKO" href="#">
                  	    <?php echo 'Bbg 4';?>
                 </a></div></td>
				  <td align="left"><select  name="bbg_4_lab" id="bbg_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_4_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="Gt2, Gt2-3D = VOT" href="#">
                         <?php echo 'Bbg 5';?>
                     </a></div></td>
				  <td align="left"><select name="bbg_5_lab" id="bbg_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_5_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="ClearI = Somo" href="#">
                        <?php echo 'Bbg 6';?>
                     </a></div></td>
				  <td align="left"><select name="bbg_6_lab" id="bbg_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_6_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
			   
              
              
              
              
              
                   <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Summit, Smallfit, Ovation, CMF, ID Life style, PSI => DR" href="#">
                  	    <?php echo 'Bbg 7';?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_7_lab" id="bbg_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_7_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Innovative +  = HKO  " href="#">
                        <?php echo 'Bbg 8';?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_8_lab" id="bbg_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_8_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a href="#" style="text-decoration:none;" title="" >
                       <?php echo 'Bbg 9';?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_9_lab" id="bbg_9_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_9_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
              
              
              
              
              
              
              
              
              
              
              
    
                <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="FT35, FT28 = Conant" href="#">
                 <?php echo 'Bbg 10';?>
                </a></div></td>
				  <td align="left"><select  name="bbg_10_lab" id="bbg_10_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_10_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="SV, ST28 = SWISS" href="#">
                      <?php echo 'Bbg 11';?>
                     </a></div></td>
				  <td align="left"><select name="bbg_11_lab" id="bbg_11_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_11_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="SelectionRx = SCT" href="#">
                    	<?php echo 'Bbg 12';?>
                     </a></div></td>
				  <td align="left"><select name="bbg_12_lab" id="bbg_12_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; if($labData["bbg_12_lab"]==$labList[primary_key]) print " selected"; print ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="submit" name="addLab" id="addLab" value="Add Lab" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField"></td>
            		</tr>
			</table>
	  </form></td>
	  </tr>
</table>
  <p>&nbsp;</p>
<?php unset($_SESSION["formVars"]); ?>  
</body>
</html>
