<form name="form4" method="post" action="newFrameCollection.php"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
      <tr bgcolor="#000000">
        <td colspan="2" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Frame Collection</font></b></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap">Collection Name:</td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">
          <input name="collection_name" type="text" id="collection_name" value="<?php echo $collectionItem[collection_name];?>" size="40" />
          &nbsp;&nbsp;&nbsp;&nbsp;Active:
          <input name="frame_collection_status" type="checkbox" id="frame_collection_status" value="active" <?php if ($collectionItem[frame_collection_status]=="active"){echo " checked=\"checked\"";} ?>"/></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD">Description:</td>
        <td align="left" bgcolor="#DDDDDD">
          <textarea name="collection_description" cols="40" rows="2" id="collection_description" class="formText"><?php echo $collectionItem[collection_description];?></textarea>
  </td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">Frame Price:</td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">USA: $
          <input name="price_US" type="text" id="price_US" value="<?php echo $collectionItem[price_US];?>" size="6" />
&nbsp;&nbsp;Canada: $
<input name="price_CA" type="text" id="price_CA" value="<?php echo $collectionItem[price_CA];?>" size="6" />
&nbsp;&nbsp;Euro: $
<input name="price_EUR" type="text" id="price_EUR" value="<?php echo $collectionItem[price_EUR];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">High Index Addition 1.50:</td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">USA: $
          <input name="US150" type="text" id="US150" value="<?php echo $collectionItem[US150];?>" size="6" />
          &nbsp;Canada: $
          <input name="CA150" type="text" id="CA150" value="<?php echo $collectionItem[CA150];?>" size="6" />
          &nbsp;&nbsp;Euro: $
          <input name="EUR150" type="text" id="EUR150" value="<?php echo $collectionItem[EUR150];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">High Index Addition 1.53:</td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">USA: $
          <input name="US153" type="text" id="US153" value="<?php echo $collectionItem[US153];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA153" type="text" id="CA153" value="<?php echo $collectionItem[CA153];?>" size="6" />
          &nbsp;&nbsp;Euro: $
        <input name="EUR153" type="text" id="EUR153" value="<?php echo $collectionItem[EUR153];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">High Index Addition 1.56:</td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">USA: $
          <input name="US156" type="text" id="US156" value="<?php echo $collectionItem[US156];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA156" type="text" id="CA156" value="<?php echo $collectionItem[CA156];?>" size="6" />
          &nbsp;&nbsp;Euro: $
          <input name="EUR156" type="text" id="EUR156" value="<?php echo $collectionItem[EUR156];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">High Index Addition 1.60:</td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">USA: $
          <input name="US160" type="text" id="US160" value="<?php echo $collectionItem[US160];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA160" type="text" id="CA160" value="<?php echo $collectionItem[CA160];?>" size="6" />
          &nbsp;&nbsp;Euro: $
        <input name="EUR160" type="text" id="EUR160" value="<?php echo $collectionItem[EUR160];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">High Index Addition 1.67:</td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">USA: $
          <input name="US167" type="text" id="US167" value="<?php echo $collectionItem[US167];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA167" type="text" id="CA167" value="<?php echo $collectionItem[CA167];?>" size="6" />
          &nbsp;&nbsp;Euro: $
        <input name="EUR167" type="text" id="EUR167" value="<?php echo $collectionItem[EUR167];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">High Index Addition 1.70:</td>
        <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">USA: $
          <input name="US170" type="text" id="US170" value="<?php echo $collectionItem[US170];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA170" type="text" id="CA170" value="<?php echo $collectionItem[CA170];?>" size="6" />
          &nbsp;&nbsp;Euro: $
        <input name="EUR170" type="text" id="EUR170" value="<?php echo $collectionItem[EUR170];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">High Index Addition 1.74:</td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">USA: $
          <input name="US174" type="text" id="US174" value="<?php echo $collectionItem[US174];?>" size="6" />
          &nbsp;&nbsp;Canada: $
          <input name="CA174" type="text" id="CA174" value="<?php echo $collectionItem[CA174];?>" size="6" />
          &nbsp;&nbsp;Euro: $
        <input name="EUR174" type="text" id="EUR174" value="<?php echo $collectionItem[EUR174];?>" size="6" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td colspan="2" align="left" nowrap="nowrap" bgcolor="#BBBBBB"><strong>Available Prescription Collections:
        </strong></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td colspan="2" align="center" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><?php
		
	$collections=array();
	$collections=explode(";",$collectionItem[avail_prescript_collections]);
	
	$collectionNum=count($collections);
	
  $query="SELECT collection FROM exclusive GROUP BY collection asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
echo "<table width=\"100%\" cellpadding\"0\" cellspacing=\"0\" class=\"formField\"><tr>";
 while ($listItem=mysql_fetch_array($result)){
	 $c_count++;
	  if (($c_count%3)==0){
	echo "<td align=\"right\">".$listItem[collection];
	echo " <input name=\"collection[".$c_count."]\" type=\"checkbox\" value=\"".$listItem[collection]."\" ";
		for($i=0;$i<$collectionNum;$i++){
			if ($collections[$i]==$listItem[collection]){
				echo " checked";}}
	echo "/></td></tr><tr>";
	 }
	 else{
		 
	echo "<td align=\"right\">".$listItem[collection];
	echo " <input name=\"collection[".$c_count."]\" type=\"checkbox\" value=\"".$listItem[collection]."\" ";
		for($i=0;$i<$collectionNum;$i++){
			if ($collections[$i]==$listItem[collection]){
				echo " checked";}}
	echo "/></td>";
	 }
	
 }
 echo "</tr></table>";
	 
	 ?></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td colspan="2" valign="middle" nowrap="nowrap" bgcolor="#BBBBBB"><strong>Available Temple Colors:</strong></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td colspan="2" align="center" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><?php
		
	$colors=array();
	$colors=explode(";",$collectionItem[avail_colors]);
	
	$collection_codes=array();
	$collection_codes=explode(";",$collectionItem[color_collection_code]);
	
	$colorNum=count($colors);
	
  $query="SELECT * FROM  frames_colors ORDER BY frames_colors_id"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
echo "<table width=\"100%\" cellpadding\"0\" cellspacing=\"0\" class=\"formField\"><tr>";
 while ($listItem=mysql_fetch_array($result)){
	 $count++;
	 
	 if (($count%5)==0){
		 echo "<td align=\"right\">".$listItem[collection_code]."-".$listItem[frame_color];
		echo " <input name=\"collection_code[".$count."]\" type=\"hidden\" value=\"".$listItem[collection_code]."\" />";
		 echo " <input name=\"color[".$count."]\" type=\"checkbox\" value=\"".$listItem[frame_color]."\"";
		 
		for($i=0;$i<$colorNum;$i++){
			if (($colors[$i]==$listItem[frame_color])&&($collection_codes[$i]==$listItem[collection_code])){
				echo " checked";}}
		 echo "/></td></tr><tr>";
	 }
	 else{
		  echo "<td align=\"right\">".$listItem[collection_code]."-".$listItem[frame_color];
		echo " <input name=\"collection_code[".$count."]\" type=\"hidden\" value=\"".$listItem[collection_code]."\" />";
		  		 echo " <input name=\"color[".$count."]\" type=\"checkbox\" value=\"".$listItem[frame_color]."\"";
		 
		for($i=0;$i<$colorNum;$i++){
			if (($colors[$i]==$listItem[frame_color])&&($collection_codes[$i]==$listItem[collection_code])){
				echo " checked";}}
		 echo " /></td>";
	 }
 }
 echo "</tr></table>";
	 
	 ?></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" bgcolor="#FFFFFF">CURRENT IMAGE:<?php if ($collectionItem[collection_image]!=""){echo "<br/><input type=\"checkbox\" name=\"remove_image\" value=\"Yes\">
      Remove Image";}?></td>
        <td align="left" bgcolor="#FFFFFF"><?php if ($collectionItem[collection_image]!=""){
	echo "<img src=\"../frames_images/$collectionItem[collection_image].jpg\" align=\"left\">";
}
	else {echo "NONE";}?></td>
      </tr>
      <tr valign="top" bgcolor="#FFFFFF" class="formLabel">
        <td align="right" bgcolor="#FFFFFF">Upload picture:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="image_name" type="file" class="formText" id="image_name" size="10" />
          &nbsp; </td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" bgcolor="#BBBBBB"><input name="from_form" type="hidden" id="from_form" value="edit" />
          <input name="current_image_name" type="hidden" id="current_image_name" value="<?php echo $collectionItem[collection_image];?>" />
          <input name="color_count" type="hidden" id="color_count" value="<?php echo $count;?>" />
          <input name="collection_count" type="hidden" id="collection_count" value="<?php echo $c_count;?>" />
          <input name="pkey" type="hidden" id="pkey" value="<?php echo $_GET[pkey];?>" />
  <input name="Submit" type="submit" value="UPDATE" />
  &nbsp;</td>
      </tr>
    </table>
</form>