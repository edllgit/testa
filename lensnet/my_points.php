<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

include("../Connections/sec_connect.inc.php");

$ladate 		  = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete     = date("Y-m-d", $ladate);		
$queryAccountNum  = "SELECT primary_key from accounts where user_id = '". $_SESSION["sessionUser_Id"]  . "'";
$ResultAccountNum = mysql_query($queryAccountNum)	or die ("Could not find account");
$DataAccountNum   = mysql_fetch_array($ResultAccountNum);
$pkey			  = $DataAccountNum[primary_key];
$query            = "select  lnc_reward_points, user_id, company from accounts WHERE primary_key = '$pkey'";
$acctResult		  = mysql_query($query)	or die ("Could not find account");
$Data			  = mysql_fetch_array($acctResult);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="UTF-8">
<title>LensNet Club</title>

<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
<script src="../formFunctions.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>

<script>
var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>
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

</head>


<body onLoad="doOnLoad();">
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
 <?php 	include("includes/sideNav.inc.php");?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<form action="my_points.php" method="post" enctype="application/x-www-form-urlencoded"><div class="header">
<div class="bigwelcome">
<?php if ($mylang == 'lang_french') { 
echo 'MES OPTI-POINTS';
echo '<br><font size="-2">Pour en apprendre davantage sur ce programme: <a target="_blank" style="text-decoration:none" href="http://www.direct-lens.com/lensnet/images/Opti-Points_fr-verso.jpg">cliquez ici</a></font><br>';
}else{ 
echo 'MY OPTI-POINTS';
echo '<br><font size="-2">Learn more about this program: <a target="_blank"  style="text-decoration:none" href="http://www.direct-lens.com/lensnet/images/Opti-Points_en-verso.jpg">Click here</a></font><br>';
} ?>
</div>
</div>




<?php
$query="select  lnc_reward_points, user_id, company from accounts WHERE primary_key = '$pkey'";
$acctResult=mysql_query($query)	or die ("Could not find account");
$Data=mysql_fetch_array($acctResult);
?>

<br /><br /><br />
<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
<tr>
				
 <?php if ($pkey <> ''){  ?>   
<table width="300" border="0" align="center" class="formBox">
    <tr> 
        <td><?php if ($mylang == 'lang_french') { 
                echo 'Compagnie:';
                }else{ 
                echo 'Company:';
                 }?></td> 
        <td><b><?php echo $Data[company];?></b></td>
    </tr>
    <tr>
        <td><?php if ($mylang == 'lang_french') { 
                echo 'Points disponibles:';
                }else{ 
                echo 'Points available:';
                 }?>
        </td>
        <td><b><?php echo $Data[lnc_reward_points];?> Point<?php if($Data[lnc_reward_points] > 0) echo 's';  ?></b></td>
    </tr>
    </table>


     
 <?php
$aujourdhui = mktime(0,0,0,date("m"),date("d"),date("Y"));
$ilyaunmois = mktime(0,0,0,date("m")-1,date("d"),date("Y"));
$datecompleteIlya1Mois = date("Y-m-d", $ilyaunmois);	 
$dateaujourdhui = date("Y-m-d", $aujourdhui);	

if ($_POST[date_from]<> '')
{
	$date1 = $_POST[date_from];
	$date2 = $_POST[date_to];
}else{
	$date1 = $datecompleteIlya1Mois;
	$date2 = $dateaujourdhui;
}
$HistoryQuery="select * from lnc_reward_history WHERE datetime between '$date1' and  '$date2'   AND  user_id= '" . $Data[user_id] . "' ORDER BY lnc_reward_id DESC"; 
$HistoryResult=mysql_query($HistoryQuery)	or die  ('I cannot select items because: ' . mysql_error());
$nbrResult = mysql_num_rows($HistoryResult);
?>
       
       
     <br />
        <table width="98%" border="3" cellpadding="2" cellspacing="0" class="formField3">
        <tr>
            <td colspan="5" align="center">
            
            <?php if ($mylang == 'lang_french') 
				  { 
                  	echo 'Du: ';
                  }else{ 
                  	echo 'Date From: ';
                  }?>
           
            
            <input name="date_from" type="text" class="formText" id="date_from" value=<?php $today=getdate(time()); 
                    
                        if ($_POST[date_from]!="")
                            echo "\"".$_POST[date_from]."\"";
                        else
                            echo $datecompleteIlya1Mois
                            ?> size="11">
             <?php if ($mylang == 'lang_french') 
				  { 
                  	echo 'Au: ';
                  }else{ 
                  	echo 'Through: ';
                  }?>
            
            
            <input name="date_to" type="text" class="formText" id="date_to" value=<?php $today=getdate(time()); 
                    
                        if ($_POST[date_to]!="")
                            echo "\"".$_POST[date_to]."\"";
                        else
                            echo $dateaujourdhui; 
                            ?> size="11"> &nbsp;&nbsp;<input type="submit" name="Filter" value="Filter" />
            </td>
        </tr>
       
       
       
       
        <tr bgcolor="#000000">
        	<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
            <?php if ($mylang == 'lang_french') { 
            echo 'Programme de loyauté';
            }else{ 
            echo 'Loyalty Program';
             } ?></font></b>
             </td>
            	
            <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
            <?php if ($mylang == 'lang_french') { 
            echo 'Points';
            }else{ 
            echo 'Points';
             } ?></font></b>
             </td>
         
        	<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Date</font></b></td>
        
            <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if ($mylang == 'lang_french') { 
            echo 'Détail';
            }else{ 
            echo 'Detail';
             } ?></font></b>
             </td>
         
             <td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if ($mylang == 'lang_french') { 
            echo 'No. Commande';
            }else{ 
            echo 'Order No.';
             } ?></font></b>
         	</td>
            
         </tr>
           
      

			<?php 
			$totalOptipoints = 0;

			while ($HistoryData=mysql_fetch_array($HistoryResult)){
				$num_rows = mysql_num_rows($HistoryResult);
				$QueryAccess="select * from access_admin WHERE id=" . $HistoryData['access_id'] ; 
				$ResultAccess=mysql_query($QueryAccess)	or die  ('I cannot select items because: ' . mysql_error());
				$DataAccess=mysql_fetch_array($ResultAccess);
				$totalOptipoints = $totalOptipoints +  $HistoryData['amount'];
				
				echo '<tr><td align="center">';
				if ($mylang == 'lang_french') { 
                	switch($HistoryData['loyalty_program']){
						case 'Platinum':  echo 'Platine'; break;
						case 'Gold' : 	  echo 'Or'; 	  break;
						case 'Silver':    echo 'Argent';  break;
						case 'None': 	  echo 'Aucun';   break;
					}
                }else{ 
                	echo  $HistoryData['loyalty_program'];
                }
				echo  '</td>';
			
			
			
			
			
			
				echo '<td align="center">';
				echo $HistoryData['amount'];
				echo  '</td>';
				
				echo '<td align="center">';
				echo  substr($HistoryData['datetime'],0,10);
				echo  '</td>';
				
				echo   '<td align="center">';
				if ($mylang == 'lang_french') { 
           	 		echo $HistoryData['detail_fr'] . ' ';
           	    }else{ 
            		echo $HistoryData['detail']    . ' ';
            	} 
				echo  '</td>';
				
				echo '<td align="center">';
				echo  $HistoryData['order_num'];
				echo  '</td>';

			    echo '<tr>';
				
			}//End WHILE
?>			
<tr>
	<td align="center">Total:</td>
    <td align="left" colspan="5"><b><?php echo  $totalOptipoints; ?> Optipoints</b></td>
</tr>
<?php
}//End if ($pkey <> '')
?>
			
            
           </table> 
			  </tr>
		     
              <tr>
                  <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="100" height="1"></td>
                  <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="50" height="1"></td>
                  <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="100" height="1"></td>
                  <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="50" height="1"></td>
              </tr>
			</table>

		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>

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
</body>
</html>
<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>