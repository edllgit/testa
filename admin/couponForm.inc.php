<form name="form4" method="post" action="newCoupon.php"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="2" cellspacing="0">
      <tr bgcolor="#000000">
        <td colspan="2" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Add
              Coupon Code </font></b></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap"><p> <font size="1" face="Arial, Helvetica, sans-serif">Code:</font></p></td>
        <td align="left" nowrap="nowrap"><font size="1">
          <input name="code" type="text" id="product_make" value="<?php echo $couponItem[code];?>" size="12" maxlength="12" />
        </font></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Type:</font></td>
        <td align="left" bgcolor="#DDDDDD"><select name="type" id="type">
          <option value="one-time" <?php if ($couponItem[type]=="one-time") echo "selected";?>>Once Per Customer</option>
          <option value="unlimited"<?php if ($couponItem[type]=="unlimited") echo "selected";?>>Unlimited</option>
          <option value="inactive"<?php if ($couponItem[type]=="inactive") echo "selected";?>>Inactive</option>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">End Date:<br />
        (end of coupon run) </font></td>
        <td align="left" bgcolor="#FFFFFF">
          <input name="end_date" type="text" id="end_date" value="<?php 	 		  if ($_GET[edit]=='true'){ echo $couponItem[date];}?>" size="10" />
       </td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">By
            Collection:
          <input name="select_by" type="radio" value="collection" <?php  if (($couponItem[select_by]=='collection')||($couponItem[select_by]=='') ) 
 echo "checked=\"checked\""; ?>/>
        </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="collection" id="collection" class="formField">
            <?php
  $query="select collection from exclusive group by collection asc"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItem[collection]\"";
  
 if ($couponItem[collection]=="$listItem[collection]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[collection]);
 echo "$name</option>";}?>
        </select></td>
      </tr>
      
        <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">By
            Coating:
          <input name="select_by" type="radio" value="coating" <?php  if (($couponItem[select_by]=='coating')||($couponItem[select_by]=='') ) 
 echo "checked=\"checked\""; ?>/>
        </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="coating" id="coating" class="formField">
            <?php
  $query="SELECT distinct coating FROM exclusive WHERE prod_status='active' order by coating"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItem[coating]\"";
  
 if ($couponItem[coating]=="$listItem[coating]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[coating]);
 echo "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">By
            Product :
            <input name="select_by" type="radio" value="product"<?php  if ($couponItem[select_by]=='product') echo "checked=\"checked\""; ?> />
        </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="product_name" id="product_name" class="formField">
          <?php
  $query="select product_name from exclusive group by product_name asc"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItem[product_name]\"";
  
 if ($couponItem[product_name]=="$listItem[product_name]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[product_name]);
 echo "$name</option>";}?>
        </select></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">All Products :
            <input name="select_by" type="radio" value="all"<?php  if ($couponItem[select_by]=='all') echo "checked=\"checked\""; ?> />
        </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">&nbsp;</td>
      </tr>
      
          
     
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">By
            System:
          <input name="select_by" type="radio" value="system" <?php  if ($couponItem[select_by]=='system') 
 echo "checked=\"checked\""; ?>/>
        </font></td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">
        <select name="system" id="system" class="formField">
        	<option value="directlens"  <?php if ($couponItem[system]=="directlens")  echo "selected";?>>Direct-Lens</option>
        	<option value="lensnetclub" <?php if ($couponItem[system]=="lensnetclub") echo "selected";?>>Lensnet Club</option>
        	<option value="aitlensclub" <?php if ($couponItem[system]=="aitlensclub") echo "selected";?>>AIT Lens Club</option>
            <option value="mybbgclub"   <?php if ($couponItem[system]=="mybbgclub")   echo "selected";?>>Mybbg Club</option>
        	<option value="ifcclubca" 	<?php if ($couponItem[system]=="ifcclubca")   echo "selected";?>>IFC.ca</option>
        </select></td>
      </tr>
      






 
      
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Arial, Helvetica, sans-serif">Amount:<br />
        (to be subtracted<br /> 
        from order) </font></p>        </td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">$</font><font size="1"><input name="amount" type="text" id="amount" value="<?php echo $couponItem[amount];?>" size="6" />
        </font></td>
      </tr>
      
     
     <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><p><font size="1" face="Arial, Helvetica, sans-serif">Description:<br />
         </font></p>      </td>
         <td><input name="description" type="text" id="description" value="<?php echo $couponItem[description];?>" maxlength="25" size="25" />  </td>
      </tr>
      
      
      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" bgcolor="#DDDDDD"><input name="from_coupon_form" type="hidden" id="from_coupon_form" value="<?php if ($_GET[edit]=='true') echo 'update'; else echo 'add';?>" />
          &nbsp;
          <input name="pkey" type="hidden" id="pkey" value="<?php echo $_GET[pkey];?>" />
          &nbsp;
  <input name="Submit" type="submit" value="<?php if ($_GET[edit]=='true') echo 'Update Coupon Code';else echo 'Add Coupon Code';?>" />
  &nbsp;</td>
      </tr>
    </table>
</form>
  <SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
