<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include("admin_functions.inc.php");
include "../connexion_hbc.inc.php";
//Le fichier getlang est partagé avec le labAdmin..Ne pas modifier!
include "../includes/getlang.php";

session_start();
?>
<html>
<head>
<title>Historique de status</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
</head>
<body>
  <table border="1" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php 	include("adminNav.php");?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td align="center" width="75%">
		<b><font  size="2" face="Helvetica, sans-serif, Arial">
        <?php
			if ($mylang == 'lang_France')
			{
			echo 'Historique de status de la commande';
			}else {
			echo 'ORDER STATUS HISTORY';
			}
		?>
            
         <br><?php echo $_GET[order_num]; ?><br>&nbsp;</font></b>
            <table width="50%" border="3" cellpadding="2" cellspacing="0" class="formField3">
            	
                <?php 
				
			if ($mylang == 'lang_France'){
			?>
               <tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">STATUS</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Mise à jour</font></b></td>
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Type de mise à jour</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Adresse Ip</font></b></td>
                    <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Qui</font></b></td>
                    <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Autorisé Par</font></b></td>
				</tr>
			
			<?php }else {?>
              <tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">STATUS</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">UPDATE TIME</font></b></td>
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">UPDATE TYPE</font></b></td>
					<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">UPDATE IP</font></b></td>
                    <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Who</font></b></td>
                    <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Authorized by</font></b></td>
          
				</tr>
			<?php }	?>
                
             
			<tr><td>
			

			<?php  	
			
			
			
$queryHistory = "SELECT * FROM status_history WHERE order_num = $_GET[order_num] ORDER BY update_time DESC";
//echo '<br>' . $queryHistory;
$HistoryResult=mysqli_query($con,$queryHistory)	or die  ('I cannot select items because 1: ' . mysqli_error($con));



			while ($HistoryData=mysqli_fetch_array($HistoryResult,MYSQLI_ASSOC)){
			//echo '<br>' . $HistoryData[order_status];
			$num_rows = mysqli_num_rows($HistoryResult);
			
			$compteur+=1;

		if ($HistoryData['access_id']  <> '' ){
			$QueryAccess="SELECT * FROM access_admin WHERE id=" . $HistoryData['access_id'] ; 
			$ResultAccess=mysqli_query($con,$QueryAccess)	or die  ('I cannot select items because 2: ' . mysqli_error($con));
			$DataAccess=mysqli_fetch_array($ResultAccess,MYSQLI_ASSOC);
		}
	
		
		if (($mylang == 'lang_France') || ($mylang == 'lang_french'))  {
			switch($HistoryData['order_status']){
			case 'processing':			$status_to_print = "Commande Transmise";			break;
			case 'in coating':			$status_to_print = "Traitement AR";					break;
			case 'in transit':			$status_to_print = "En Transit";					break;
			case 'in mounting':			$status_to_print = "Au Montage";					break;
			case 'in edging':			$status_to_print = "Au Taillage";					break;
			case 'order imported':		$status_to_print = "Commande en cours";				break;
			case 'information in hand':	$status_to_print = "Info Transmise";   				break;
			case 'on hold':				$status_to_print = "En Attente";					break;	
			case 'order completed':		$status_to_print = "Production Termin&eacute;e";   	break;
			case 'delay issue 0':		$status_to_print = "D&eacute;lai 0";				break;
			case 'delay issue 1':		$status_to_print = "D&eacute;lai 1";				break;
			case 'delay issue 2':		$status_to_print = "D&eacute;lai 2";				break;
			case 'delay issue 3':		$status_to_print = "D&eacute;lai 3";				break;
			case 'delay issue 4':		$status_to_print = "D&eacute;lai 4";				break;
			case 'delay issue 5':		$status_to_print = "D&eacute;lai 5";				break;
			case 'delay issue 6':		$status_to_print = "D&eacute;lai 6";				break;
			case 'filled':				$status_to_print = "Exp&eacute;di&eacute;e";    	break;
			case 'cancelled':			$status_to_print = "Annul&eacute;e";				break;
			case 'waiting for frame':	$status_to_print = "Attente de monture";			break;
			case 'waiting for lens':	$status_to_print = "Attente de verres";				break;	
			case 'waiting for shape':	$status_to_print = "Attente de forme";				break;
			case 're-do':				$status_to_print = "Reprise Interne";				break;
			case 'job started':			$status_to_print = 'Surfa&ccedil;age';				break;
			case 'scanned shape to swiss':$status_to_print = "Scanned shape to Swiss";		break;	
			case 'waiting for frame store':	$status_to_print = "Attente de monture Magasin";			break;
			case 'waiting for frame ho/supplier':	$status_to_print = "Attente de monture Siege Social/Fournisseur";			break;
			default:					$status_to_print= $HistoryData['order_status'];		break;
					}
			}else {
			
				switch($HistoryData['order_status']){
					case "cancelled":					$status_to_print ="Cancelled";					break;
					case "processing":					$status_to_print = "Confirmed";					break;
					case "order imported":				$status_to_print = "Order Imported";			break;
					case "job started":					$status_to_print = "Surfacing";					break;
					case "in coating":					$status_to_print = "In Coating";				break;
					case "in mounting":					$status_to_print = "In Mounting";				break;
					case "in edging":					$status_to_print = "In Edging";					break;
					case 'in edging swiss':				$status_to_print = "In Edging Swiss";			break;
					case "order completed":				$status_to_print = "Order Completed";			break;
					case "delay issue 0":				$status_to_print = "Delay Issue 0";				break;
					case "delay issue 1":				$status_to_print = "Delay Issue 1";				break;
					case "delay issue 2":				$status_to_print = "Delay Issue 2";				break;
					case "delay issue 3":				$status_to_print = "Delay Issue 3";				break;
					case "delay issue 4":				$status_to_print = "Delay Issue 4";				break;
					case "delay issue 5":				$status_to_print = "Delay Issue 5";				break;
					case "delay issue 6":				$status_to_print = "Delay Issue 6";				break;	
					case "waiting for frame":			$status_to_print = "Waiting for Frame";			break;
					case "waiting for shape":			$status_to_print = "Waiting for Shape";			break;
					case "on hold":						$status_to_print = "On Hold";					break;
					case "re-do":						$status_to_print = "Redo";						break;
					case "in transit":					$status_to_print = "In Transit";				break;				
					case "filled":						$status_to_print = "Shipped";					break;
					case "waiting for lens":			$status_to_print = "Waiting for Lens";			break;
					case "scanned shape to swiss":		$status_to_print = "Scanned shape to Swiss";	break;
					case "waiting for frame store":			$status_to_print = "Waiting for Frame Store";			break;
					case "waiting for frame ho/supplier":			$status_to_print = "Waiting for Frame Head Office/Supplier";			break;
					default:							$status_to_print = $HistoryData['order_status'];break;
				}
			}
		
			
			echo "<tr><td align=\"center\">". $status_to_print. "</td><td align=\"center\">";
			$ladate = $HistoryData['update_time'];
			echo date("D,  j, M                  G:i:s ",strtotime($ladate));

			 
		   echo  '</td><td align="center">';
		    
			if (($mylang == 'lang_France') && ($HistoryData['update_type']=='manual'))
			{
			echo 'Manuelle';
			}else {
			echo $HistoryData['update_type'];
			}
		   echo  '</td>';
		   
		   
		   
		   echo   '<td align="center">';
		   if ($HistoryData['update_ip2'] != ''){
		    echo $HistoryData['update_ip2']. ' ';
		   }else{
		   echo $HistoryData['update_ip']. '&nbsp;';
		   }

		   echo  '</td>';
		   
		   
		   
		   echo  '</td><td align="center">';
		   if ($DataAccess['name'] <> ''){
		   echo $DataAccess['name'];
		   }else{
		    echo '&nbsp;';
		   }
		   echo  '</td>';
		   
		   
		   
		   if ($HistoryData[redo_approved_by] <> ''){
		   echo '<td>&nbsp;'. $HistoryData['redo_approved_by'] . '</td>';
		   }else{
		   echo '<td>&nbsp;</td>';
		   }
		   echo  '</tr>';
		}//End WHILE

				
				
				//Partie Basket
			$queryBasket     = "SELECT * FROM status_history WHERE order_primary_key = (SELECT primary_key FROM orders WHERE order_num=$_GET[order_num])";
			$ResultBasket    = mysqli_query($con,$queryBasket)	or die  ('I cannot select items because 1: ' . mysqli_error($con));	
			$NbrResultBasket = mysqli_num_rows($ResultBasket);
			$DataBasket      = mysqli_fetch_array($ResultBasket,MYSQLI_ASSOC);
		    
			if (($mylang == 'lang_France') && ($HistoryData['update_type']=='manual') && ($NbrResultBasket>0))
			{
				echo "<tr><td align='center'>Panier d\'achat</td>  <td align='center'>$DataBasket[update_time]</td> <td align='center'>Import Script</td> <td align='center'>-</td>  <td align='center'>Importation Optipro</td> <td align='center'>-</td></tr>";
			}elseif ($NbrResultBasket>0) {
				echo "<tr><td align='center'>Basket</td>  <td align='center'>$DataBasket[update_time]</td> <td align='center'>Import Script</td> <td>-</td>  <td align='center'>Importation Optipro</td> <td align='center'>-</td></tr>";
			}
		    
				
				
			$compteur = 0;
			 ?>

			</td></tr>
		
		
</table>
<br>
<a  target="_blank" style="text-decoration:none;" href="http://cqcounter.com/whois/">Recherche par adresse ip</a><br><br>

<table border="1">

<?php 
	if ($mylang == 'lang_France'){ ?>
<td><b>Manuelle</b>: Quelqu'un a manuellement changé le status</td>
<td><b>Script</b>: Un script automatique à effectué la mise à jour updated the status</td>
<?php }else { ?>
<td><b>Manual</b>: A person change the status manually</td>
<td><b>Update script</b>: A script updated the status</td>
	<?php }?>            
 </table>
&nbsp;<br>

</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>