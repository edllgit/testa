<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

if ($_POST[from_upload_form]=="true"){

	if ($_FILES['image1']['tmp_name'] != ""){ /* if admin has selected a file to upload */
		$fileToUpload=($_FILES['image1']['tmp_name']);
		$upload_file_path="../promotions/";
		$name="promo.pdf";
		$upload_file_path .= $name;
		if(!copy($fileToUpload, $upload_file_path)){
			$message= "<center><font color=\"red\"size=\"2\" face=\"Helvetica, sans-serif, Arial\">File was not uploaded. $fileToUpload</font></center>";
			exit();
			}
			else{
			$message="<center><font color=\"red\" size=\"2\" face=\"Helvetica, sans-serif, Arial\">FILE UPLOADED</font></center>";}
		}
	else{
		$message="<center><font color=\"red\" size=\"2\" face=\"Helvetica, sans-serif, Arial\">Please select a file to upload.</font></center>";
		}//END IF FILE NOT EMPTY
	
	$_POST[from_upload_form]="false";
	}//END from form conditional
?>
<html>
<head>
<title>Direct-Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

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
  		<td width="75%" bgcolor="#DDDDDD"><form action="promotionsUpload.php" method="post" enctype="multipart/form-data" name="form4">
  		  <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <tr bgcolor="#000000">
              <td align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT-LENS
              ADMIN UPLOAD PROMOTION FORM </font></b></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF"><?php print $message?></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF">
                <input name="image1" type="file" id="image1">              </td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF"><input type="submit" name="Upload File" id="edit" value="Upload PDF File">
                <input name="from_upload_form" type="hidden" id="from_upload_form" value="true"><br>
                <font size="2" face="Helvetica, sans-serif, Arial">
                (2mb size limit)</font></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
            </tr>
          </table>
  		</form>
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
