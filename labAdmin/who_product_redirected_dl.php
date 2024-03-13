<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");

//DÉMARRER LA SESSION
session_start();
$collectionIN = "('Conant','CSC DL','DL Somo','Easy Fit HD','Fit','Glass','Glass 2','Glass 3','Horizon','Identity','Innovation FF SD','Innovative FF HD 2','My World','Optimize','Optimize 2','Optimize 3',
'Optimize 4','Other','Private 4','Rodenstock','Rodenstock HD','Selection Rx','STC DL','Svision','Svision 2','Vision Pro Poly','Vot','Younger Prog')";

//Search by Product Name
if($_POST["rpt_search"]=="search orders"){
	$rptQuery="SELECT * FROM exclusive 
			   WHERE collection in  $collectionIN
			   AND product_name like '%$_POST[product_name]%'
			   ORDER  BY prod_status desc, collection, product_name";
			   //echo $rptQuery;
}

//Search by Product Code
if($_POST["rpt_search"]=="search by code"){
	$rptQuery="SELECT * FROM exclusive 
			   WHERE collection in  $collectionIN
			   AND product_code like '%$_POST[product_code]%'
			   ORDER  BY prod_status desc, collection, product_name";
}

//Search by Selected Criterias
if($_POST["rpt_search"]=="search by criterias"){
	
	if ($_POST[index_v] <> "Select an Index"){ 
		$index_v = $_POST[index_v];	
		$FilterIndex = " AND index_v = $index_v ";
	}else
		$FilterIndex = " AND 1  = 1 ";
	
	if ($_POST[polar] <> "Select a color"){ 
		$polar = $_POST[polar];	
		$FilterPolar = " AND polar = '$polar' ";
	}else
		$FilterPolar = " AND 2  = 2 ";
	
	if ($_POST[photo] <> "Select a color"){ 
		$photo = $_POST[photo];	
		$FilterPhoto = " AND photo = '$photo' ";
	}else
		$FilterPhoto = " AND 3  = 3 ";
	
	if ($_POST[lens_category] <> "All"){ 
		$lens_category = $_POST[lens_category];	
		$FilterLensCat = " AND lens_category = '$lens_category' ";
	}else
		$FilterLensCat = " AND 4  = 4 ";
		
	if ($_POST[coating] <> "All"){ 
		switch($_POST[coating]){
			case 'Uncoated' :      $FilterCoating = " AND coating IN ('Uncoated')";     	   break;
			case 'Hard Coat' :     $FilterCoating = " AND coating IN ('Hard Coat')";    	   break;
			case 'Smart AR' :      $FilterCoating = " AND coating IN ('Smart AR')";     	   break;
			case 'AR+ETC' :        $FilterCoating = " AND coating IN ('ITO AR','Dream AR')"; break;
			case 'Multiclear AR' : $FilterCoating = " AND coating IN ('Multiclear AR')";    break;
			case 'Xlr' : 		   $FilterCoating = " AND coating IN ('Xlr')";    		   break;	
			case 'CrizalF' :       $FilterCoating = " AND coating IN ('CrizalF')";   	   break;
		}	
		
		
		
	}else{
		$FilterCoating = " AND 5  = 5 ";
	}
	
}//End search by criterias

	//Search by Redirection
	if($_POST["rpt_search"]=="search by redirection"){
				

	    $manufacturer = $_POST[manufacturer];
		switch($manufacturer){
			case 'Trois-Rivieres' :	  $filtreManufacturer = " collection IN ('Conant','DL SOMO','Fit','Glass 2','Horizon','Optimize','Optimize 2','Optimize 3', 
			'Optimize 4','Other','Vot','Rodenstock','Rodenstock HD','Svision 2','Younger Prog' )";  break;	
			case 'Saint-Catharines' : $filtreManufacturer = " collection IN ('STC DL', 'Svision')";  	break;
			case 'CSC' : $filtreManufacturer = " collection IN ('CSC DL')"; break;
			case 'GKB' : $filtreManufacturer = " collection IN ('Selection RX','Private 4')"; break;
			case 'HKO' : $filtreManufacturer = " collection IN ('Identity','Easy Fit HD','Glass','Innovation FF HD 2')";         break;
			case 'SWISS' :$filtreManufacturer = " collection IN ('Glass 3' ,'Vision Pro Poly','My World')"; break;
		}
			
		$rptQuery="SELECT * FROM exclusive 
					   WHERE  $filtreManufacturer
					   ORDER  BY prod_status desc, collection, product_name";
	}//End IF Search by Redirection
		
		

 //  echo $rptQuery;

?>
<html>
<head>
<title>Search where an Direct-Lens Product is currently redirected</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="../milano6769/admin.css" rel="stylesheet" type="text/css" />
<link href="charles.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript">
//Partie Mouse Over
$(document).ready(function() {// Se déclenchera automatiquement au chargement de la page
	
zebraRows('tbody tr:odd td', 'odd');
 	
$('tbody tr').hover(function(){
  $(this).find('td').addClass('hovered');
}, function(){
  $(this).find('td').removeClass('hovered');
});
});
</script>

<script type="text/javascript">
//Fonction Zebra
function zebraRows(selector, className)
{
  $(selector).removeClass(className).addClass(className);
}

//Fonction Recherche
function filter(selector, query) {
  query =   $.trim(query); //trim white space
  query = query.replace(/ /gi, '|'); //add OR for regex query
 
  $(selector).each(function() {
    ($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).hide().removeClass('visible') : $(this).show().addClass('visible');
  });
}
</script>
</head>

<body>
<form  method="post" name="Who_product_redirected1" id="Who_product_redirected1" action="who_product_redirected_dl.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            <thead>
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Search where an Direct-Lens product is currently redirected'; ?></font></b></td>
            		</tr>

				<tr align="center" bgcolor="#DDDDDD">
					<td nowrap="nowrap">
					Product Name&nbsp;&nbsp;<input name="product_name" type="text" id="product_name" size="25" class="formField">&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Search by product name'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
				</tr>


			
</form>


<form  method="post" name="Who_product_redirected2" id="Who_product_redirected2" action="who_product_redirected_dl.php">
				<tr align="center" bgcolor="#FFFFFF">
					<td nowrap="nowrap">
					Product Code&nbsp;&nbsp;<input name="product_code" type="text" id="product_code" size="25" class="formField">&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Search by product code'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search by code" class="formField"></div></td>
				</tr>


		
</form>


<form  method="post" name="Who_product_redirected3" id="Who_product_redirected3" action="who_product_redirected_dl.php">
<tr align="center" bgcolor="#DDDDDD">
<td nowrap="nowrap">
Index:
 <select name="index_v" class="formField" id="index_v">
 <option value="Select an Index">Select an Index</option>
  <option disabled>PLASTIC</option>
  <option value="1.50">1.50</option>
  <option value="1.53">1.53</option>
  <option value="1.56">1.56</option>
  <option value="1.59">1.59</option>
  <option value="1.60">1.60</option>
  <option value="1.67">1.67</option>
  <option value="1.74">1.74</option>
  <option value="1.76">1.76</option>
  <option disabled>GLASS</option>
  <option value="1.50">1.50</option>
  <option value="1.52">1.52</option>
  <option value="1.60">1.60</option>
  <option value="1.70">1.70</option>
  <option value="1.80">1.80</option>
  <option value="1.90">1.90</option>
</select>
&nbsp;&nbsp;&nbsp;
Polarized:
 <select name="polar" class="formField" id="polar">
 <option value="Select a color">Select a color</option>
	<?php
    $query      = "SELECT polar FROM exclusive WHERE collection in $collectionIN  GROUP BY polar asc"; /* select all openings */
    $result     = mysqli_query($con,$query) or die ("Could not select items");
    $usercount  = mysqli_num_rows($result);
    
    while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
        echo "<option value=\"$listItem[polar]\"";
        echo ">";
        $name = stripslashes($listItem[polar]);
        echo "$name</option>";}?>
</select>


&nbsp;&nbsp;&nbsp;
Transitions:
 <select name="photo" class="formField" id="photo">
 <option value="Select a color">Select a color</option>
	<?php
    $query      = "SELECT photo FROM exclusive WHERE collection in $collectionIN  GROUP BY photo asc"; /* select all openings */
    $result     = mysqli_query($con,$query) or die ("Could not select items");
    $usercount  = mysqli_num_rows($result);
    
    while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
        echo "<option value=\"$listItem[photo]\"";
        echo ">";
        $name = stripslashes($listItem[photo]);
        echo "$name</option>";}?>
</select>


&nbsp;&nbsp;&nbsp;
Lens Category:
 <select name="lens_category" class="formField" id="lens_category">
 <option value="All">All</option>
 <option value="bifocal">Bifocal</option>
 <option value="glass">Glass</option>
 <option value="prog cl">Progressive Classique</option>
 <option value="prog ds">Progressive DS</option>
 <option value="prog ff">Progressive FF</option>
 <option value="sv">Single Vision</option>
</select>

&nbsp;&nbsp;&nbsp;
Coating:
<select name="coating" class="formField" id="coating">
    <option value="All">All</option>
    <option disabled>PLASTIC ONLY</option>
    <option value="Hard Coat">Hard Coat</option>
    <option value="CrizalF">CrizalF</option>
    <option value="Smart AR">Smart AR</option>
    <option value="AR+ETC">AR+ETC</option>
    <option value="Xlr">Xlr</option>
    <option disabled>GLASS ONLY</option>
    <option value="Uncoated">Uncoated</option>
    <option value="Multiclear AR">Multiclear AR</option>
</select>
       
                    &nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Search products that matches my criterias'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search by criterias" class="formField"></div></td>
				</tr>
		
</form>




<form  method="post" name="Who_product_redirected3" id="Who_product_redirected3" action="who_product_redirected_dl.php">
				<tr align="center" bgcolor="#FFFFFF">
					<td nowrap="nowrap">
					See all products redirected to &nbsp;&nbsp;
                 <select name="manufacturer" class="formField" id="manufacturer">
                     <option value="CSC">CSC</option>
                     <option value="GKB">GKB/Essilor #1 Lab</option>
                     <option value="HKO">HKO/Central Lab</option>
                     <option value="Saint-Catharines">Saint-Catharines/Dlab</option>
                     <option value="SWISS">Swiss</option>
                     <option value="Trois-Rivieres">Trois-Rivieres</option>     
                 </select>
                    &nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Search by manufacturer'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search by redirection" class="formField"></div></td>
				</tr>
</form>

	</table>
    
	 <div align="center">
    	<label for="filter">Filtre en direct</label>
		<input type="text" name="filter" value="" id="filter" />
    </div>

<?php 		
if ($rptQuery!=""){
	$rptResult=mysqli_query($con,$rptQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
	$usercount=mysqli_num_rows($rptResult);
	$rptQuery="";
}	

if (($usercount != 0) && ($_POST["rpt_search"] <> '')){//some products were found
	echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
	echo "<tr>
			<th width=\"7%\" align=\"center\">Clé</th>
			<th width=\"10%\" align=\"center\">Collection</th>
			<th width=\"45%\" align=\"center\">Product</th>
			<th width=\"10%\" align=\"center\">Code</th>
			<th width=\"10%\" align=\"center\">Manufacturer</th>
			<th width=\"8%\" align=\"center\">Lens Category</th>
			<th width=\"8%\" align=\"center\">Coating</th>
			<th width=\"10%\" align=\"center\">Fitting Heights</th>
			<th width=\"10%\" align=\"center\">Addition</th>
			<th width=\"10%\" align=\"center\">Sphere Min</th>
			<th width=\"10%\" align=\"center\">Sphere Max</th>
			<th width=\"10%\" align=\"center\">Sphere Over Min</th>
			<th width=\"12%\" align=\"center\">Sphere Over Max</th>
			<th width=\"12%\" align=\"center\">Cyl Min</th>
			<th width=\"12%\" align=\"center\">Cyl Max</th>";
	echo "</tr>";

	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	if ($listItem[prod_status] == 'inactive')
	$Product = 'inactive';
	else
	$Product = 'active';
	
	
	switch($listItem[collection]){
		
		case 'Identity'    	        :  $Fabriquant = "HKO/Central Lab";  	break;
		case 'Easy Fit HD'    	    :  $Fabriquant = "HKO/Central Lab";  	break;
		case 'Glass'    	        :  $Fabriquant = "HKO/Central Lab";  	break;
		case 'Innovation FF HD 2'   :  $Fabriquant = "HKO/Central Lab";  	break;
		
		case 'CSC DL'    	        :  $Fabriquant = "CSC";  				break;
		
		case 'Selection RX'    	    :  $Fabriquant = "GKB/Essilor #1 Lab";  break;
		case 'Private 4'    	    :  $Fabriquant = "GKB/Essilor #1 Lab";  break;
		
		case 'Conant'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'DL SOMO'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Fit'    	            :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Glass 2'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Horizon'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Optimize'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Optimize 2'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Optimize 3'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Optimize 4'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Other'    	        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Vot'    	       		:  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Rodenstock'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Rodenstock HD'        :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Svision 2'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		case 'Younger Prog'    	    :  $Fabriquant = "Trois-Rivieres";  	break;
		
		case 'Glass 3'    	        :  $Fabriquant = "Swiss";  				break;
		case 'Vision Pro Poly'    	:  $Fabriquant = "Swiss";  				break;
		case 'My World'    	        :  $Fabriquant = "Swiss";  				break;
		
		case 'STC DL'    	        :  $Fabriquant = "Saint-Catharines/Dlab";  	break;
		case 'Svision'    	        :  $Fabriquant = "Saint-Catharines/Dlab";  	break;
	}
	
		if ($Product == 'inactive'){
			echo "<tr>
			<td align=\"center\"><font color=\"#FF0004\"><b>".$listItem[primary_key]."</b></font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[collection]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[product_name]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[product_code]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$Fabriquant."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[lens_category]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[coating]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[min_height]. '-'. $listItem[max_height]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[add_min]. '-'. $listItem[add_max]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[sphere_min]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[sphere_max]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[sphere_over_min]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[sphere_over_max]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[cyl_min]."</font></td>
			<td align=\"center\"><font color=\"#4A4CA0\">".$listItem[cyl_max]."</font></td>
			</tr>";
		}else{
			echo "<tr>
			<td align=\"center\">".$listItem[primary_key]."</td>
			<td align=\"center\">".$listItem[collection]."</td>
			<td align=\"center\">".$listItem[product_name]."</td>
			<td align=\"center\">".$listItem[product_code]."</td>
			<td align=\"center\">".$Fabriquant."</td>
			<td align=\"center\">".$listItem[lens_category]."</td>
			<td align=\"center\">".$listItem[coating]."</td>
			<td align=\"center\">".$listItem[min_height] . '-'.$listItem[max_height] ."</td>
			<td align=\"center\">".$listItem[add_min] . '-'.$listItem[add_max] ."</td>
			<td align=\"center\">".$listItem[sphere_min]."</td>
			<td align=\"center\">".$listItem[sphere_max]."</td>
			<td align=\"center\">".$listItem[sphere_over_min]."</td>
			<td align=\"center\">".$listItem[sphere_over_max]."</td>
			<td align=\"center\">".$listItem[cyl_min]."</td>
			<td align=\"center\">".$listItem[cyl_max]."</td>
			</tr>";
		}
	}//END WHILE
	
	echo "</form></table>";

}else{
	if ($_POST["rpt_search"] <> '')
	echo "<div class=\"formField\">".'No product found'."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
<script src="js/ajax.js"></script>  
</body>
</html>