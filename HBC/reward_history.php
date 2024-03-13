<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);
	
require('../Connections/sec_connect.inc.php');
$user_id=$_SESSION["sessionUser_Id"];

if ($_POST[from_form_order_num]=="true"){
	$order_num=$_POST[order_num];
	$query="SELECT orders.order_num AS order_no,orders.order_product_name,, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type,  orders.reward_points FROM orders 
	WHERE orders.order_num='$order_num' and orders.user_id='$user_id'  GROUP by orders.order_num ORDER BY orders.order_num ";
	
$_SESSION["QUERY"]=$query;
}//END IF FROM FORM ORDER NUM

if ($_POST[from_form]=="true"){

		$date=array();
		$date2=array();
		
		$date= explode("/", $_POST[date_from]);
		$start_date=$date[2]."-".$date[0]."-".$date[1];

		$date2= explode("/", $_POST[date_to]);
		$end_date=$date2[2]."-".$date2[0]."-".$date2[1];
		
			$query="SELECT orders.order_num AS order_no, orders.order_product_name, orders.patient_ref_num, orders.order_patient_last, orders.order_date_processed, orders.order_date_shipped, orders.order_status, orders.order_total, orders.order_product_type, orders.reward_points FROM orders 
			WHERE  (orders.order_date_processed between '$start_date' and '$end_date') and orders.user_id='$user_id' GROUP by orders.order_num ORDER BY orders.order_num ";
	

$_SESSION["QUERY"]=$query;

}//END IF FROM FORM

if ($_GET["q"]!=""){// IF COLUMN SORT CLICKED ON
	$query=$_SESSION["QUERY"]." ".$_GET["q"];
}
else if (($_SESSION["QUERY"]!="")&&($_GET["q"]=="")) {//IF SEARCH FORM USED
	$query=$_SESSION["QUERY"]." asc";
}

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
 <?php if ($mylang == 'lang_france') {  ?>
<title>Direct-Lens &mdash; Programme de loyauté</title>
<?php  }else{ ?>
<title>Direct-Lens &mdash; My Reward History</title>
<?php } ?>


<!--<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
      }
    </script>
<!--<![endif]-->-->
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />


    
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

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function validate(theForm)
{

  if (theForm.TRAY.value== "")
  {
    alert("You must enter a value in the \"Tray Reference\" field.");
    theForm.TRAY.focus();
    return (false);
  }
  }

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>



<script src="../formFunctions.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form  method="post" name="goto_date" id="goto_date" action="reward_history.php">
	
      <div class="loginText">
        <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        <?php print $_SESSION["sessionUser_Id"];?></div>
      <div class="header">
 <?php if ($mylang == 'lang_france') {  ?>
Programme de loyauté
<?php  }else{ ?>
My Reward History 
<?php } ?>

	  
	  
	  
	   </div>
      <table width="770" border="0" align="center" cellpadding="2" cellspacing="0" class="formBox">
   	  <tr bgcolor="#000000">
            		<td colspan="4" align="right" bgcolor="#17A2D2" class="formCell">&nbsp;</td>
            		</tr>

				<tr bgcolor="#DDDDDD">
				  <td width="20%" nowrap bgcolor="#FFFFFF" class="formCellNosides" ><div align="right"> Date
				      From </div></td>
				  <td align="left" bgcolor="#FFFFFF" class="formCellNoleft"><input name="date_from" type="text" class="formText" id="date_from" value=<?php $today=getdate(time()); 
		
			if ($_POST[date_from]!="")
				print "\"".$_POST[date_from]."\"";
			else
				print "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; 
				?> size="11">
						<a href="#" onclick="cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;" title="Popup calendar for quick date selection" name="anchor1xx" id="anchor1xx"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle" /></a>&nbsp;
						  Through&nbsp;
						  <input name="date_to" type="text" class="formText" id="date_to" value=<?php $today=getdate(time()); 
		
			if ($_POST[date_to]!="")
				print "\"".$_POST[date_to]."\"";
			else
				print "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; 
				?> size="11">
						<A HREF="#" onClick="cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor2xx" ID="anchor2xx"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A></td>
				  <td colspan="2" align="left" bgcolor="#FFFFFF" class="formCellNoleft"><input name="rpt_search" type="submit" id="rpt_search" value="Search" class="formText">
				    <input name="from_form" type="hidden" id="from_form" value="true"></td>
				  </tr>
			</table>
</form>

			<?php 
			if ($query!=""){
				$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error().$query);
			$usercount=mysql_num_rows($result);
			}	
		
if ($usercount != 0){
	$query="";

if ($_POST[from_form]=="true"){
	$message=$usercount . ' orders';
	}
else{$message="&nbsp;";}

print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\"><tr >
                <td colspan=\"8\" bgcolor=\"#17A2D2\" class=\"tableHead\">".$message."</td>";
  print "</tr>
              <tr>
                <td  align=\"center\" class=\"formCell\"><b>Order #</b></td>
				<td align=\"center\" class=\"formCell\"><b>";
				 if ($mylang == 'lang_france') {  
				echo 'Produit';
				}else{ 
				echo 'Product'; 
				} ?>

				
				<?php  print "</b></td>
                <td align=\"center\" class=\"formCell\"><b>Order Date</b></td>
  				<td align=\"center\" class=\"formCell\"><b>Reward points</b></td>
			</tr>";
               
			 	$point_balance_total = 0; 
while ($listItem=mysql_fetch_array($result)){

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$formated_date=mysql_result($new_result,0,0);
			
			if ($listItem[order_date_shipped]!="0000-00-00"){
				$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
				$ship_date=mysql_result($new_result,0,0);}
			else {
				if (($listItem[est_ship_date]!="0000-00-00")&&($listItem[est_ship_date]!=NULL)){
					$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[est_ship_date]','%m-%d-%Y')");
					$ship_date="<b>est:</b> ".mysql_result($new_result,0,0);}
				else{ $ship_date="<b>est:</b> TBD";}
				}
		print  "<tr>
                <td align=\"center\"  class=\"formCell\">$listItem[order_no]</td>
				 <td align=\"center\"  class=\"formCell\">$listItem[order_product_name]</td>
                <td align=\"center\"  class=\"formCell\">$formated_date</td>
				<td align=\"center\"  class=\"formCell\">$listItem[reward_points]</td>";
				$point_balance_total = 	$point_balance_total + $listItem[reward_points];
       print "</tr>";
	   
		}//END WHILE
		
		print "</table><br>";
		
  if ($mylang == 'lang_france') {  
print "<h4 align=\"center\">Total de points pour cette période:  " . $point_balance_total. " Points</h4>";
}else{ 
print "<h4 align=\"center\">Total  balance for this period:  " . $point_balance_total. " Points</h4>";
 }
 
}
else if ($query!=""){
	$query="";
		print "<div class=\"Subheader\">No Orders Found</div>";}//END USERCOUNT CONDITIONAL
?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFFFFF;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFFFFF;layer-background-color:white;"></DIV>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>