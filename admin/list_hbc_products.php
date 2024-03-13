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

$FiltreCollection=$_REQUEST[collection];
	switch($FiltreCollection){
		case 'HBC_STC':		$ConditionFiltreParCollection = " AND COLLECTION='HBC STC'";  		break;//Saint-Catharines
		case 'HBC_SURFACE':	$ConditionFiltreParCollection = " AND COLLECTION='HBC SURFACE'";  	break;//HKO
		case 'HBC_STOCK':	$ConditionFiltreParCollection = " AND COLLECTION='HBC STOCK'";		break;//GKB
		default: $ConditionFiltreParCollection = " ";
	}//End Switch



	if($_GET[sort_by]!=""){
		$query="SELECT * FROM ifc_ca_exclusive WHERE prod_status='active' $ConditionFiltreParCollection  ORDER BY ".$_GET[sort_by];
		$_GET[sort_by]="";
	}else{
		$query="SELECT * FROM ifc_ca_exclusive WHERE prod_status='active' $ConditionFiltreParCollection  ORDER BY collection, product_name";
	}
//echo '<br>'.$query;
	
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
$edit_products	= "yes";
	/*
if ($_SESSION["access_admin_id"] <> ""){
	$queryAccess = "SELECT * FROM access_admin WHERE id=" . $_SESSION["access_admin_id"];
	$resultAccess=mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
	$AccessData=mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);	
	$edit_products	= $AccessData[edit_products];
}	*/
?>

		   <table width="100%" border="0" cellpadding="2" cellspacing="0">
            	<tr bgcolor="#000000">
            		<td colspan="14" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">HBC EXCLUSIVES PRODUCTS</font></b></td>
       		  </tr></table>
              <br>
		<div align="center">
			<a href='list_hbc_products.php'>All Products</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='list_hbc_products.php?collection=HBC_STC'>HBC STC</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='list_hbc_products.php?collection=HBC_SURFACE'>HBC SURFACE(HKO)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href='list_hbc_products.php?collection=HBC_STOCK'>HBC STOCK(GKB)</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
	
				<br>
        <div align="center">
        	<label for="filter">Filtre en direct</label>
	    	<input type="text" name="filter" value="" id="filter" />
  	    </div>
              
              
              <div id="displayBox">
            	  <table width="100%" border="0" cellpadding="2" cellspacing="0" id="tabletosort"  class="formField1"> 
                  <thead>
                     <tr>
                         <th>Key</th>
                         <th>Collection</th>
                         <th>Product</th>
                         <th>Index</th>
                         <th>Coating</th>
                         <th>Photo</th>
                         <th>Polar</th>
                         <th>Price CAD</th>	
                         <th>COST US</th>
                         <th>Manufacturer</th>	
                         <th>Status</th>
                         <th>Edit/Delete</th>  
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

			if (($catData2[cost_us] <> 0) && ($catData2[cost_us] > $catData2[price_can] )){
				$bgcolor="#F51418";	
			}


					 echo "<tr bgcolor=\"$bgcolor\">
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[primary_key]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[collection]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[product_name]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[index_v]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[coating]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[photo]</td>
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[polar]</td> 
					 <td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[price_can]</td>";
					
					$bgcolor="#ff0000";
					if ($catData2[cost_us]<>0)
						echo "<td  bgcolor=\"$bgcolor\"><font  size=\"2\" face=\"Arial, Helvetica, sans-serif\"><b>$catData2[cost_us]</b></td>";
					else
						echo "<td><font size=\"2\" face=\"Arial, Helvetica, sans-serif\"><b>$catData2[cost_us]</b></td>";	
					
					echo"<td><font sizse=\"2\" face=\"Arial, Helvetica, sans-serif\">&nbsp;$catData2[real_manufacturer]</td>
					 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">$catData2[prod_status]</td>
					 <td align=\"center\"><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
					 
					   if ($edit_products == "yes")
					echo " <a href=\"update_exclusive_product_hbc.php?pkey=$catData2[primary_key]\">Edit</a>";
					
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