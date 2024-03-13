<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");

$aujourdhui=date("Y-m-d");
//$aujourdhui="2018-02-21";
$aujourdhui="2018-01-01";
$date2="2018-03-01";

//$aujourdhui="2014-06-11";	
//RAPPORT QUI ROULE CHAQUE JOUR et qui inclus toutes les montures Armourx commandés durant a journée
//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
/*$rptQuery="SELECT * FROM orders, extra_product_orders WHERE orders.order_num = extra_product_orders.order_num AND extra_product_orders.category in ('Edging','Frame','Edging_Frame') AND order_from IN ('ifcclubca','safety') AND order_status not in ('cancelled','basket','on hold') AND order_date_processed = '$aujourdhui'  AND order_product_type = 'exclusive'
AND orders.user_id NOT IN ('redoifc','redosafety') AND supplier in ('Basic','Metro','Wrap-Rx','Classic','ARMOURX') and redo_order_num is null
GROUP BY orders.order_num ORDER BY  model  DESC";*/

//echo '<br><br>requete:'. $rptQuery;
//$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
//$ordersnum=mysql_num_rows($rptResult);
	
	//if ($ordersnum!=0){
		//$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"750\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td width=\"80\" align=\"center\">Order #</td>
                <td width=\"80\" align=\"center\">Date</td>
				<td width=\"80\" align=\"center\">Supplier</td>
                <td width=\"80\" align=\"center\">Model</td>
				<td width=\"70\" align=\"center\">Frame A</td>
				<td width=\"80\" align=\"center\">Color</td>
				<td width=\"140\" align=\"center\">Extra</td>
				</tr>";
				
		/*while ($listItem=mysql_fetch_array($rptResult)){
			
		$Extra = '';	
		
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysql_query($queryFrame)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrame   = mysql_fetch_array($resultFrame);
		
		$queryRemSideShield  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Removable Side Shield')";
		$resultRemSideShield = mysql_query($queryRemSideShield)		or die  ('I cannot select items because: ' . mysql_error());
		$NbrResultatSideShield = mysql_num_rows($resultRemSideShield );
		if ($NbrResultatSideShield  > 0){
			$queryRemSideShieldDetail  = "SELECT  distinct  removable_side_shield_ID  FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultRemSideShieldDetail = mysql_query($queryRemSideShieldDetail) or die  ('I : ' . mysql_error());
			$DataRemoveableSideShield   = mysql_fetch_array($resultRemSideShieldDetail);
			$Extra .= ' Removable Side Shield  ' . $DataRemoveableSideShield[removable_side_shield_ID]  ;
		}
		
		//Cushion
		$queryCushion  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Cushion')";
		$resultCushion= mysql_query($queryCushion)		or die  ('I cannot select items because: ' . mysql_error());
		$NbrResultatCushion = mysql_num_rows($resultCushion );
		if ($NbrResultatCushion  > 0) {
			$queryCushionDetail  = "SELECT  distinct cushion_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultCushionDetail = mysql_query($queryCushionDetail)		or die  ('I cannot select items because: ' . mysql_error());
			$DataCushionDetail   = mysql_fetch_array($resultCushionDetail);
			$Extra .= ' Cushion ' . $DataCushionDetail[cushion_ID]  ;
		}
		
		//Dust bar
		$queryDustBar  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Dust Bar')";
		$resultDustBar= mysql_query($queryDustBar)		or die  ('I cannot select items because: ' . mysql_error());
		$NbrResultatDustBar = mysql_num_rows($resultDustBar );
		if ($NbrResultatDustBar  > 0) {
			$queryDustBarDetail  = "SELECT  distinct dust_bar_ID FROM safety_frames_french WHERE collection = '$listItem[supplier]' AND model = '$listItem[temple_model_num]'";
			$resultDustBarDetail = mysql_query($queryDustBarDetail)		or die  ('I cannot select items because: ' . mysql_error());
			$DataDustBarDetail   = mysql_fetch_array($resultDustBarDetail);
			$Extra .= ' Dust Bar ' . $DataDustBarDetail[dust_bar_ID]  ;
		}
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);

			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[order_num]</td>
               				<td align=\"center\">$order_date</td>";
             $message.="    <td align=\"center\">$DataFrame[supplier]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[ep_frame_a]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$Extra</td>";
              $message.="</tr>";
		}//END WHILE*/

		
		//Fin de la partie 1
		//echo '<br>Debut partie 2<br>';
		//Début de la partie 2
		$QueryOpticBox="SELECT * FROM orders WHERE shape_name_bk like '%armour%'
		AND order_from IN ('ifcclubca','safety') AND order_status not in ('cancelled','basket','on hold') AND order_date_processed between '$aujourdhui'  and '$date2'
		AND orders.user_id NOT IN ('redoifc','redosafety','redo_supplier_quebec','redo_supplier_stc') 
		AND redo_order_num is null
		GROUP BY orders.order_num ";
		
		echo '<br><br><br>requete 2 :'. $QueryOpticBox.'<br>';

		
		$rptResult2=mysql_query($QueryOpticBox)		or die  ('I cannot select items because: ' . mysql_error());
		
		while ($listItem2=mysql_fetch_array($rptResult2)){
			
		$Extra = '';	
		
		//Removable side shield	
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a, color FROM extra_product_orders WHERE order_num = $listItem2[order_num] AND category in ('Frame','Edging','Edging_Frame')";
			//echo '<br>' . $queryFrame;
		$resultFrame = mysql_query($queryFrame)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrame   = mysql_fetch_array($resultFrame);
		
		$queryRemSideShield  = "SELECT * FROM extra_product_orders WHERE order_num = $listItem2[order_num] AND category in ('Removable Side Shield')";
		$resultRemSideShield = mysql_query($queryRemSideShield)		or die  ('I cannot select items because: ' . mysql_error());
		$NbrResultatSideShield = mysql_num_rows($resultRemSideShield );
		if ($NbrResultatSideShield  > 0){
			$queryRemSideShieldDetail  = "SELECT  distinct  removable_side_shield_ID  FROM safety_frames_french WHERE collection = '$listItem2[supplier]' AND model = '$listItem2[temple_model_num]'";
			$resultRemSideShieldDetail = mysql_query($queryRemSideShieldDetail) or die  ('I : ' . mysql_error());
			$DataRemoveableSideShield   = mysql_fetch_array($resultRemSideShieldDetail);
			$Extra .= ' Removable Side Shield  ' . $DataRemoveableSideShield[removable_side_shield_ID]  ;
		}
		

		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			
			$ModeleDemander = $DataFrame[temple_model_num];
			$ModeleDemander= str_replace('ARMOURX','',$ModeleDemander);
			$ModeleDemander= str_replace('Black','',$ModeleDemander);
			$ModeleDemander= str_replace('BLACK','',$ModeleDemander);
			$ModeleDemander= str_replace('Monture','',$ModeleDemander);

			
			$supplier =   $DataFrame[supplier];
			$supplier= str_replace('ENTREPOT DE LA LUNETTE','ARMOURX',$supplier);
				
				
			if (($supplier=='REFLECTION')|| ($supplier=='SUNTRENDS') || ($supplier=='CASINO')|| ($supplier=='PEACE')){
						
			}else{	
				
			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem2[order_num]</td>
               				<td align=\"center\">$listItem2[order_date_processed]</td>
							<td align=\"center\">$supplier</td>
							<td align=\"center\">$ModeleDemander</td>
							<td align=\"center\">$DataFrame[ep_frame_a]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$Extra</td>
						</tr>";
			}//End IF
		}//END WHILE
		
		
		
		echo $message;
		exit();
		
		
		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		
echo '<br><br>' . $message;

//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');//PRODUCTION CREDENTIALS
$send_to_address = array('rapports@direct-lens.com');//PRODUCTION CREDENTIALS
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Direct lab Network ArmouRx Frames order(s) of the day: $aujourdhui";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
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

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>