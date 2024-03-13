<form name="form4" method="post" action="newFrameColor.php"  enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formText">
      <tr bgcolor="#000000">
        <td colspan="2" align="center"><p><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">Frame Temple Colors</font></b></p></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">Frame Temple Color:</td>
        <td align="left" nowrap="nowrap" bgcolor="#DDDDDD">
          <input name="frame_color" type="text" id="frame_color" value="<?php print $colorItem[frame_color];?>" size="20" />
          &nbsp;</td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#FFFFFF">Collection Code:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="collection_code" type="text" id="collection_code" value="<?php print $colorItem[collection_code];?>" size="20" /></td>
      </tr>
      <tr bgcolor="#DDDDDD">
        <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">Temple Model Number:</td>
        <td align="left" bgcolor="#DDDDDD"><input name="temple_model_num" type="text" id="temple_model_num" value="<?php print $colorItem[temple_model_num];?>" size="20" /></td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td align="right" valign="top" bgcolor="#FFFFFF">CURRENT IMAGE:<?php if ($colorItem[frames_colors_image]!=""){print "<br/><input type=\"checkbox\" name=\"remove_image\" value=\"Yes\">
      Remove Image";}?></td>
        <td align="left" bgcolor="#FFFFFF"><?php if ($colorItem[frames_colors_image]!=""){
	print "<img src=\"../frames_images/$colorItem[frames_colors_image].jpg\" align=\"left\">";
}
	else {print "NONE";}?></td>
      </tr>
      <tr valign="top" bgcolor="#FFFFFF" class="formLabel">
        <td align="right" bgcolor="#FFFFFF">Upload picture:</td>
        <td align="left" bgcolor="#FFFFFF"><input name="image_name" type="file" class="formText" id="image_name" size="10" />
          &nbsp; </td>
      </tr>
      <tr bgcolor="#FFFFFF">
        <td colspan="2" align="center" bgcolor="#BBBBBB"><input name="from_form" type="hidden" id="from_form" value="edit" />
          <input name="current_image_name" type="hidden" id="current_image_name" value="<?php print $colorItem[frames_colors_image];?>" />
          <input name="pkey" type="hidden" id="pkey" value="<?php print $_GET[pkey];?>" />
  <input name="Submit" type="submit" value="UPDATE" />
  &nbsp;</td>
      </tr>
    </table>
</form>
