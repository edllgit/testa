<?php 
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$tomorrow  = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ajd 	   = date("Y-m-d", $tomorrow);


if ($_POST[submit] == 'Sauvegarder les modifications' ){
	//Faire l'update puisque le bouton Sauvegarder a été appuyé
	$NouveauDetail 							= mysql_escape_string($_POST[detail]);
	$Nouveau_nombre_notification_succursale = $_POST[nombre_notification_succursale];
	$Erreur_ID   							= $_POST[erreur_id];
	/*echo '<br>Nouveau detail:'      .  $NouveauDetail 	;
	echo '<br>Nouveau nombre notif' . $Nouveau_nombre_notification_succursale ;*/
	$queryUpdate = "UPDATE erreurs_optipro 
					SET detail ='$NouveauDetail',
					nombre_notification_succursale = $Nouveau_nombre_notification_succursale
					WHERE erreur_id= $Erreur_ID  ";
					echo '<br>'. $queryUpdate;
	$resultUpdate =  mysql_query($queryUpdate) or die  ('I cannot select items because 6: ' . mysql_error());
	//Rediriger vers la page d'erreur
	header("Location: optipro_today.php");
	exit();	

}else{
//Search errors of the day   
$rptQuery="SELECT * FROM erreurs_optipro 
WHERE  erreur_id = $_REQUEST[erreur_id]
ORDER BY user_id, order_num_optipro"; 
//echo '<br>'. $rptQuery . '<br>';	   
$resultQuery = mysql_query($rptQuery) or die  ('I cannot select items because 6: ' . mysql_error());
$Data        = mysql_fetch_array($resultQuery);	
}//End IF There is an ID to Update

if ($Data[rx_re_cyl] == '')
$Data[rx_re_cyl] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

if ($Data[rx_le_cyl] == '')
$Data[rx_le_cyl] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

if ($Data[rx_re_axis] == '')
$Data[rx_re_axis] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

if ($Data[rx_le_axis] == '')
$Data[rx_le_axis] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
?>
<html>
<head>
<title>Recherche parmis les erreurs d'importation Optipro</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
</head>
<body>
<form  method="post" name="edit_optipro" id="edit_optipro" action="edit_optipro.php">
	
<div align="center">

<p align="center"><h3>Modifier une erreur Optipro</h3></p>
    <table width="33%" border="1" cellspacing="0" cellpadding="0">
    
    <tr>
   		<td>Date du transfert:</td>   
        <td><?php echo $Data[date]; ?></td>
    </tr>
    
    <tr>
        <td>Compte:</td> 
        <td><?php echo $Data[user_id]; ?></td>
    </tr>
    
    <tr>
        <td>Numéro Facture Optipro:</td> 
        <td><?php echo $Data[order_num_optipro]; ?></td>
    </tr>
    
        
    <tr>
        <td>Patient:</td> 
        <td><?php echo $Data[rx_patient_full_name]; ?></td>
    </tr>
    
    <tr>
        <td>Erreur:</td> 
        <td><textarea autofocus rows="4" style="width:300px;" name="detail" id="detail"><?php echo $Data[detail]; ?></textarea></td>
    </tr>
    
     <tr>
        <td>Succursale avisée:</td> 
        <td><input type="text" name="nombre_notification_succursale" id="nombre_notification_succursale" size="1" value="<?php echo $Data[nombre_notification_succursale]; ?>"> fois</td>
    </tr>
    
     <tr>
        <td>Produit demandé:</td> 
        <td><?php echo $Data[produit_optipro]; ?></td>
    </tr>
    
      <tr>
        <td>Indice:</td> 
        <td><?php echo $Data[rx_index_v]; ?></td>
    </tr>
    
     <tr>
        <td>Transitions:</td> 
        <td><?php echo $Data[rx_photo]; ?></td>
    </tr>
    
     <tr>
        <td>Polarized:</td> 
        <td><?php echo $Data[rx_polar]; ?></td>
    </tr>
    
      <tr>
        <td>Traitement:</td> 
        <td><?php echo $Data[rx_coating]; ?></td>
    </tr>
    </table>
    
    <br><br>
     <table width="45%" border="1" cellspacing="0" cellpadding="0">
     <tr>
     	<th align="center" colspan="8"><h4>RX:</h4></th>
     </tr>
     
     <tr>
         <th  width="9%">&nbsp;</th>
         <th width="13%">Spheres</th>
         <th width="13%">Cylinders</th>
         <th width="13%">Axis</th>
         <th width="13%">Addition</th>
         <th width="13%">Hauteurs</th>
         <th width="13%">PD</th>
         <th width="15%">PD Loin</th>
     </tr>
     
     
     <tr>
        <th align="right">R.E.</th> 
        <td align="center"><?php echo $Data[rx_re_sphere]; ?></td>
        <td align="center"><?php echo $Data[rx_re_cyl]; ?></td>
        <td align="center"><?php echo $Data[rx_re_axis]; ?></td>
        <td align="center"><?php echo $Data[rx_re_add]; ?></td>
        <td align="center"><?php echo $Data[rx_re_height]; ?></td> 
        <td align="center"><?php echo $Data[rx_re_pd_near]; ?></td>
        <td align="center"><?php echo $Data[rx_re_pd]; ?></td>    
    </tr>
    
    <tr>
    	<th align="right">L.E.</th> 
        <td align="center"><?php echo $Data[rx_le_sphere]; ?></td>
        <td align="center"><?php echo $Data[rx_le_cyl]; ?></td>
        <td align="center"><?php echo $Data[rx_le_axis]; ?></td>
        <td align="center"><?php echo $Data[rx_le_add]; ?></td>
        <td align="center"><?php echo $Data[rx_le_height]; ?></td>
        <td align="center"><?php echo $Data[rx_le_pd_near]; ?></td>
        <td align="center"><?php echo $Data[rx_le_pd]; ?></td>
    </tr>
    
    
 <tr>
 	<th align="center" valign="middle" colspan="8"><h4>Frame:</h4></th>
 </tr>
 
 <tr>
 	<th>A</th>
    <th>B</th>
    <th>ED</th>
    <th>DBL</th>
    <th>Type</th>
    <th>Collection</th>  
    <th>Model</th>  
    <th>Color</th>  
 </tr>
 
  <tr>
 	<td align="center"><?php echo $Data[rx_frame_a]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_b]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_ed]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_dbl]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_type]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_collection]; ?></td>
    <td align="center"><?php echo $Data[rx_frame_model]; ?></td> 
    <td align="center"><?php echo $Data[rx_frame_color]; ?></td>
 </tr>
 
 
    
    <input type="hidden" name="erreur_id" id="erreur_id" value="<?php echo $Data[erreur_id]; ?>">
    
    </table>
    <br>
</div>    
    <?php 
	if ($_REQUEST[message] <> ''){
		
		switch($_REQUEST[acct]){
			case 'granby':      case 'grabysafe':  		$Succ = "Granby"; 		  break;	
			case 'levis':       case 'levissafe':  		$Succ = "Lévis"; 		  break;
			case 'chicoutimi':  case 'chicoutimisafe':  $Succ = "Chicoutimi"; 	  break;	
			case 'entrepotifc': case 'entrepotsafe':    $Succ = "Trois-Rivières"; break;	
			case 'entrepotdr':  case 'safedr':  	    $Succ = "Drummondville";  break;
			case 'laval': 		case 'lavalsafe':       $Succ = "Laval"; 		  break;
			case 'terrebonne':  case 'terrebonne':      $Succ = "Terrebonne";     break;
			case 'sherbrooke':  case 'sherbrookesafe':  $Succ = "Sherbrooke";     break;
			case 'longueuil':   case 'longueuilsafe':   $Succ = "Longueuil";      break;
		}
		
		switch($_REQUEST[message]){
		case 'dejatransfere': 
		echo '<div style="width:750px;background-color:#E6F18F;"><font color="#F50004">La commande #'.$_REQUEST[order_num_optipro].' de '. $Succ . ' a déja été transféré avec succès, il est donc inutile d\'aviser la succursale.</font></div>';   break;
		}//End Switch
		
	}//End if On a un message a afficher
	?>

  
    
   <div align="center"><input name="submit" type="submit" id="submit" value="Sauvegarder les modifications" class="formField"></div>
   <br><br>
    <div align="center"><a href="optipro_today.php">Retour à la liste d'erreurs Optipro</a></div>
</form>



<br><br>
<p>&nbsp;</p>
<script src="js/ajax.js"></script>
</body>
</html>