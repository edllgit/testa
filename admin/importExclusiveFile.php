<?php
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");



?>
<html>
<head>
<title>Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link href="admin.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style1 {
	font-family: Helvetica, sans-serif, Arial;
	font-weight: bold;
	font-size: 12px;
}
.style2 {
	font-family: Helvetica, sans-serif, Arial;
	font-size: 12px;
}
.style3 {
	font-family: Helvetica, sans-serif, Arial;
	font-size: 10px;
}
-->
</style>
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="200" class="rightBord"><?php
		include("adminNav.php");
		?></td>
  		
  		<td  class="rightBord"><form action="importExclusiveFile.php" method="post" enctype="multipart/form-data" name="form4">
            
        <table width="100%" border="0" cellpadding="2" cellspacing="0">
          <tr> 
            <td align="center" bgcolor="#000000"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">ADMIN
              IMPORT ITEM LIST FROM FILE</font></b></td>
          </tr>
          <tr>
            <td align="right" nowrap class="formLabels">&nbsp;</td>
          </tr>
          <tr>
            <td class="formLabels">&nbsp;<?php if ($message!="") echo $message; ?></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="center" nowrap class="formLabels">&nbsp;</td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="center" class="formLabels"><input name="uploadFile" type="file" class="formLabels" id="uploadFile" Value="Select a File to Upload" size="30"></td>
          </tr>
          <tr bgcolor="#DDDDDD">
            <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
          
          <tr> 
            <td class="formLabels"><div align="center" class="style1">Import
                file must be a comma delimited csv file with text fields qualified
                with &quot; (double-quotes).</div></td>
          </tr>
          
          <tr bgcolor="#FFFFFF">
            <td class="formLabels"><div align="center" class="style2">Note: records are appended to existing database, they do not replace existing records.</div></td>
          </tr>
          <tr bgcolor="#FFFFFF">
            <td align="center" nowrap class="formLabels">&nbsp;</td>
          </tr>
          <tr bgcolor="#DDDDDD">
            <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
          <tr bgcolor="#DDDDDD"> 
            <td align="center"><input name="Submit" type="submit" class="formLabels" id="add" value="Submit"> 
              
            <input name="from_import_file" type="hidden" id="from_import_file" value="true">
            &nbsp;&nbsp;&nbsp;
            <input name="test_format" type="checkbox" id="test_format" value="true" checked>
            <span class="style3">Test file format without adding to database. </span></td>
          </tr>
        </table>
  		</form>
		  <?php  
		  
		  if ($_POST[from_import_file]=="true"){
	
		if (isset($HTTP_POST_FILES['uploadFile']['tmp_name'])){/* if admin has selected a file to upload */
			$checkfile=($HTTP_POST_FILES['uploadFile']['name']);
			$fileToUpload=($HTTP_POST_FILES['uploadFile']['tmp_name']);
			//$upload_file_path="/home/content/m/u/s/muskie00/html/leatherheadtools/admin/upload_temp/";
			$upload_file_path="/home/direc54/public_html//admin/upload_temp/";
			$upload_file_path .= ($HTTP_POST_FILES['uploadFile']['name']);
			
			if ($checkfile==""){
				$message="<center><font color=\"red\">You did not select a file.</font></center>";
			}else{
				if(!copy($fileToUpload, $upload_file_path)){
					$message="<center><font color=\"red\">File was not uploaded.</font></center>";
					}
				else{
			
					$fp=fopen("$upload_file_path", "r");
	
				
				if ($_POST[test_format]=="true"){
						echo "<table cellpadding='3' cellspacing='0'  border='1' class='formField' width='100%'>";
					}
					while (($data=fgetcsv($fp,4096,",",'"'))!==FALSE) {
    				$num=count($data);
    				$row++;
					
					$product_code=$data[1];
					$color_code=$data[2];
					$product_name=$data[3];
					$collection=$data[4];
					$price=$data[5];
					$price_can=$data[6];
					$price_eur=$data[7];
					$e_lab_us_price=$data[8];
					$e_lab_can_price=$data[9];
					$description=$data[10];
					$manufacturer=$data[11];
					$abbe=$data[12];
					$density=$data[13];
					$sphere_min=$data[14];
					$sphere_max=$data[15];
					$sphere_over_max=$data[16];
					$sphere_over_min=$data[17];
					$cyl_over_min=$data[18];
					$cyl_min=$data[19];
					$cyl_max=$data[20];
					$add_min=$data[21];
					$add_max=$data[22];
					$index_v=$data[23];
					$coating=$data[24];
					$photo=$data[25];
					$polar=$data[26];
					$prod_status=$data[27];

		$query="insert into exclusive (product_code,color_code,product_name,collection,price,price_can,price_eur,e_lab_us_price,e_lab_can_price,description,manufacturer,abbe,density,index_v,coating,photo,polar,sphere_max,sphere_min,sphere_over_max,sphere_over_min,cyl_max,cyl_min,cyl_over_min,add_max,add_min,prod_status) values ('$product_code','$color_code','$product_name','$collection','$price','$price_can','$price_eur','$e_lab_us_price','$e_lab_can_price','$description','$manufacturer','$abbe','$density','$index_v','$coating','$photo','$polar','$sphere_max','$sphere_min','$sphere_over_max','$sphere_over_min','$cyl_max','$cyl_min','$cyl_over_min','$add_max','$add_min','$prod_status')";
					
						if ($_POST[test_format]=="true"){
							if (($num!=28)&&($FLAG!="true")){
								$FLAG=true;
								echo "<tr><td colspan='$num'><center><font color=\"red\"><b>INCORRECT NUMBER OF FIELDS. 28 REQUIRED, $num FOUND.</b></font></center></td></tr>";
							
							}
							echo "<tr>";
								for ($i=0;$i<$num;$i++){
					
								echo "<td>".$data[$i]."&nbsp;</td>";
					
								}
							echo "</tr>";
							}
						else{
							$result=mysql_query($query)
								or die ("Could not add items because".mysql_error());
						}//END IF TEST FORMAT
					}// END OFWHILE
					
					if ($_POST[test_format]=="true"){
							echo "</table>";
							}
					else{
						echo "<center><font color=\"red\">File uploaded successfully.<br>Number of records added: $row.</font></center>";
					}
					
					fclose($fp);
					if (is_file($upload_file_path)) /* delete uploaded file */
						unlink($upload_file_path);
			
				}//END COPY ELSE  
			}//END CHECKFILE ELSE
	}//FILE IS SET
}//FROm IMPORT = "TRUE"
		  ?>
</td>
    </tr>
</table>

  
</body>
</html>
