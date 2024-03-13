<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");
//include('../phpmailer_email_functions.inc.php');
//include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if(($_POST[rpt_search]=="Generate Report") || ($_POST[rpt_search]=="Generate Report and Send by Email")){
		$date_from = date("Y-m-d",strtotime($_POST[date_from]));
		$date_to   = date("Y-m-d",strtotime($_POST[date_to]));
		$rptQuery="SELECT * FROM orders  WHERE lab IN (66,67) AND prescript_lab = 10 	AND orders.order_status!='basket' AND orders.order_status!='cancelled' 
		AND orders.order_date_processed BETWEEN '$date_from' AND '$date_to' AND order_status <> 'filled'  ORDER BY frame_sent_swiss, order_date_processed";
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
	}else{
		$ladate    	  = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$datecomplete = date("Y-m-d", $ladate);
		$date_from    = $datecomplete;
		$date_to      = $datecomplete;
	}

	$heading="Frame sent to Swisscoat";
	$heading.=$dateInfo;
	$heading=ucwords($heading);

if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>
</head>

<body onload="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="report_send_frame_swiss.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Generate Frames send to Swiss Report</font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="25%"><div align="right">
						Date From
					</div></td>
					<td width="15%"><input name="date_from" type="text" class="formField" id="date_from" value="<?php echo $date_from ; ?>" size="11">
					</td>
					<td width="15%"><div align="center">
						Through
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="<?php echo $date_to ; ?>" size="11">
					</td>
					</tr>
				<tr>
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="Generate Report" class="formField">&nbsp;&nbsp;&nbsp;
                    <input name="rpt_search" type="submit" id="rpt_search" value="Generate Report and Send by Email" class="formField"></div></td>
					</tr>
                    <tr>
                    <td colspan="2">Keep in mind that this report <b>does not</b> display <b>Remote Edging<b> and <b>shipped</b> orders.</td>
                    </tr>
			</table>
</form>
<?php 
if ($rptQuery!=""){
	$rptResult=mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
	$rptCount=mysqli_num_rows($rptResult);
	$rptQuery="";
	$_SESSION["RPTQUERY"]=$rptQuery;
}
	
$message="";

$message.= "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";

$message.= "<tr bgcolor=\"#000000\"><td colspan=\"6\"><font color=\"white\">$heading</font></td></tr>";
echo "<tr bgcolor=\"#000000\"><td colspan=\"10\"><font color=\"white\">$heading</font></td></tr>";

if ($rptCount != 0){
	echo "
	<tr bgcolor=\"DDDDDD\">
		<th align=\"center\">Order #</th>
		<th align=\"center\">Tray</th>
		<th align=\"center\">Frame</th>
		<th align=\"center\">Model</th>
		<th align=\"center\">Company</th>
		<th align=\"center\">Product</th>
		<th align=\"center\">Frame Sent</th>
		<th align=\"center\">Job Type</th>
		<th align=\"center\">Date</th>
		<th align=\"center\">Status</th>
	</tr>";
	$message.= "
	<tr bgcolor=\"DDDDDD\">
		<th align=\"center\">Order #</th>
		<th align=\"center\">Tray</th>
		<th align=\"center\">Frame</th>
		<th align=\"center\">Model</th>
		<th align=\"center\">Company</th>
		<th align=\"center\">Product</th>
	</tr>";
	$CompteurFrame              = 0;
	$Compteur_Frame_Envoyes     = 0;
	$Compteur_Frame_Non_Envoyes = 0;
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
		$queryFrame  = "SELECT * FROM extra_product_orders WHERE category= 'Frame' AND order_num = $listItem[order_num]";
		$resultFrame = mysqli_query($con,$queryFrame)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$ModeleTrouver = mysqli_num_rows($resultFrame);
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
		$Collection  = $DataFrame[supplier];
		$FrameModel  = $DataFrame[temple_model_num];
		
		$queryEdging  = "SELECT job_type FROM extra_product_orders WHERE category= 'Edging' AND order_num = $listItem[order_num]";
		$resultEdging = mysqli_query($con,$queryEdging)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$Nbr_Resultat = mysqli_num_rows($resultEdging);
		if ($Nbr_Resultat > 0){
			$DataEdging   = mysqli_fetch_array($resultEdging,MYSQLI_ASSOC);
			if ($DataEdging[job_type]=='Edge and Mount')
				$Job_Type = 'Edge and Mount';
			elseif($DataEdging[job_type]=='remote edging')
				$Job_Type = 'Remote Edging';
			else 
				$Job_Type = 'Uncut';
		}
		
		$queryCompany  =  "SELECT company FROM accounts WHERE user_id = '$listItem[user_id]'";	
		$resultCompany =  mysqli_query($con,$queryCompany)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataCompany   =  mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		$Company       =  $DataCompany[company];

		if (($ModeleTrouver <> 0) && ($Job_Type == 'Edge and Mount')){
			$CompteurFrame +=1;
			echo '<tr>';
			echo '<td align="center">'.$listItem[order_num].'</td>';
			echo '<td align="center">'.$listItem[tray_num].'</td>';		
			echo '<td align="center">'.$Collection.'</td>';
			echo '<td align="center">'.$FrameModel.'</td>';
			echo '<td align="center">'.$Company.'</td>';
			echo '<td align="center">'.$listItem[order_product_name].'</td>';
			
			
			
			echo '<td align="center">';
			if ($listItem[frame_sent_swiss] == "0000-00-00 00:00:00"){
				$listItem[frame_sent_swiss] = "";
				echo '<font color="#F91216"><b>NO</b></font>';
				$Compteur_Frame_Non_Envoyes +=1;
			}else{
				$Compteur_Frame_Envoyes += 1;
				echo $listItem[frame_sent_swiss];
			}//End IF
			echo '</td>';
			
			echo '<td align="center">'.$Job_Type.'</td>';
			echo '<td align="center">'.$listItem[order_date_processed].'</td>';
			echo '<td align="center">'.$listItem[order_status].'</td>';
			echo '</tr>';
			
			$message.= '<tr>';
			$message.= '<td align="center">'.$listItem[order_num].'</td>';
			$message.= '<td align="center">'.$listItem[tray_num].'</td>';		
			$message.= '<td align="center">'.$Collection.'</td>';
			$message.= '<td align="center">'.$FrameModel.'</td>';
			$message.= '<td align="center">'.$Company.'</td>';
			$message.= '<td align="center">'.$listItem[order_product_name].'</td>';
			$message.= '</tr>';
		}//End If a frame model has been found
	}//END WHILE
	
$message.= '<tr><td align="center" colspan="9"><b>Total: '.$CompteurFrame.' orders</b></td></tr>';
echo  '<tr><td align="center" colspan="10"><b>Total: '.$CompteurFrame.' orders&nbsp;&nbsp;&nbsp;
Sent to Swiss: '. $Compteur_Frame_Envoyes. ' orders&nbsp;&nbsp;&nbsp;<font color="#FF0105">Not sent: '. $Compteur_Frame_Non_Envoyes .' orders</font></b></td></tr>';
}else{
	$message.= "<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\">No Orders Found</td></tr>";
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\">No Orders Found</td></tr>";
}//END rptCount CONDITIONAL
$message.= "</table>";
echo "</table>";
?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<?php
//Envoie du rapport par courriel Copie a Danielle Bouffard, Moi et Daniel Beaulieu à chaque fois que ce rapport est généré.
$send_to_address = array('rapports@direct-lens.com');
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Report Frames sent to Swisscoat";

if($_POST[rpt_search]=="Generate Report and Send by Email"){//Envoyer le rapport uniquement si il a été généré: évite l'envoie d'un rapport vide au chargement de la page
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}
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
	
	
		if($response){ 
			log_email("REPORT: Report Frames sent to Swisscoat",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			log_email("REPORT: Report Frames sent to Swisscoat",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}	
	
	

function log_email($subject,$send_to_address,$additional, $user_agent){
	include "../sec_connectEDLL.inc.php";
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because: ' . mysqli_error($con));	
}


 ?>

</body>
</html>