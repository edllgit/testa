<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$today      = date("Y-m-d");
$time_start = microtime(true);		
$rptQuery   = "SELECT  * FROM gkb_stock_order WHERE 1";
$rptResult  = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult);
	
$IDaEffacer = $_REQUEST[del];	
if ($IDaEffacer <> ''){
	$queryDelete   = "DELETE FROM gkb_stock_order WHERE primary_key = ". $IDaEffacer;
	echo '<br>' . $queryDelete;
	$resultDelete  = mysqli_query($con,$queryDelete)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));	
	header("Location: gkb_stock_order.php");
	exit();	
}

if ($_POST[add_to_order]=='Add to my GKB order'){
	//echo 'Ajout demandé';
	$product_name = $_POST["product_name"];	
	$quantity     = $_POST["quantity"];	
	$sphere       = $_POST["sphere"];	
	$cylindre     = $_POST["cylindre"];	
	$tray_num     = $_POST["tray_num"];	
	
	$queryValide  = "SELECT * FROM gkb_stock_order WHERE product_name = '$product_name' AND sphere = '$sphere' AND cylindre = '$cylindre'";
	$resultvalide = mysqli_query($con,$queryValide)		or die  ('I cannot validate this: <br><br>' . mysqli_error($con));
	$ExisteDeja   = mysqli_num_rows($resultvalide);
		$queryInsert  = "INSERT into gkb_stock_order (product_name,quantity,sphere,cylindre, tray_num) VALUES ('$product_name','$quantity','$sphere','$cylindre','$tray_num')";
		$resultInsert = mysqli_query($con,$queryInsert)		or die  ('I cannot add to the gkb order <br><br>' . mysqli_error($con));
		header("Location: gkb_stock_order.php");
		exit();

}

echo "<head>
		<meta charset=\"utf-8\">
    	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
   		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style>
		<!-- Bootstrap core CSS -->
		<link href=\"../../bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
		<!-- Custom styles for this template -->
		<link href=\"css/signin.css\" rel=\"stylesheet\">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
        <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
        <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
        <![endif]-->
		</head>";
	
	
	
if ($ordersnum!=0){ //Il y a des verres de sélectionné, on  doit donc les afficher
	
	echo '<div align="center"  class="alert alert-success" role="alert"><h3>Your Current Order Detail</h3></div>';
	echo "<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"table\">

	<tr bgcolor=\"CCCCCC\">
		<td align=\"center\"><strong>Product</strong></th>
		<td align=\"center\"><strong>Quantity</strong></th>
		<td align=\"center\"><strong>Sphere</strong></th>
		<td align=\"center\"><strong>Cylinder</strong></th>
		<td align=\"center\"><strong>Tray</strong></th>
		<td align=\"center\"><strong>Remove</strong></th>
	</tr>";
	
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	//Afficher les verres déja demandés pour cette commande
	echo "
	<tr class=\"alert alert-success\">
		<td align=\"center\">$listItem[product_name]</td>
		<td align=\"center\">$listItem[quantity]</td>
		<td align=\"center\">$listItem[sphere]</td>
		<td align=\"center\">$listItem[cylindre]</td>
		<td align=\"center\">$listItem[tray_num]</td>
		<td align=\"center\"><a href=\"gkb_stock_order.php?del=$listItem[primary_key]\">Remove</a></td>
	</tr>";
	
	}//End While
	echo "</table><br><br>";
	
}else{
?>
<div  align="center" class="alert alert-info" role="alert">
        <h3><strong>Welcome to the page where you can prepare your next GKB Stock order</strong></h3>
      </div><br>	
<?php
}//End IF il y a des verres dans la commande à afficher

	
		
	//Section d'ajoute de produit dans la commande 
	?>
    <form action="gkb_stock_order.php" method="post">
    <div  align="left" class="alert alert-warning" role="alert">To add new lenses to your order: Select the product,the quantity and the power you need then click on the 'Add to my GKB order' button</div>
    <table width="525" cellpadding="2"  cellspacing="0" border="1" class="table">
      <tr bgcolor="#D3CBCB">
      <td align="center"><strong>Product</strong></td>
      <td align="center"><strong>Quantity (by lens)</strong></td>
      <td align="center"><strong>Sphere</strong></td>
      <td align="center"><strong>Cylinder</strong></td>
      <td align="center"><strong>Tray</strong></td>
      <td align="center"><strong>Add to Order</strong></td>
      </tr>
      
       <tr class="alert alert-warning">
       <td align="center" width="30%">
        <select name="product_name"  id="product_name">
            <option value="1.5 White 65mm Uncoated">1.5 White 65mm Uncoated</option>
            <option value="1.5 White 65mm with HC">1.5 White 65mm with HC</option>
            <option value="1.5 White 65mm with HMC">1.5 White 65mm with HMC</option>
             <option value="1.5 White 65mm with SHMC">1.5 White 65mm with SHMC</option>
            <option value="1.5 White 65mm with Low Reflexion">1.5 White 65mm with Low Reflexion</option>
            <option value="1.5 White 70mm Uncoated">1.5 White 70mm Uncoated</option>
            <option value="1.5 White 70mm with HC">1.5 White 70mm with HC</option>
            <option value="1.5 White 70mm with HMC">1.5 White 70mm with HMC</option>
            <option value="1.5 White 70mm with SHMC">1.5 White 70mm with SHMC</option>
		    <option value="1.5 White 70mm with Low Reflexion">1.5 White 70mm with Low Reflexion</option>
           
            <option value="" disabled></option>
            <option value="1.5 Transitions VII Grey 65mm with HC">1.5 Transitions VII Grey 65mm with HC</option>
            <option value="1.5 Transitions VII Grey 65mm with HMC">1.5 Transitions VII Grey 65mm with HMC</option>
            <option value="1.5 Transitions VII Grey 65mm with SHMC">1.5 Transitions VII Grey 65mm with SHMC</option>
            <option value="1.5 Transitions VII Grey 65mm with AQUA+">1.5 Transitions VII Grey 65mm with AQUA+</option>
            <option value="1.5 Transitions VII Grey 70mm with HC">1.5 Transitions VII Grey 70mm with HC</option>
            <option value="1.5 Transitions VII Grey 70mm with HMC">1.5 Transitions VII Grey 70mm with HMC</option>
            <option value="1.5 Transitions VII Grey 70mm with SHMC">1.5 Transitions VII Grey 70mm with SHMC</option>
            <option value="1.5 Transitions VII Grey 70mm with AQUA+">1.5 Transitions VII Grey 70mm with AQUA+</option>
            
            <option value="1.5 Transitions VII Brown 65mm with HC">1.5 Transitions VII Brown 65mm with HC</option>
            <option value="1.5 Transitions VII Brown 65mm with HMC">1.5 Transitions VII Brown 65mm with HMC</option>
            <option value="1.5 Transitions VII Brown 65mm with SHMC">1.5 Transitions VII Brown 65mm with SHMC</option>
            <option value="1.5 Transitions VII Brown 65mm with AQUA+">1.5 Transitions VII Brown 65mm with AQUA+</option>
            <option value="1.5 Transitions VII Brown 70mm with HC">1.5 Transitions VII Brown 70mm with HC</option>
            <option value="1.5 Transitions VII Brown 70mm with HMC">1.5 Transitions VII Brown 70mm with HMC</option>
            <option value="1.5 Transitions VII Brown 70mm with SHMC">1.5 Transitions VII Brown 70mm with SHMC</option>
            <option value="1.5 Transitions VII Brown 70mm with AQUA+">1.5 Transitions VII Brown 70mm with AQUA+</option>
            <option value="" disabled></option>
            
           
            <option value="Plano 1.5 Gold Mirror with HC">Plano 1.5 Gold Mirror with HC</option>
            <option value="Plano 1.5 Gold Mirror with BHCM">Plano 1.5 Gold Mirror with BHCM</option>
            <option value="Plano 1.5 Silver Mirror with HC">Plano 1.5 Silver Mirror with HC</option>
            <option value="Plano 1.5 Silver Mirror with BHCM">Plano 1.5 Silver Mirror with BHCM</option>
            <option value="Plano 1.5 Blue Mirror with HC">Plano 1.5 Blue Mirror with HC</option>
            <option value="Plano 1.5 Blue Mirror with BHCM">Plano 1.5 Blue Mirror with BHCM</option>  
            <option value="" disabled></option>
            
            
            <option value="Plano 1.5 Tinted Brown Uncoated">Plano 1.5 Tinted Brown Uncoated</option>
            <option value="Plano 1.5 Tinted Brown with BHMC">Plano 1.5 Tinted Brown with BHMC</option>
            <option value="Plano 1.5 Tinted Grey Uncoated">Plano 1.5 Tinted Grey  Uncoated</option>
            <option value="Plano 1.5 Tinted Grey with HC">Plano 1.5 Tinted Grey with BHCM</option>
            <option value="" disabled></option>
            
            
            <option value="1.56 White 65mm with HMC">1.56 White 65mm with HMC</option>
            <option value="1.56 White 65mm with SHMC">1.56 White 65mm with SHMC</option>
            <option value="1.56 White 70mm with HMC">1.56 White 70mm with HMC</option> 
            <option value="1.56 White 70mm with SHMC">1.56 White 70mm with SHMC</option> 
            <option value="" disabled></option>
            <option value="1.56 Photovision Grey 65mm with HC">1.56 Photovision Grey 65mm with HC</option>
            <option value="1.56 Photovision Grey 65mm with HMC">1.56 Photovision Grey 65mm with HMC</option>
            <option value="1.56 Photovision Grey 65mm with SHMC">1.56 Photovision Grey 65mm with SHMC</option>
            <option value="1.56 Photovision Grey 65mm with AQUA+">1.56 Photovision Grey 65mm with AQUA+</option>
            <option value="1.56 Photovision Grey 70mm with HC">1.56 Photovision Grey 70mm with HC</option>
            <option value="1.56 Photovision Grey 70mm with HMC">1.56 Photovision Grey 70mm with HMC</option>
            <option value="1.56 Photovision Grey 70mm with SHMC">1.56 Photovision Grey 70mm with SHMC</option>
            <option value="1.56 Photovision Grey 70mm with AQUA+">1.56 Photovision Grey 70mm with AQUA+</option>

            <option value="1.56 Photovision Brown 65mm with HC">1.56 Photovision Brown 65mm with HC</option>
            <option value="1.56 Photovision Brown 65mm with HMC">1.56 Photovision Brown 65mm with HMC</option>
              <option value="1.56 Photovision Brown 65mm with SHMC">1.56 Photovision Brown 65mm with SHMC</option>
            <option value="1.56 Photovision Brown 65mm with AQUA+">1.56 Photovision Brown 65mm with AQUA+</option>
            <option value="1.56 Photovision Brown 70mm with HC">1.56 Photovision Brown 70mm with HC</option>
            <option value="1.56 Photovision Brown 70mm with HMC">1.56 Photovision Brown 70mm with HMC</option>
             <option value="1.56 Photovision Brown 70mm with SHMC">1.56 Photovision Brown 70mm with SHMC</option>
            <option value="1.56 Photovision Brown 70mm with AQUA+">1.56 Photovision Brown 70mm with AQUA+</option>
            <option value="" disabled></option>
			
            <option value="1.59 White 65mm with HC">1.59 White 65mm with HC</option>
            <option value="1.59 White 65mm with HMC">1.59 White 65mm with HMC</option>
            <option value="1.59 White 65mm with SHMC">1.59 White 65mm with SHMC</option>
            <option value="1.59 White 65mm with AQUA+">1.59 White 65mm with AQUA+</option>
			<option value="1.59 White 65mm with BlueX">1.59 White 65mm with BlueX</option>
			<option value="1.59 White 65mm with Low Reflection">1.59 White 65mm with Low Reflection</option>
		
			
            <option value="1.59 White 70mm with HC">1.59 White 70mm with HC</option>
            <option value="1.59 White 70mm with HMC">1.59 White 70mm with HMC</option>
            <option value="1.59 White 70mm with SHMC">1.59 White 70mm with SHMC</option>
            <option value="1.59 White 70mm with AQUA+">1.59 White 70mm with AQUA+</option> 
			<option value="1.59 White 70mm with BlueX">1.59 White 70mm with BlueX</option>
			<option value="1.59 White 70mm with Low Reflection">1.59 White 70mm with Low Reflection</option>
			
            <option value="" disabled></option>
            
            <option value="1.59 Transitions VII Grey 65mm with HC">1.59 Transitions VII Grey 65mm with HC</option>
            <option value="1.59 Transitions VII Grey 65mm with HMC">1.59 Transitions VII Grey 65mm with HMC</option>
             <option value="1.59 Transitions VII Grey 65mm with SHMC">1.59 Transitions VII Grey 65mm with SHMC</option>
            <option value="1.59 Transitions VII Grey 65mm with AQUA+">1.59 Transitions VII Grey 65mm with AQUA+</option>
            <option value="1.59 Transitions VII Grey 70mm with HC">1.59 Transitions VII Grey 70mm with HC</option>
            <option value="1.59 Transitions VII Grey 70mm with HMC">1.59 Transitions VII Grey 70mm with HMC</option>
             <option value="1.59 Transitions VII Grey 70mm with SHMC">1.59 Transitions VII Grey 70mm with SHMC</option>
            <option value="1.59 Transitions VII Grey 70mm with AQUA+">1.59 Transitions VII Grey 70mm with AQUA+</option>
             <option value="" disabled></option>
            
            <option value="1.59 Transitions VII Brown 65mm with HC">1.59 Transitions VII Brown 65mm with HC</option>
            <option value="1.59 Transitions VII Brown 65mm with HMC">1.59 Transitions VII Brown 65mm with HMC</option>
             <option value="1.59 Transitions VII Brown 65mm with SHMC">1.59 Transitions VII Brown 65mm with SHMC</option>
            <option value="1.59 Transitions VII Brown 65mm with AQUA+">1.59 Transitions VII Brown 65mm with AQUA+</option>
            <option value="1.59 Transitions VII Brown 70mm with HC">1.59 Transitions VII Brown 70mm with HC</option>
            <option value="1.59 Transitions VII Brown 70mm with HMC">1.59 Transitions VII Brown 70mm with HMC</option>
             <option value="1.59 Transitions VII Brown 70mm with SHMC">1.59 Transitions VII Brown 70mm with SHMC</option>
             
            <option value="1.59 Transitions VII Brown 70mm with AQUA+">1.59 Transitions VII Brown 70mm with AQUA+</option>
             <option value="" disabled></option>
            <option value="1.6 White 65mm with HC">1.6 White 65mm with HC</option>
            <option value="1.6 White 65mm with HMC">1.6 White 65mm with HMC</option>
             <option value="1.6 White 65mm with SHMC">1.6 White 65mm with SHMC</option>
            <option value="1.6 White 65mm with AQUA+">1.6 White 65mm with AQUA+</option>
            <option value="1.6 White 65mm with Low Reflexion">1.6 White 65mm with Low Reflexion</option>
            <option value="1.6 White 70mm with HC">1.6 White 70mm with HC</option>
            <option value="1.6 White 70mm with HMC">1.6 White 70mm with HMC</option>
            <option value="1.6 White 70mm with SHMC">1.6 White 70mm with SHMC</option>
            <option value="1.6 White 70mm with AQUA+">1.6 White 70mm with AQUA+</option>
            <option value="1.6 White 70mm with Low Reflexion">1.6 White 70mm with Low Reflexion</option>
            <option value="1.6 White 75mm with HC">1.6 White 75mm with HC</option>
            <option value="1.6 White 75mm with HMC">1.6 White 75mm with HMC</option>
             <option value="1.6 White 75mm with SHMC">1.6 White 75mm with SHMC</option>
            <option value="1.6 White 75mm with AQUA+">1.6 White 75mm with AQUA+</option>
            <option value="1.6 White 75mm with Low Reflexion">1.6 White 75mm with Low Reflexion</option>
            <option value="" disabled></option>           
            <option value="1.6 Transitions VII Grey 65mm with HC">1.6 Transitions VII Grey 65mm with HC</option>
            <option value="1.6 Transitions VII Grey 65mm with HMC">1.6 Transitions VII Grey 65mm with HMC</option>
             <option value="1.6 Transitions VII Grey 65mm with SHMC">1.6 Transitions VII Grey 65mm with SHMC</option>
            <option value="1.6 Transitions VII Grey 65mm with AQUA+">1.6 Transitions VII Grey 65mm with AQUA+</option>
            <option value="1.6 Transitions VII Grey 75mm with HC">1.6 Transitions VII Grey 75mm with HC</option>
            <option value="1.6 Transitions VII Grey 75mm with HMC">1.6 Transitions VII Grey 75mm with HMC</option>
            <option value="1.6 Transitions VII Grey 75mm with SHMC">1.6 Transitions VII Grey 75mm with SHMC</option>
            <option value="1.6 Transitions VII Grey 75mm with AQUA+">1.6 Transitions VII Grey 75mm with AQUA+</option>
            
            <option value="1.6 Transitions VII Brown 65mm with HC">1.6 Transitions VII Brown 65mm with HC</option>
            <option value="1.6 Transitions VII Brown 65mm with HMC">1.6 Transitions VII Brown 65mm with HMC</option>
            <option value="1.6 Transitions VII Brown 65mm with SHMC">1.6 Transitions VII Brown 65mm with SHMC</option>
            <option value="1.6 Transitions VII Brown 65mm with AQUA+">1.6 Transitions VII Brown 65mm with AQUA+</option>
            <option value="1.6 Transitions VII Brown 75mm with HC">1.6 Transitions VII Brown 75mm with HC</option>
            <option value="1.6 Transitions VII Brown 75mm with HMC">1.6 Transitions VII Brown 75mm with HMC</option>
             <option value="1.6 Transitions VII Brown 75mm with SHMC">1.6 Transitions VII Brown 75mm with SHMC</option>
            <option value="1.6 Transitions VII Brown 75mm with AQUA+">1.6 Transitions VII Brown 75mm with AQUA+</option>
            
            <option value="" disabled></option>
            <option value="1.67 White 65mm with HC">1.67 White 65mm with HC</option>
            <option value="1.67 White 65mm with HMC">1.67 White 65mm with HMC</option>
            <option value="1.67 White 65mm with SHMC">1.67 White 65mm with SHMC</option>
            <option value="1.67 White 65mm with AQUA+">1.67 White 65mm with AQUA+</option>
            <option value="1.67 White 70mm with HC">1.67 White 70mm with HC</option>
            <option value="1.67 White 70mm with HMC">1.67 White 70mm with HMC</option>
            <option value="1.67 White 70mm with SHMC">1.67 White 70mm with SHMC</option>
            <option value="1.67 White 70mm with AQUA+">1.67 White 70mm with AQUA+</option>
            <option value="1.67 White 75mm with HC">1.67 White 75mm with HC</option>
            <option value="1.67 White 75mm with HMC">1.67 White 75mm with HMC</option>
            <option value="1.67 White 75mm with SHMC">1.67 White 75mm with SHMC</option>
            <option value="1.67 White 75mm with AQUA+">1.67 White 75mm with AQUA+</option>
   
			  <option value="" disabled></option>
			
			

            <option value="1.74 White 65mm with SHMC">1.74 White 65mm with SHMC</option>
            <option value="1.74 White 65mm with AQUA+">1.74 White 65mm with AQUA+</option>
            <option value="1.74 White 75mm with SHMC">1.74 White 75mm with SHMC</option>
            <option value="1.74 White 75mm with AQUA+">1.74 White 75mm with AQUA+</option>
			
        </select></td>

		<td  align="center">
            <select name="quantity"  id="quantity">
                <option value="1">1</option><option   value="2">2</option>  <option value="3">3</option><option value="4">4</option>
                <option value="5">5</option><option   value="6">6</option>  <option value="7">7</option><option value="8">8</option>
                <option value="9">9</option><option   value="10">10</option><option value="11">11</option><option value="12">12</option>
                <option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option>
                <option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
                <option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
                <option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option>
                <option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option>
                <option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option>
                <option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option>
            </select>
        </td>
        
        
        <td  align="center">
        	<select name="sphere"  id="sphere">
                <option value="+6.00">+6.00</option> 
                <option value="+5.75">+5.75</option> 
                <option value="+5.50">+5.50</option> 
                <option value="+5.25">+5.25</option> 
                <option value="+5.00">+5.00</option>
                <option value="+4.75">+4.75</option> 
                <option value="+4.50">+4.50</option> 
                <option value="+4.25">+4.25</option> 
                <option value="+4.00">+4.00</option>
                <option value="+3.75">+3.75</option> 
                <option value="+3.50">+3.50</option> 
                <option value="+3.25">+3.25</option> 
                <option value="+3.00">+3.00</option>
                <option value="+2.75">+2.75</option> 
                <option value="+2.50">+2.50</option> 
                <option value="+2.25">+2.25</option> 
                <option value="+2.00">+2.00</option>
                <option value="+1.75">+1.75</option> 
                <option value="+1.50">+1.50</option> 
                <option value="+1.25">+1.25</option> 
                <option value="+1.00">+1.00</option> 
                <option value="+0.75">+0.75</option> 
			    <option value="+0.50">+0.50</option> 
                <option value="+0.25">+0.25</option> 
                <option value="-0.00" selected>-0.00</option> 
                <option value="-0.25">-0.25</option> 
                <option value="-0.50">-0.50</option> 
                <option value="-0.75">-0.75</option>
                <option value="-1.00">-1.00</option> 
                <option value="-1.25">-1.25</option> 
                <option value="-1.50">-1.50</option> 
                <option value="-1.75">-1.75</option> 
                <option value="-2.00">-2.00</option> 
                <option value="-2.25">-2.25</option> 
                <option value="-2.50">-2.50</option> 
                <option value="-2.75">-2.75</option> 
                <option value="-3.00">-3.00</option> 
                <option value="-3.25">-3.25</option>  
                <option value="-3.50">-3.50</option> 
                <option value="-3.75">-3.75</option> 
                <option value="-4.00">-4.00</option> 
                <option value="-4.25">-4.25</option> 
                <option value="-4.50">-4.50</option> 
                <option value="-4.75">-4.75</option>
                <option value="-5.00">-5.00</option> 
                <option value="-5.25">-5.25</option> 
                <option value="-5.50">-5.50</option> 
                <option value="-5.75">-5.75</option> 
                <option value="-6.00">-6.00</option>
                <option value="-6.25">-6.25</option> 
                <option value="-6.50">-6.50</option> 
                <option value="-6.75">-6.75</option> 
                <option value="-7.00">-7.00</option>
                <option value="-7.25">-7.25</option>
                <option value="-7.50">-7.50</option>
                <option value="-7.75">-7.75</option>
                <option value="-8.00">-8.00</option>
            </select>
         </td>
        
        
        <td  align="center">
        	<select name="cylindre"  id="cylindre">
                <option value="-0.00">-0.00</option> 
                <option value="-0.25">-0.25</option> 
                <option value="-0.50">-0.50</option> 
                <option value="-0.75">-0.75</option>
                <option value="-1.00">-1.00</option> 
                <option value="-1.25">-1.25</option> 
                <option value="-1.50">-1.50</option> 
                <option value="-1.75">-1.75</option> 
                <option value="-2.00">-2.00</option> 
                <option value="-2.25">-2.25</option> 
                <option value="-2.50">-2.50</option> 
                <option value="-2.75">-2.75</option> 
                <option value="-3.00">-3.00</option> 
                <option value="-3.25">-3.25</option>  
                <option value="-3.50">-3.50</option> 
                <option value="-3.75">-3.75</option> 
                <option value="-4.00">-4.00</option>               
            </select>
         </td>
        
        <td  align="center"><input name="tray_num" type="text" size="5" id="tray_num" value="" class="formField"></td>
        
        <td  align="center"><input name="add_to_order" type="submit" id="add_to_order" value="Add to my GKB order" class="formField"></td>
        </tr>
    </table></form>
    <br><br>
    
    
    
    
    
    
    <?php

	if ($ordersnum!=0){
		$count=0;
		

		$message.="<body><table class=\"table\" width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Product</td>
                <td align=\"center\">Quantity</td>
                <td align=\"center\">Remove</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
	

$queryUpd= "Select max(update_time) as update_time from status_history WHERE order_status = 'in transit' and order_num =". $listItem[order_num] ;
echo $queryUpd . '<br>';
$rptUpd=mysqli_query($con, $queryUpd)		or die  ('I cannot select items because: <br><br>' . mysql_error());
$DataUpd=mysqli_fetch_array($rptUpd,MYSQLI_ASSOC);
$LastUpdate = $DataUpd['update_time'];
	

			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[lab_name]</td><td align=\"center\">$order_date</td>";
		           $message.="
                <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[patient_ref_num]</td>
                <td align=\"center\">$LastUpdate</td>";
              $message.="</tr>";
		}//END WHILE	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL
	
	
//Afficher bouton pour terminer la commmande 
	//Afficher seulement si des items sont ajoutés a la commande 
	if ($ordersnum!=0){
	echo '<form action="send_gkb_stock_order.php" method="post">';
	?>
	<div style="border:1px solid black;width:700px;margin-left:420px;"  align="center" class="alert alert-danger" role="alert">
    When your order is finish, just    click this button to send it to GKB
	<input name="send_order_email" type="submit" id="send_order_email" value="Send my Order by Email to GKB" class="formField">
	<?php
    echo '</div><br><br>
	</form>';
	}//end IF items are added to the order
	

//SEND EMAIL
 $send_to_address = array('rapports@direct-lens.com');

 //ob_start();

//echo "<br>".$send_to_address;
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "GKB Stock order: ".$curTime;
$response   = office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	$compteur = 0;
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}
	

    	// Générer le contenu HTML du rapport
	//$contenuHtml = ob_get_clean();

	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_gkb_stock_order_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/Fournisseur/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

	
	/*if($response){ 
		log_email("REPORT: GKB Stock order",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: GKB Stock order",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	*/

		
$time_end = microtime(true);
$time = $time_end - $time_start;
//echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Send jobs in Transit', '$time','$today','$timeplus3heures','cron_sent_late_transit.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because: ' . mysqli_error($con));	
}

?>