<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if ($_POST['from_update']=="true"){

$Reward_Infocus = $_POST['Reward_Infocus'];
$Reward_MyWorld = $_POST['Reward_MyWorld'];
$Reward_VisionPro = $_POST['Reward_VisionPro'];
$Reward_Precision = $_POST['Reward_Precision'];
$Reward_VisionProPoly = $_POST['Reward_VisionProPoly'];
$Reward_Other = $_POST['Reward_Other'];
$Reward_Generation = $_POST['Reward_Generation'];
$Reward_TrueHD = $_POST['Reward_TrueHD'];
$Reward_EasyFitHD = $_POST['Reward_EasyFitHD'];

$Reward_Private1 = $_POST['Reward_Private1'];
$Reward_Private2 = $_POST['Reward_Private2'];
$Reward_Private3 = $_POST['Reward_Private3'];
$Reward_Private4 = $_POST['Reward_Private4'];
$Reward_Private5 = $_POST['Reward_Private5'];

$Reward_Rodenstock = $_POST['Reward_Rodenstock'];
$Reward_Rodenstock_HD = $_POST['Reward_Rodenstock_HD'];

$Reward_Vot = $_POST['Reward_Vot'];
$Reward_Eco = $_POST['Reward_Eco'];
$Reward_Glass = $_POST['Reward_Glass'];
$Reward_Glass2 = $_POST['Reward_Glass2'];

		//Infocus
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Infocus' WHERE collection_name = 'Infocus'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//My World
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_MyWorld' WHERE collection_name = 'My World'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Vision Pro
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_VisionPro' WHERE collection_name = 'Vision Pro'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Precision
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Precision' WHERE collection_name = 'Precision'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Vision Pro Poly
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_VisionProPoly' WHERE collection_name = 'Vision Pro Poly'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Other
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Other' WHERE collection_name = 'Other'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Generation
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Generation' WHERE collection_name = 'Generation'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//TrueHD
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_TrueHD' WHERE collection_name = 'TrueHD'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Easy Fit HD
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_EasyFitHD' WHERE collection_name = 'Easy Fit HD'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		
		//Private 1
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Private1' WHERE collection_name = 'Private 1'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Private 2
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Private2' WHERE collection_name = 'Private 2'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Private 3
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Private3' WHERE collection_name = 'Private 3'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Private 4
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Private4' WHERE collection_name = 'Private 4'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Private 5
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Private5' WHERE collection_name = 'Private 5'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		
		//Rodenstock
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Rodenstock' WHERE collection_name = 'Rodenstock'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Rodenstock Hd
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Rodenstock_HD' WHERE collection_name = 'Rodenstock HD'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		
		//Glass
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Glass' WHERE collection_name = 'Glass'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Glass 2
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Glass2' WHERE collection_name = 'Glass 2'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//VOT
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Vot' WHERE collection_name = 'Vot'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		
		//Eco
		$query=("UPDATE collection_rewards SET reward_points ='$Reward_Eco' WHERE collection_name = 'Eco'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());



}
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="50%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td colspan="6" align="center" class="formField1"><span class="formField2 style2">Collections Rewards
            		       Pricing </span></td>
       		  </tr>
			</table>

<table width="100%" border="1" cellpadding="4" cellspacing="0" class="formField2">
   
<form action="reward_pricing.php" method="post" name="form"><tr bgcolor="#DDDDDD">


<!--Infocus-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Infocus'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Infocus</b></td>
<td align=\"center\">Reward:<input name="Reward_Infocus" type="text" size="2" value="<?php echo $Data['reward_points']; ?>" size="5" class="formField2"/>pts</td>	
</tr>

<!--My World-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='My World'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>My World</b></td>
<td align=\"center\">Reward:<input  name="Reward_MyWorld" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>


<!--Vision Pro-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Vision Pro'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Vision Pro</b></td>
<td align=\"center\">Reward:<input name="Reward_VisionPro" type="text" size="2" value=<?php echo $Data['reward_points']; ?>  class="formField2"/>pts</td>	
</tr>



<!--Precision-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Precision'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Precision</b></td>
<td align=\"center\">Reward:<input name="Reward_Precision" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Vision Pro Poly-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Vision Pro Poly'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Vision Pro Poly</b></td>
<td align=\"center\">Reward:<input name="Reward_VisionProPoly" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Other-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Other'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Other</b></td>
<td align=\"center\">Reward:<input name="Reward_Other" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Generation-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Generation'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Generation</b></td>
<td align=\"center\">Reward:<input name="Reward_Generation" type="text" size="2" value=<?php echo $Data['reward_points']; ?>  size="5"  class="formField2"/>pts</td>	
</tr>




<!--TrueHD-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='TrueHD'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>TrueHD</b></td>
<td align=\"center\">Reward:<input name="Reward_TrueHD" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Easy Fit HD-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Easy Fit HD'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Easy fit HD</b></td>
<td align=\"center\">Reward:<input name="Reward_EasyFitHD" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>






<!--Private 1-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Private 1'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Private 1</b></td>
<td align=\"center\">Reward:<input name="Reward_Private1" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Private 2-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Private 2'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Private 2</b></td>
<td align=\"center\">Reward:<input name="Reward_Private2" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Private 3-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Private 3'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Private 3</b></td>
<td align=\"center\">Reward:<input name="Reward_Private3" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Private 4-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Private 4'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Private 4</b></td>
<td align=\"center\">Reward:<input name="Reward_Private4" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Private 5-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Private 5'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Private 5</b></td>
<td align=\"center\">Reward:<input name="Reward_Private5" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Glass-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Glass'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Glass</b></td>
<td align=\"center\">Reward:<input name="Reward_Glass" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Glass 2-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Glass 2'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Glass 2</b></td>
<td align=\"center\">Reward:<input name="Reward_Glass2" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Rodenstock-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Rodenstock'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Rodenstock</b></td>
<td align=\"center\">Reward:<input name="Reward_Rodenstock" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Rodenstock HD-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Rodenstock HD'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Rodenstock HD</b></td>
<td align=\"center\">Reward:<input name="Reward_Rodenstock_HD" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




<!--Vot-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Vot'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Vot</b></td>
<td align=\"center\">Reward:<input name="Reward_Vot" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>

<!--Eco-->
<tr>
<?php 
$Query="SELECT reward_points from collection_rewards WHERE collection_name='Eco'";
$Result=mysql_query($Query)	or die ("Could not find product prices");
$Data=mysql_fetch_array($Result);
?>
<td align="right"><b>Eco</b></td>
<td align=\"center\">Reward:<input name="Reward_Eco" type="text" size="2" value=<?php echo $Data['reward_points']; ?> size="5" class="formField2"/>pts</td>	
</tr>




</table>

<?php 
echo "<input type=\"hidden\" name=\"from_update\" value=\"true\" /><p align=\"center\"><input align=\"center\" name=\"updateDisc\" type=\"submit\" value=\"Update Rewards\" class=\"formField2\" /></p></form>";
?>
</td>
	  </tr>
</table>

</body>
</html>
