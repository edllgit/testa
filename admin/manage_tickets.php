<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
session_start();

if ($_SESSION[adminData][username]==""){
print "You are not logged in. Click <a href='".constant('DIRECT_LENS_URL')."/admin'>here</a> to login.";
exit();
}
require('../Connections/sec_connect.inc.php');
$user_id=$_SESSION["sessionUser_Id"];

$query="SELECT * from tickets WHERE status = 'open' ";
$_SESSION["QUERY"]=$query;


?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens &mdash; Support Tickets</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css">

<script src="formFunctions.js" type="text/javascript"></script>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn"><?php 
	include("adminNav.php");
	?></div></td>
    <td width="685" valign="top"><form  method="post" name="goto_date" id="goto_date" action="manage_tickets.php">
	
      <div class="header"><?php echo 'Support Tickets';?>  </div><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
            <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
            	<tr bgcolor="#000000">
            		<td colspan="4" align="right" bgcolor="#000099" class="formCell">&nbsp;</td>
            		</tr>
			</table>
</form>
<form  method="post" name="search_form" id="search_form" action="tickets.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				
			</table>
</form><form  method="post" name="search_form" id="search_form" action="tickets.php">
  <table width="650" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
				<tr bgcolor="#DDDDDD">
					<td width="23%" bgcolor="#FFFFFF" class="formCellNosides" ><div align="right">
						<?php echo 'Support ticket #';?>
					</div></td>
					<td width="26%" align="left" bgcolor="#FFFFFF" class="formCellNosides"><input name="patient_ref_num" type="text" id="patient_ref_num" size="12" class="formText"></td>
					<td width="51%" align="right" bgcolor="#FFFFFF" class="formCellNoleft" >
					  <input name="rpt_search" type="submit" id="rpt_search" value="Search by ticket #" class="formText">
				
					  <input name="from_form_patient_ref" type="hidden" id="from_form_patient_ref" value="true"></td>
				</tr>
			</table>
</form>
			
			<?php 
			
			if ($query!=""){
				$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($result);
			}
			
			

print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\"><tr >
                <td colspan=\"6\" bgcolor=\"#000098\" class=\"tableHead\">".$message."</td><td colspan=\"2\" bgcolor=\"#000098\" ></td>";
  print "</tr>
              <tr>
			   <td align=\"center\" class=\"formCell\"><b>#</b></td>
			   <td align=\"center\" class=\"formCell\"><b>Priorité</b></a></td>
                <td align=\"center\" class=\"formCell\"><b>Titre</b></td>
                <td align=\"center\" class=\"formCell\"><b>Auteur</b></a></td>
                <td align=\"center\" class=\"formCell\"><b>Email</b></a></td>
				<td align=\"center\" class=\"formCell\"><b>Lab/User ID</b></td>
				<td align=\"center\" class=\"formCell\"><b>Date</b></td>
				<td align=\"center\" class=\"formCell\"><b>Status</b></td>
              </tr>";
			  
while ($listItem=mysql_fetch_array($result)){

	
		print  "<tr>
                <td align=\"center\"  class=\"formCell\">$listItem[ticket_id]";
				
				if ($listItem['redo_order_num']!=0) print "<b>R</b></a>";
				else print "&nbsp;&nbsp;";
				
				print"</td>
				<td align=\"center\"  class=\"formCell\">$listItem[priority]</td>
				<td align=\"center\"  class=\"formCell\">$listItem[title]</td>
                <td align=\"center\"  class=\"formCell\">$listItem[author]</td>	
				<td align=\"center\"  class=\"formCell\">$listItem[email]</td>";
				
				if ($listItem[lab_id] != 0){
				print "	<td align=\"center\"  class=\"formCell\">$listItem[lab_id]</td>";
				}
				
				if ($listItem[user_id] != ""){
				print "	<td align=\"center\"  class=\"formCell\">$listItem[user_id]</td>";
				}
				
				if (($listItem[user_id] == "") && ($listItem[lab_id] == 0)){
				print "	<td align=\"center\"  class=\"formCell\">&nbsp;</td>";
				}
				
				
			print "	<td align=\"center\"  class=\"formCell\">$listItem[date]</td>	
			<td align=\"center\"  class=\"formCell\">$listItem[status]</td>";

              print "</tr>";
			  		
		}//END WHILE
		print "</table><br>";

?></td>
  </tr>
</table>
		 </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>

<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFFFFF;layer-background-color:white;"></DIV>
</body>
</html>
