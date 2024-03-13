<?php require_once("../sec_connectEDLL.inc.php"); ?>
<?php
if ($_SESSION["access_admin_id"] <> ""){
$queryAccess = "SELECT * FROM access_admin WHERE  id=" . $_SESSION["access_admin_id"];
$resultAccess=mysqli_query($con,$queryAccess) or die ('Error'. mysqli_error($con));
$AccessData=mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);
	
	
if($accountData["product_line"]=="directlens")
$edit_account	= $AccessData[edit_dl_account];

if($accountData["product_line"]=="lensnetclub")
$edit_account	= $AccessData[edit_ln_account];

if($accountData["product_line"]=="mybbgclub")	
$edit_account	= $AccessData[edit_bbg_account];	

if($accountData["product_line"]=="aitlensclub")	
$edit_account	= $AccessData[edit_ait_account];	

if($accountData["product_line"]=="ifcclub")	
$edit_account	= $AccessData[edit_ifc_account];	


if($accountData["product_line"]=="ifcclubca")	
$edit_account	= $AccessData[edit_ifc_account];

if($accountData["product_line"]=="ifcclubus")	
$edit_account	= $AccessData[edit_ifc_account];		

$edit_discounts	= $AccessData[edit_customer_discounts];		
$edit_bg		= $AccessData[edit_customer_buying_group];		
}	




if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($con,$theValue) : mysqli_escape_string($con,$theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysqli_select_db($con,$database_directlens);
$query_Recordset1 = "SELECT * FROM sales_reps";
$Recordset1 = mysqli_query($con,$query_Recordset1) or die(mysqli_error($con));
$row_Recordset1 = mysqli_fetch_array($Recordset1,MYSQLI_ASSOC);
$totalRows_Recordset1 = mysqli_num_rows($Recordset1);
?>
<link href="admin.css" rel="stylesheet" type="text/css" />
<form name="form3" method="post" action="getAccount.php" onSubmit="return formCheck(this);">
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
				<tr bgcolor="#000000">
					<td align="center" colspan="10"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$heading"; ?></font></b></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td width="7%" align="left" ><div align="right">
						Title
					</div></td>
					<td colspan="5" align="left" nowrap="nowrap">
                    
                    <?php  if ($edit_account=="yes"){  ?>
                   <select name="title" class="formField" id="title">
						<option value="">Select</option>
						<option value="Dr." <?php if($accountData["title"]=="Dr.") echo " selected"; ?>>Dr.</option>
						<option value="Mr." <?php if($accountData["title"]=="Mr.") echo " selected"; ?>>Mr.</option>
						<option value="Ms." <?php if($accountData["title"]=="Ms.") echo " selected"; ?>>Ms.</option>
						<option value="Mrs." <?php if($accountData["title"]=="Mrs.") echo " selected"; ?>>Mrs.</option>
					</select>	
                    <?php  }else{
					echo $accountData["title"];
					?>
                    <input  name="title" type="hidden" class="formField" id="title" value="<?php echo $accountData["title"]; ?>" size="20" />
					<?php }?>
                   				
                        
                        </td>
                        
					<td width="17%" align="left" nowrap="nowrap"><div align="right">
						First Name
					</div></td>
					<td width="19%" align="left">
                     <?php  if ($edit_account=="yes"){  ?>
                    <input  name="first_name" type="text" class="formField" id="first_name" value="<?php echo $accountData["first_name"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["first_name"];
					?>
                    <input  name="first_name" type="hidden" class="formField" id="first_name" value="<?php echo $accountData["first_name"]; ?>" size="20" />
					<?php }?>
                    </td>
					<td width="9%" align="left" nowrap="nowrap"><div align="right">
						Last Name
					</div></td>
					<td width="20%" align="left">
                    <?php  if ($edit_account=="yes"){  ?>
                    <input  name="last_name" type="text" class="formField" id="last_name" value="<?php echo $accountData["last_name"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["last_name"];
					?>
                    <input  name="last_name" type="hidden" class="formField" id="last_name" value="<?php echo $accountData["last_name"]; ?>" size="20" />
					<?php }?>			
                    </td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
						Account No
					</div></td>
					<td colspan="3" align="left" >            
                    <?php 	echo $accountData["account_num"];?>
                    <input  name="account_num" type="hidden" class="formField" id="account_num" value="<?php echo $accountData["account_num"]; ?>" size="20" />
                    
					
					
					
					</td>
					
					<td colspan="2"><b>Member since:</b> <?php echo $accountData["member_since"]; ?></td> 
					<td align="left" ><div align="right">
						Company
					</div></td>
					<td align="left">
                   <?php  if ($edit_account=="yes"){  ?>
                    <input  name="company" type="text" class="formField" id="company" value="<?php echo $accountData["company"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["company"];
					?>
                    <input  name="company" type="hidden" class="formField" id="company" value="<?php echo $accountData["company"]; ?>" size="20" />
					<?php }?>					
                    </td>
					
                    
                    
                    <td align="left" nowrap="nowrap"><div align="right">
						Buying Group
					</div></td>
					<td align="left">
                    
                       <?php  if ($edit_bg=="yes"){  ?>
                    <select name="buying_group" class="formField" id="buying_group">
	<?php
	$query="SELECT primary_key, bg_name FROM buying_groups order by bg_name";
	$result=mysqli_query($con,$query)		or die ("Could not find bg list");
	while ($bgList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$bgList[primary_key]\""; if($accountData["buying_group"]==$bgList[primary_key]) echo " selected"; echo ">$bgList[bg_name]</option>";
	}
						?>
					</select>
                    <?php  }else{
					$query="SELECT primary_key, bg_name FROM buying_groups WHERE primary_key = " .  $accountData["buying_group"];
					$result=mysqli_query($con,$query)		or die ("Could not find bg list");
					$bgList=mysqli_fetch_array($result,MYSQLI_ASSOC);
					echo $bgList["bg_name"];
					?>
                    <input  name="buying_group" type="hidden" class="formField" id="buying_group" value="<?php echo $bgList["primary_key"]; ?>" size="20" />
					<?php }?>
                    
                    
                    
                  
                    </td>
                    
                    
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
						Order by
					</div></td>
					
                    <td colspan="5" align="left" >
                    
                      
					 <?php  if ($edit_account=="yes"){  ?>
                    <input name="purchase_unit" type="radio" value="single" <?php if($accountData["purchase_unit"]=="single") echo " checked"; ?> class="formField" />
					single
					<input name="purchase_unit" type="radio" value="pair" <?php if($accountData["purchase_unit"]=="pair") echo " checked"; ?> class="formField" />
					pair	
                    <?php  }else{
					echo $accountData["purchase_unit"];
					?>
                    <input  name="purchase_unit" type="hidden" class="formField" id="purchase_unit" value="<?php echo $accountData["purchase_unit"]; ?>" size="20" />
					<?php }?> 
                    
                  
                    
                    </td>
					<td align="left" ><div align="right">
						Business Type
					</div></td>
					<td align="left">
                    
                    
                   						
                     
					 <?php  if ($edit_account=="yes"){  ?>
                    <select name="business_type" class="formField">
						<option value="Optometrist Office"<?php if($accountData["business_type"]=="Optometrist Office") echo " selected"; ?>>Optometrist Office</option>
						<option value="Optician Office"<?php if($accountData["business_type"]=="Optician Office") echo " selected"; ?>>Optician Office</option>
						<option value="Lab"<?php if($accountData["business_type"]=="Lab") echo " selected"; ?>>Lab</option>
					</select>	
                    <?php  }else{
					echo $accountData["business_type"];
					?>
                    <input  name="business_type" type="hidden" class="formField" id="business_type" value="<?php echo $accountData["business_type"]; ?>" size="20" />
					<?php }?> 
                        
                        </td>
					<td align="left" nowrap="nowrap"><div align="right">
						Currency
					</div></td>
					<td align="left">
                    
          <?php  if ($edit_account=="yes"){  ?>
     <select name="currency" id="currency" class="formField">
		<option value="US" <?php if($accountData["currency"]=="US") echo " selected"; ?>>US Dollar</option>
		<option value="CA" <?php if($accountData["currency"]=="CA") echo " selected"; ?>>CA Dollar</option>
		<option value="EUR" <?php if($accountData["currency"]=="EUR") echo " selected"; ?>>EU Euro</option>
	</select>
    	 <?php  }else{
					echo $accountData["currency"];
					?>
                    <input  name="currency" type="hidden" class="formField" id="currency" value="<?php echo $accountData["currency"]; ?>" size="20" />
					<?php }?>            
                    
                    
   
    			</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right">
						Discounts
					</div></td>
					<td width="4%" align="left" ><div align="right">
						RX
					</div></td>
				
                
                	<td width="8%" align="left"><div align="right">&nbsp;</div></td>
					<td align="left" >&nbsp;</td>
					
                    <td width="8%" align="left"><div align="right">&nbsp;</div></td>
					<td align="left" >&nbsp;</td>
                    
			
            
            		<td align="left" ><div align="right">
						Fax
					</div></td>
					<td align="left">
                     <?php  if ($edit_account=="yes"){  ?>
                    <input  name="fax" type="text" class="formField" id="fax" value="<?php echo $accountData["fax"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["fax"];
					?>
                    <input  name="fax" type="hidden" class="formField" id="fax" value="<?php echo $accountData["fax"]; ?>" size="20" />
					<?php }?>					
                    </td>
					
                    
                    <td align="left" nowrap="nowrap"><div align="right">
						VAT Number
					</div></td>
					<td align="left">
                     <?php  if ($edit_account=="yes"){  ?>
                    <input  name="VAT_no" type="text" class="formField" id="VAT_no" value="<?php echo $accountData["VAT_no"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["VAT_no"];
					?>
                    <input  name="VAT_no" type="hidden" class="formField" id="VAT_no" value="<?php echo $accountData["VAT_no"]; ?>" size="20" />
					<?php }?>		
                    </td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
				
                
                	<td colspan="2" align="left" ><div align="right">&nbsp;</div></td>
					<td align="left" >&nbsp;</td>
					
                    
                    <td align="left" ><div align="right">&nbsp;</div></td>
					<td align="left" >&nbsp;</td>
                    
                    
					<td align="left" ><div align="right">
						Email
					</div></td>
					<td  align="left">
                  <?php  if ($edit_account=="yes"){  ?>
                    <input  name="email" type="text" class="formField" id="email" value="<?php echo $accountData["email"]; ?>" size="50" />
                    <?php  }else{
					echo $accountData["email"];
					?>
                    <input  name="email" type="hidden" class="formField" id="email" value="<?php echo $accountData["email"]; ?>" size="50" />
					<?php }?>
				 </td>
                 
                 
                 
                 	<td align="left" ><div align="right">
						Depot Number
					</div></td>
					<td  align="left">
                    <input  name="depot_number" type="text" class="formField" id="depot_number" value="<?php echo $accountData["depot_number"]; ?>" size="10" />
				 </td>
                 
                 
				</tr>
                
                
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
					&nbsp;
					</div></td>
					<td align="left" >&nbsp;
                    <?php /*?><?php  if ($edit_discounts=="yes"){  ?>
                    <input  name="precision_dsc" type="text" class="formField" id="precision_dsc" value="<?php echo $accountData["precision_dsc"]; ?>" size="4" />
                    <?php  }else{
					echo $accountData["precision_dsc"];
					?>
                    <input  name="precision_dsc" type="hidden" class="formField" id="precision_dsc" value="<?php echo $accountData["precision_dsc"]; ?>" size="4" />
					<?php }?>	<?php */?>			
                    </td>
                    
                    
					<td align="left" ><div align="right">
					&nbsp;
					</div></td>
					<td align="left" >&nbsp;
                  <?php /*?>  <?php  if ($edit_discounts=="yes"){  ?>
                    <input name="visioneco_dsc" type="text" id="visioneco_dsc" size="4" maxlength="4" value="<?php echo $accountData["visioneco_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["visioneco_dsc"];
					?>
                    <input  name="visioneco_dsc" type="hidden" class="formField" id="visioneco_dsc" value="<?php echo $accountData["visioneco_dsc"]; ?>" size="4" />
					<?php }?>	<?php */?>
                    </td>
					
                    
                    <td align="left" ><div align="right">
						Login
					</div></td>
					<td align="left"><b><?php echo $accountData["user_id"]; ?></b> </td>
					
                    
                    <td align="left" nowrap="nowrap"><div align="right">
						Password
					</div></td>
					<td align="left">
                   <?php  if ($edit_account=="yes"){  ?>
                    <input  name="password" type="text" class="formField" id="password" value="<?php echo $accountData["password"]; ?>" size="20" />
                    <?php  }else{
					echo $accountData["password"];
					?>
                    <input  name="password" type="hidden" class="formField" id="password" value="<?php echo $accountData["password"]; ?>" size="20" />
					<?php }?>			
                    </td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					
                    <td colspan="2" align="left" ><div align="right"> &nbsp; </div></td>
					<td align="left" >&nbsp;
                    <?php /*?> <?php  if ($edit_discounts=="yes"){  ?>
                    <input name="generation_dsc" type="text" id="generation_dsc" size="4" maxlength="4" value="<?php echo $accountData["generation_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["generation_dsc"];
					?>
                    <input  name="generation_dsc" type="hidden" class="formField" id="generation_dsc" value="<?php echo $accountData["generation_dsc"]; ?>" size="4" />
					<?php }?>	<?php */?>
                    </td>
                    
                    
					<td align="left" ><div align="right">&nbsp;</div></td>
					<td align="left" bgcolor="#DDDDDD" >&nbsp;
                    <?php /*?> <?php  if ($edit_discounts=="yes"){  ?>
                     <input name="truehd_dsc" type="text" id="truehd_dsc" size="4" maxlength="4" value="<?php echo $accountData["truehd_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["truehd_dsc"];
					?>
                    <input  name="truehd_dsc" type="hidden" class="formField" id="truehd_dsc" value="<?php echo $accountData["truehd_dsc"]; ?>" size="4" />
					<?php }?>	<?php */?>
                    </td>
				
                
                	<td align="left" ><div align="right"> Buying Level:</div></td>
					<td align="left">
                    <?php  if ($edit_account=="yes"){  ?>
                    <select name="buying_level" class="formField" id="buying_level">
					<?php
                    $query="SELECT buying_level FROM buying_levels";
                    $result=mysqli_query($con,$query) or die ("Could not find lab list");
                    while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    echo "<option value=\"$labList[buying_level]\""; if($accountData["buying_level"]==$labList[buying_level]) echo " selected"; echo ">$labList[buying_level]</option>";
					}
					?>
				    </select>
                    <?php  }else{
					$query="SELECT buying_level FROM buying_levels WHERE buying_level =  '" . $accountData["buying_level"] . "'" ;
				    $result=mysqli_query($con,$query) or die ("Could not find lab list");
					$labList=mysqli_fetch_array($result,MYSQLI_ASSOC);
					echo $labList["buying_level"];
					?>
                    <input  name="buying_level" type="hidden" class="formField" id="buying_level" value="<?php echo $labList[buying_level]; ?>" size="4" />
					<?php }?>	
                    
                    
                    </td>
                    
                    
					<td align="left"><div align="right">E-Lab</div></td>
					<td align="left">
                    <?php  if ($edit_account=="yes"){  ?>
                    <select name="e_lab" class="formField">
			        	<option value = "None"		 <?php if($accountData["e_lab"]=="None") 	  echo " selected"; ?>>None</option>
						<option value = "E-Lab US" 	 <?php if($accountData["e_lab"]=="E-Lab US")  echo " selected"; ?>>E-Lab US</option>
						<option value = "E-Lab CAN"  <?php if($accountData["e_lab"]=="E-Lab CAN") echo " selected"; ?>>E-Lab CAN</option>
			        </select>
                    <?php  }else{
					echo $accountData["e_lab"];
					?>
                    <input  name="e_lab" type="hidden" class="formField" id="e_lab" value="<?php echo $accountData["e_lab"]; ?>" size="10" />
					<?php }?>	
                   
                    </td>
				</tr>
				<tr bgcolor="#FFFFFF">
				  <td align="left" >&nbsp;</td>
				
                
                  <td colspan="2" align="left" ><div align="right">&nbsp;</div></td>
				  <td align="left" >&nbsp;
                   <?php /*?><?php  if ($edit_discounts=="yes"){  ?>
                     <input name="easy_fit_dsc" type="text" id="easy_fit_dsc" size="4" maxlength="4" value="<?php echo $accountData["easy_fit_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["easy_fit_dsc"];
					?>
                    <input  name="easy_fit_dsc" type="hidden" class="formField" id="easy_fit_dsc" value="<?php echo $accountData["easy_fit_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
                  
				
                
                  <td align="left" ><div align="right">&nbsp;</div></td>
				  <td align="left" >&nbsp;
                   <?php /*?><?php  if ($edit_discounts=="yes"){  ?>
                      <input name="private_1_dsc" type="text" id="private_1_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_1_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["private_1_dsc"];
					?>
                    <input  name="private_1_dsc" type="hidden" class="formField" id="private_1_dsc" value="<?php echo $accountData["private_1_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
				  
                  
                  <td align="left" ><div align="right"> Account Approval </div></td>
				  <td align="left"><select name="approved" class="formField">
		          <option value = "pending" <?php if($accountData["approved"]=="pending") echo " selected"; ?>>pending</option>
				      <option value = "approved" <?php if($accountData["approved"]=="approved") echo " selected"; ?>>approved</option>
				      <option value = "declined" <?php if($accountData["approved"]=="declined") echo " selected"; ?>>declined</option>
		          </select></td>
				  <td align="left"><div align="right"> Shipping Code </div></td>
				  <td align="left">
                  
                    <?php  if ($edit_account=="yes"){  ?>
                       <select name="shipping_code" id="shipping_code" class="formField">
				    <option value="">Select</option>
                    <option value="OR005DLN"  <?php if($accountData["shipping_code"]=="OR005DLN")  echo " selected"; ?>>OR005DLN</option>
                    <option value="OR005US"   <?php if($accountData["shipping_code"]=="OR005US")   echo " selected"; ?>>OR005US</option>
 					<option value=""  <?php if($accountData["shipping_code"]=="")  echo " selected"; ?>></option>



				     
                    
                     
			      </select>
                    <?php  }else{
					echo $accountData["shipping_code"];
					?>
                    <input  name="shipping_code" type="hidden" class="formField" id="shipping_code" value="<?php echo $accountData["shipping_code"]; ?>" size="4" />
					<?php }?>
                
                  
                  </td>
      </tr>
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#DDDDDD" >&nbsp;</td>
				  <td colspan="2" align="left" bgcolor="#DDDDDD" ><div align="right">&nbsp;</div></td>
				  <td align="left" bgcolor="#DDDDDD" >&nbsp;
                  <?php /*?> <?php  if ($edit_discounts=="yes"){  ?>
                     <input name="glass_dsc" type="text" id="glass_dsc" size="4" maxlength="4" value="<?php echo $accountData["glass_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["glass_dsc"];
					?>
                    <input  name="glass_dsc" type="hidden" class="formField" id="glass_dsc" value="<?php echo $accountData["glass_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
                  
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">&nbsp;</div></td>
				  <td align="left" bgcolor="#DDDDDD" >&nbsp;
                 <?php /*?>  <?php  if ($edit_discounts=="yes"){  ?>
                   <input name="private_2_dsc" type="text" id="private_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_2_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["private_2_dsc"];
					?>
                    <input  name="private_2_dsc" type="hidden" class="formField" id="private_2_dsc" value="<?php echo $accountData["private_2_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
                  
                  
				  <td align="left" bgcolor="#DDDDDD" ><div align="right"> Sales Representative</div></td>
				  <td align="left" bgcolor="#DDDDDD">
                   <?php  if ($edit_account=="yes"){  ?>
                    <select name="mysalesrep" class="formField">
				    <option value="0" <?php if (!(strcmp(0, $accountData["sales_rep"]))) {echo "selected=\"selected\"";} ?>>Select Representative</option>
				    <?php
					do {  
					?>
				    <option value="<?php echo $row_Recordset1['id']?>"<?php if (!(strcmp($row_Recordset1['id'], $accountData["sales_rep"]))) {echo "selected=\"selected\"";} ?>><?php echo $row_Recordset1['rep_name']?></option>
				    <?php
					} while ($row_Recordset1 = mysqli_fetch_assoc($Recordset1,MYSQLI_ASSOC));
					  $rows = mysqli_num_rows($Recordset1);
					  if($rows > 0) {
						  mysqli_data_seek($Recordset1, 0);
						  $row_Recordset1 = mysqli_fetch_assoc($Recordset1,MYSQLI_ASSOC);
					  }
					?>
                  </select>
                  
                    <?php  }else{
					
					if ($accountData["mysalesrep"] <> '') 
					{
					$query="SELECT rep_name FROM sales_reps WHERE id = " .  $accountData["mysalesrep"];
					$result=mysqli_query($con,$query)		or die ("Could not find bg list");
					$bgList=mysqli_fetch_array($result,MYSQLI_ASSOC);
					$bg = $bgList['rep_name'];
					}else{
					$bg = '';
					}
					echo $bg;
					?>
                    <input  name="mysalesrep" type="hidden" class="formField" id="mysalesrep" value="<?php echo $accountData["mysalesrep"]; ?>" size="4" />
					<?php }?>
                 
                 
                 
               
                  
                  
                  </td>
				  <td align="left" bgcolor="#DDDDDD"><div align="right"> Product Line</div></td>
				  <td align="left" bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
                    <select name="product_line" id="product_line" class="formField">
                        <option value = "aitlensclub" <?php	if($accountData["product_line"]=="aitlensclub") echo " selected";?> >AIT Lens Club</option>
                        <option value = "directlens"  <?php	if($accountData["product_line"]=="directlens") echo " selected";?> >Directlens</option>
                        <option value = "eye-recommend" <?php	if($accountData["product_line"]=="eye-recommend") echo " selected";?> >Prestige</option>
                        <option value = "lensnetclub" <?php	if($accountData["product_line"]=="lensnetclub") echo " selected";?> >Lens Net Club</option>
                        <option value = "ifcclub"     <?php if($accountData["product_line"]=="ifcclub") echo " selected";?> >IFC Optic Club</option>
                        <option value = "ifcclubca"   <?php if($accountData["product_line"]=="ifcclubca") echo " selected";?> >IFC Optic CA Club</option>
                    </select>
                    <?php  }else{
					
					switch ($accountData["product_line"]){
						case 'directlens':    echo 'Directlens';  		break;
						case 'eye-recommend': echo 'Prestige';  		break;
						case 'lensnetclub':   echo 'Lens Net Club'; 	break;
						case 'ifcclub':  	  echo 'IFC OPTIC Club'; 	break;
						case 'ifcclubca':  	  echo 'IFC OPTIC CA Club'; break;
					}
					?>
                    <input  name="product_line" type="hidden" class="formField" id="product_line" value="<?php echo $accountData["product_line"]; ?>" size="20" />
                  <?php }?></td>
	  </tr>
				<tr bgcolor="#FFFFFF">
				  <td align="left" >&nbsp;</td>
				  
                  
                  <td colspan="2" align="left" ><div align="right">&nbsp;</div></td>
				  <td align="left" >&nbsp;
                 <?php /*?> <?php  if ($edit_discounts=="yes"){  ?>
                     <input name="eco_dsc" type="text" id="eco_dsc" size="4" maxlength="4" value="<?php echo $accountData["eco_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["eco_dsc"];
					?>
                    <input  name="eco_dsc" type="hidden" class="formField" id="eco_dsc" value="<?php echo $accountData["eco_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
                  
				  <td align="left" ><div align="right">&nbsp;</div></td>
				  <td align="left" >&nbsp;
                  <?php /*?> <?php  if ($edit_discounts=="yes"){  ?>
                     <input name="private_3_dsc" type="text" id="private_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_3_dsc"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["private_3_dsc"];
					?>
                    <input  name="private_3_dsc" type="hidden" class="formField" id="private_3_dsc" value="<?php echo $accountData["private_3_dsc"]; ?>" size="4" />
					<?php }?><?php */?>
                  </td>
				  <td colspan="2" align="left" bgcolor="#FFFFFF" ><div align="right"></div>				    
			      <div align="right"> <strong>Lab Preferences - </strong>Main Lab</div></td>
				  <td colspan="2" align="left"><?php   if ($edit_account=="yes"){  ?>
                    <select name="main_lab" id="main_lab" class="formField">
                      <?php
                    $query="select primary_key, lab_name from labs order by lab_name";
					$result=mysqli_query($con,$query)      or die ("Could not find lab list");
                    while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
                    echo "<option value=\"$labList[primary_key]\""; if($accountData["main_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
					}
					?>
                    </select>
                    <?php  }else{
					$query="SELECT primary_key, lab_name FROM labs where primary_key = " . $accountData["main_lab"];
				    $result=mysqli_query($con,$query)      or die ("Could not find lab list");
					$labList=mysqli_fetch_array($result,MYSQLI_ASSOC);
					echo $labList["lab_name"];
					?>
                    <input  name="main_lab" type="hidden" class="formField" id="main_lab" value="<?php echo $labList["primary_key"]; ?>" size="20" />
                  <?php } ?></td>
	  </tr>
				<tr bgcolor="#FFFFFF">
					<td colspan="3" align="left" bgcolor="#DDDDDD" ><div align="right"> Credit Hold
					        <?php  if ($edit_account=="yes"){  ?>
                   <input name="credit_hold" type="checkbox"  class="formField" id="credit_hold" value="1" <?php if($accountData["credit_hold"]=="1") echo " checked"; ?> />
                    <?php  }else{
					 if($accountData["credit_hold"]=="1") {
					   echo "Yes";
					   }else{
					   echo "No";
					   } ?> 
					<?php }?>

					</div></td>
					<td colspan="3" align="left" bgcolor="#DDDDDD" > If checked, customer must pay for new orders with a credit
				    card </td>
					<td align="left" bgcolor="#DDDDDD" >Bill To:
                     <select name = "bill_to" id="bill_to" class="formField">
					<?php   $bill_to  = $accountData["bill_to"];        ?>
                    <option value="">Select One</option>
                    <option value ="B00020"	<?php if($bill_to=="B00020") echo " selected"; ?>>B00020</option>
                    <option value ="B00021" <?php if($bill_to=="B00021") echo " selected"; ?>>B00021</option>
                    <option value ="B00022" <?php if($bill_to=="B00022") echo " selected"; ?>>B00022</option>
                    <option value ="B00023" <?php if($bill_to=="B00023") echo " selected"; ?>>B00023</option>
                    <option value ="B00024" <?php if($bill_to=="B00024") echo " selected"; ?>>B00024</option>
                    <option value ="B00025" <?php if($bill_to=="B00025") echo " selected"; ?>>B00025</option>
                    <option value ="B00026" <?php if($bill_to=="B00026") echo " selected"; ?>>B00026</option>
                    <option value ="B00027" <?php if($bill_to=="B00027") echo " selected"; ?>>B00027</option>
      			   </select>
                    </td>
					<td align="left" nowrap="nowrap" bgcolor="#DDDDDD" ><div align="right"> Credit Limit 
                    
                     <?php  if ($edit_account=="yes"){  ?>
                       <input name="cl_limit_amt" type="text" id="cl_limit_amt" size="20" value="<?php echo $accountData["cl_limit_amt"]; ?>" class="formField" />
                    <?php  }else{
					echo $accountData["cl_limit_amt"];
					?>
                    <input  name="cl_limit_amt" type="hidden" class="formField" id="cl_limit_amt" value="<?php echo $accountData["cl_limit_amt"]; ?>" size="20" />
					<?php }?>
                    
                  
                    
                    </div></td>
					<td colspan="2" align="left" bgcolor="#DDDDDD" >					  If not empty, customer must pay for new orders with a credit
				    card if account balance is equal or greater than limit</td>
				</tr>
                
                <tr><td>&nbsp;</td></tr>
                
                <tr bgcolor="#FFFFFF">
					<td colspan="3" align="left" bgcolor="#DDDDDD" ><div align="right"> Account Past Due <b>(LNC ONLY)</b>
					        <?php  if ($edit_account=="yes"){  ?>
                   <input name="account_past_due" type="checkbox"  class="formField" id="account_past_due" value="yes" <?php if($accountData["account_past_due"]=="yes") echo " checked"; ?> />
                    <?php  }else{
					 if($accountData["account_past_due"]=="yes") {
					   echo "Yes";
					   }else{
					   echo "No";
					   } ?> 
					<?php }?>

					</div></td>
					<td colspan="3" align="left" bgcolor="#DDDDDD" > If checked, (A warning message  will be displayed to the customer in the prescription page)
				     </td>
				</tr>
                
                

    
                
				<tr bgcolor="#DDDDDD">
				  <td colspan="4" align="right" valign="top" bgcolor="#FFFFF" >My Stock Collections</td>
				  <td colspan="3" align="left" valign="top" bgcolor="#FFFFF" class="bord-right" >
              
                 <?php
				   
				$stockcollectionsQuery="SELECT * FROM accounts_stock_collections WHERE accounts_id='$accountData[primary_key]'";
				$stockcollectionsResult=mysqli_query($con,$stockcollectionsQuery) or die ("ERROR ".mysqli_error($con));
					
				$stock_collections=array();
			  	while($item=mysqli_fetch_array($stockcollectionsResult,MYSQLI_ASSOC)){
					array_push($stock_collections,$item['stock_collections_id']);
				}
    
			   	$stockcollectionsQuery="SELECT * FROM stock_collections WHERE active='1'";
				$stockcollectionsResult=mysqli_query($con,$stockcollectionsQuery) or die ("ERROR ".mysqli_error($con));
			  	while($stockCollectionsItem=mysqli_fetch_array($stockcollectionsResult,MYSQLI_ASSOC)){
					$checkedStr="";
					
					foreach($stock_collections as $k=>$v){
						if ($stockCollectionsItem['stock_collections_id']==$v){
							$checkedStr=" checked=checked ";
						}
					}
					
					echo '<input name="stock_collection['.$stockCollectionsItem['stock_collections_id'].']" type="checkbox" '.$checkedStr.' class="formText" value="1"/>';
					echo $stockCollectionsItem['stock_collection']."<br>";
				}
			  ?>
              </td>
				  <td colspan="1" align="right" valign="top" bgcolor="#FFFFFF" class="formField">My Exclusive Collections:</td>
				  <td colspan="2" align="left" valign="top" bgcolor="#ffffff"><div style="height:100px;overflow-y:scroll"><?php 
				  showcollections($accountData["primary_key"]);?>
                  </div></td>
	  </tr>
				
                
         
			</table>
	<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
	  <tr>
	    <td colspan="4" bgcolor="#000000"><div align="center"> <font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b>Billing
        Address</b></font></div></td>
	    <td colspan="4" bgcolor="#000000"><div align="center"> <font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b>Shipping
        Address</b></font> </div></td>
      </tr>
	  <tr>
	    <td><div align="right"> Address 1 </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <input name="bill_address1" type="text" id="bill_address1" size="20" value="<?php echo $accountData["bill_address1"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["bill_address1"];
					?>
          <input  name="bill_address1" type="hidden" class="formField" id="bill_address1" value="<?php echo $accountData["bill_address1"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Address 2 </div></td>
	    <td class="bord-right"><?php  if ($edit_account=="yes"){  ?>
          <input name="bill_address2" type="text" id="bill_address2" size="20" value="<?php echo $accountData["bill_address2"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["bill_address2"];
					?>
          <input  name="bill_address2" type="hidden" class="formField" id="bill_address2" value="<?php echo $accountData["bill_address2"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Address 1 </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <input name="ship_address1" type="text" id="ship_address1" size="20" value="<?php echo $accountData["ship_address1"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["ship_address1"];
					?>
          <input  name="ship_address1" type="hidden" class="formField" id="ship_address1" value="<?php echo $accountData["ship_address1"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Address 2 </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <input name="ship_address2" type="text" id="ship_address2" size="20" value="<?php echo $accountData["ship_address2"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["ship_address2"];
					?>
          <input  name="ship_address2" type="hidden" class="formField" id="ship_address2" value="<?php echo $accountData["ship_address2"]; ?>" size="20" />
        <?php }?></td>
      </tr>
	  <tr>
	    <td bgcolor="#DDDDDD"><div align="right"> City </div></td>
	    <td bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
          <input name="bill_city" type="text" id="bill_city" size="20" value="<?php echo $accountData["bill_city"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["bill_city"];
					?>
          <input  name="bill_city" type="hidden" class="formField" id="bill_city" value="<?php echo $accountData["bill_city"]; ?>" size="20" />
        <?php }?></td>
	    <td bgcolor="#DDDDDD"><div align="right"> State/Province </div></td>
	    <td bgcolor="#DDDDDD" class="bord-right"><?php  if ($edit_account=="yes"){  ?>
          <input name="bill_state" type="text" id="bill_state" size="20" value="<?php echo $accountData["bill_state"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["bill_state"];
					?>
          <input  name="bill_state" type="hidden" class="formField" id="bill_state" value="<?php echo $accountData["bill_state"]; ?>" size="20" />
        <?php }?></td>
	    <td bgcolor="#DDDDDD"><div align="right"> City </div></td>
	    <td bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
          <input name="ship_city" type="text" id="ship_city" size="20" value="<?php echo $accountData["ship_city"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["ship_city"];
					?>
          <input  name="ship_city" type="hidden" class="formField" id="ship_city" value="<?php echo $accountData["ship_city"]; ?>" size="20" />
        <?php }?></td>
	    <td bgcolor="#DDDDDD"><div align="right"> State/Province </div></td>
	    <td bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
          <input name="ship_state" type="text" id="ship_state" size="20" value="<?php echo $accountData["ship_state"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["ship_state"];
					?>
          <input  name="ship_state" type="hidden" class="formField" id="ship_state" value="<?php echo $accountData["ship_state"]; ?>" size="20" />
        <?php }?></td>
      </tr>
	  <tr>
	    <td><div align="right"> Zip/Postal Code </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <input name="bill_zip" type="text" id="bill_zip" size="20" value="<?php echo $accountData["bill_zip"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["bill_zip"];
					?>
          <input  name="bill_zip" type="hidden" class="formField" id="bill_zip" value="<?php echo $accountData["bill_zip"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Country </div></td>
	    <td class="bord-right"><?php  if ($edit_account=="yes"){  ?>
          <select name = "bill_country" id="bill_country" class="formField">
            <?php   $Bill_Country  = $accountData["bill_country"];                   ?>
            <option value="">Select One</option>
            <option value ="BE" <?php if($Bill_Country=="BE") echo " selected"; ?>>Benin</option>
            <option value ="CA" <?php if($Bill_Country=="CA") echo " selected"; ?>>Canada</option>
            <option value ="CAM" <?php if($Bill_Country=="CAM") echo " selected"; ?>>Cameroun</option>
            <option value ="CR" <?php if($Bill_Country=="CR") echo " selected"; ?>>Caribbean</option>
            <option value ="CI" <?php if($Bill_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
            <option value ="FR" <?php if($Bill_Country=="FR") echo " selected"; ?>>France</option>
            <option value ="IT" <?php if($Bill_Country=="IT") echo " selected"; ?>>Italy</option>
            <option value ="SE" <?php if($Bill_Country=="SE") echo " selected"; ?>>Senegal</option>
            <option value ="TO" <?php if($Bill_Country=="TO") echo " selected"; ?>>Togo</option>
            <option value ="US" <?php if($Bill_Country=="US") echo " selected"; ?>>United States</option>
          </select>
          <?php  }else{
					
					switch($accountData["bill_country"]){
					case 'BE': 		$country_billing = "Benin";			break;	
					case 'CA':	 	$country_billing = "Canada";		break;	
					case 'CAM':		$country_billing = "Cameroun";		break;	
					case 'CR':		$country_billing = "Caribbean";		break;		
					case 'CI':		$country_billing = "Cote d'Ivoire";	break;					
					case 'FR':		$country_billing = "France";		break;					
					case 'IT':	 	$country_billing = "Italy";			break;	
					case 'SE':		$country_billing = "Senegal";		break;	
					case 'TO':		$country_billing = "Togo";			break;	
					case 'US':		$country_billing = "United States";	break;	
					}
					
					echo $country_billing;
					?>
          <input  name="bill_country" type="hidden" class="formField" id="bill_country" value="<?php echo $accountData["bill_country"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Zip/Postal Code </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <input name="ship_zip" type="text" id="ship_zip" size="20" value="<?php echo $accountData["ship_zip"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["ship_zip"];
					?>
          <input  name="ship_zip" type="hidden" class="formField" id="ship_zip" value="<?php echo $accountData["ship_zip"]; ?>" size="20" />
        <?php }?></td>
	    <td><div align="right"> Country </div></td>
	    <td><?php  if ($edit_account=="yes"){  ?>
          <select name = "ship_country" id="ship_country" class="formField">
            <?php   $Ship_Country  = $accountData["ship_country"];                   ?>
            <option value="">Select One</option>
            <option value ="BE" <?php if($Ship_Country=="BE") echo " selected"; ?>>Benin</option>
            <option value ="CA" <?php if($Ship_Country=="CA") echo " selected"; ?>>Canada</option>
            <option value ="CAM" <?php if($Ship_Country=="CAM") echo " selected"; ?>>Cameroun</option>
            <option value ="CR" <?php if($Ship_Country=="CR") echo " selected"; ?>>Caribbean</option>
            <option value ="CI" <?php if($Ship_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
            <option value ="FR" <?php if($Ship_Country=="FR") echo " selected"; ?>>France</option>
            <option value ="IT" <?php if($Ship_Country=="IT") echo " selected"; ?>>Italy</option>
            <option value ="SE" <?php if($Ship_Country=="SE") echo " selected"; ?>>Senegal</option>
            <option value ="TO" <?php if($Ship_Country=="TO") echo " selected"; ?>>Togo</option>
            <option value ="US" <?php if($Ship_Country=="US") echo " selected"; ?>>United States</option>
          </select>
          <?php  }else{
					
					switch($accountData["bill_country"]){
					case 'BE': 		$country_shipping = "Benin";		 break;	
					case 'CA':	 	$country_shipping = "Canada";		 break;	
					case 'CAM':		$country_shipping = "Cameroun";		 break;	
					case 'CR':		$country_shipping = "Caribbean";	 break;		
					case 'CI':		$country_shipping = "Cote d'Ivoire"; break;					
					case 'FR':		$country_shipping = "France";		 break;					
					case 'IT':	 	$country_shipping = "Italy";		 break;	
					case 'SE':		$country_shipping = "Senegal";		 break;	
					case 'TO':		$country_shipping = "Togo";			 break;	
					case 'US':		$country_shipping = "United States"; break;	
					}
					
					echo $country_shipping;
					?>
          <input  name="ship_country" type="hidden" class="formField" id="ship_country" value="<?php echo $accountData["ship_country"]; ?>" size="20" />
        <?php }?></td>
      </tr>
	  <tr>
	    <td bgcolor="#DDDDDD">&nbsp;</td>
	    <td bgcolor="#DDDDDD">&nbsp;</td>
	    <td bgcolor="#DDDDDD">&nbsp;</td>
	    <td bgcolor="#DDDDDD" class="bord-right">&nbsp;</td>
	    <td bgcolor="#DDDDDD"><div align="right"> Phone </div></td>
	    <td bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
          <input name="phone" type="text" id="phone" size="20" value="<?php echo $accountData["phone"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["phone"];
					?>
          <input  name="phone" type="hidden" class="formField" id="phone" value="<?php echo $accountData["phone"]; ?>" size="20" />
        <?php }?></td>
	    <td bgcolor="#DDDDDD"><div align="right"> Other Phone </div></td>
	    <td bgcolor="#DDDDDD"><?php  if ($edit_account=="yes"){  ?>
          <input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $accountData["other_phone"]; ?>" class="formField" />
          <?php  }else{
					echo $accountData["other_phone"];
					?>
          <input  name="other_phone" type="hidden" class="formField" id="other_phone" value="<?php echo $accountData["other_phone"]; ?>" size="20" />
        <?php }?></td>
      </tr>
      
           <tr bgcolor="#FFFFFF">
               <td align="right" colspan="3">Loyalty Program</td>
               <td>
               <select name = "loyalty_program" id="loyalty_program" class="formField">
				   <?php  $Loyalty_Program  = $accountData["loyalty_program"];  ?>
                   <option value="">Select One</option>
                   <option value ="platinum"    <?php if($Loyalty_Program=="platinum") echo " selected"; ?>>Platinum</option>
                   <option value ="gold"        <?php if($Loyalty_Program=="gold")     echo " selected"; ?>>Gold</option>
                   <option value ="silver"  	<?php if($Loyalty_Program=="silver")   echo " selected"; ?>>Silver</option>
                   <option value ="none"  		<?php if($Loyalty_Program=="none")     echo " selected"; ?>>None</option>
               </select>
           	   </td>
           </tr>             
  </table>
  
  

  
  <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
	  <tr>
	    <td colspan="4" bgcolor="#000000"><div align="center"> <font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b>Prestige Level</b></font></div></td>
      </tr>
	  <tr>
	    <td><div align="right"> Prestige Level </div></td>
	    <td>
        		  <select name="prestige_level" class="formField" id="prestige_level">
                 	<option value="none"   <?php if($accountData["prestige_level"]=="none")   echo " selected"; ?>>None</option>
                    <option value="high"   <?php if($accountData["prestige_level"]=="high")   echo " selected"; ?>>High   (45% on invoice + 5% on statement)</option>
					<option value="medium" <?php if($accountData["prestige_level"]=="medium") echo " selected"; ?>>Medium (25% on invoice + 5% on statement)</option>
				 	<option value="low"    <?php if($accountData["prestige_level"]=="low") 	  echo " selected"; ?>>Low    (10% on invoice + 5% on statement)</option>
				 </select>	
	  	</td>
        <td><div align="right">Account Rebate</div></td>
        <td><?php echo $accountData["account_rebate"]; ?>%</td>
      </tr>
      
      <tr bgcolor="#DDDDDD">
      				<td width="10%" align="left" >
                    <div align="right">Prestige Swiss</div></td>
					<td align="left" width="10%" >
					<?php echo $accountData["er_swiss_dsc"]; ?>%</td>
                    
                    <td align="left" nowrap="nowrap" width="3%" ><div align="right">Prestige TR</div></td>
					<td align="left" width="10%" >
                    <?php echo $accountData["er_tr_dsc"];?>%</td>
      </tr>
      
      
      
      
         <tr>
      				<td width="10%" align="left"><div align="right">Prestige Hko</div></td>
					<td align="left" width="10%"><?php echo $accountData["er_hko_dsc"]; ?>%</td>
                    
                    <td align="left" nowrap="nowrap" width="3%" ><div align="right">Prestige Crystal</div></td>
					<td align="left" width="10%"><?php echo $accountData["er_crystal_dsc"]; ?>%</td>
      </tr>
      
      <tr><td>&nbsp;</td></tr>
      
        <tr bgcolor="#DDDDDD">
					<td colspan="8" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$accountData[primary_key]"; ?>" />
							<input type="hidden" name="user_id" value="<?php echo "$accountData[user_id]"; ?>" />
							<input type="hidden" name="notifyApproved" value="<?php echo "$accountData[approved]"; ?>" />
							<input type="submit" name="editAcct" id="editAcct" value="Edit Account" class="formField" />
						&nbsp;
						<input name="cancel" type="button" id="cancel" value="Cancel" onclick="window.open('adminHome.php', '_top')" class="formField" />
						<br />
						<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b>Edit
						Account cannot be reversed.</b></font></td>
	  </tr>
  </table>
  
  
  
</form>