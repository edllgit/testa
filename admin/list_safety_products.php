<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("prod_functions.inc.php");

session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



$type=$_GET[category];


	if($_GET[sort_by]!=""){
		$query="select * from safety_exclusive where prod_status='active' and collection  like '%Safety%' order by ".$_GET[sort_by];
		$_GET[sort_by]="";
		}
	else{
		$query="select * from safety_exclusive where prod_status='active' and  collection  like '%Safety%' order by collection, product_name";
		}

$catResult=mysqli_query($con,$query)	or die ( "Query failed: " . mysqli_error($con));

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css">
<script src="../includes/jquery-1.2.6.min.js"></script>
<script src="../includes/jquery.tablesorter.js"></script>
  
  <script>
 $(function(){
  $('#tabletosort').tablesorter(); 
});
  </script>
</head>

<body>
		
		<?php
$edit_products				= "yes";

if ($_SESSION["access_admin_id"] <> ""){
	$queryAccess = "SELECT * FROM access_admin WHERE id=" . $_SESSION["access_admin_id"];
	$resultAccess=mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
	$AccessData=mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);	
	$edit_products	= $AccessData[edit_products];
}	
?>

		   <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">SAFETY EXCLUSIVES PRODUCTS</font></b></td>
       		  </tr></table><div id="displayBox">
            	
                 <div align="center">
        	<label for="filter">Filtre en direct</label>
	    	<input type="text" name="filter" value="" id="filter" />
  	    </div>
                
                <table width="100%" border="0" cellpadding="2" cellspacing="0" id="tabletosort"  class="formField1"> 
                  <thead>
                     <tr>
                         <th>Collection</th>
                         <th>Product</th>
                         <th>Index</th>
                         <th>Coating</th>
                         <th>Photo</th>
                         <th>Polar</th>
                         <th>Price</th>
                         <th>Price Discounted</th>
                         <th>Price Interco</th>	
                         <th>Status</th>
                         <th>Edit</th>  
                     </tr>	
                </thead>
                <tbody>
            	<?php
				while($catData2=mysqli_fetch_array($catResult,MYSQLI_ASSOC)){
					$count++;
					if (($count%2)==0)
   						$bgcolor="#DDDDDD";
					else 
						$bgcolor="#FFFFFF";

					 echo "<tr bgcolor=\"$bgcolor\">
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[collection]</td>
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[product_name]</td>
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[index_v]</td>
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[coating]</td>
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[photo]</td>
							 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[polar]</td>
							 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price]</td>
							 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_discounted]</td>
							 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_interco]</td>
							 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[prod_status]</td>
							 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					 
					   if ($edit_products == "yes")
					   echo " <a href=\"update_exclusive_product_safety.php?pkey=$catData2[primary_key]\">Edit</a>";
					
				echo " </td></tr>";
				}
				?>
                </tbody>
				</table>
                </div>
  <p>&nbsp;</p>
<script src="../labAdmin/js/ajax.js"></script>   
</body>
</html>