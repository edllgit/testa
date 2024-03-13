<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once('../Connections/directlens.php');
require_once('../Connections/sec_connect.inc.php');
include '../sales/salesmath.php';

/// set up the date
		$mysqldate1 = date('Y-m-d');
    
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}


mysql_select_db($database_directlens, $directlens);
$query_sales_staff = sprintf("SELECT * FROM sales_reps where id = '".$_GET["sid"]."'");
$sales_staff = mysql_query($query_sales_staff, $directlens) or die(mysql_error());
$row_sales_staff = mysql_fetch_assoc($sales_staff);
$totalRows_sales_staff = mysql_num_rows($sales_staff);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens - Sales</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.listheader{
font-family:Arial, Helvetica, sans-serif;
font-weight:bold;
font-size:10px;	
}
.listvalue{
font-family:Arial, Helvetica, sans-serif;
font-weight:normal;
font-size:10px;	
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="135" valign="top">&nbsp;</td>
            <td width="685" valign="top">
              <div id="" class="header" style="padding-top:10px;height:25px">
                <div style="width:280px;">Manager Report - Orders</div>
                
                </div>
              <div style="width:720px;padding:10px;border:1px solid #000000;background-color:#ffffff">
                <div style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:14px;text-align:left;"><span style="width:300px"><?php echo date("m-d-y");?></span></div>
                <?php do { ?>
                  <table width="600" border="0" cellspacing="0" cellpadding="2">
                    <tr>
                      <td colspan="9" bgcolor="#96A7CB"><div style="padding:5px; border:1px solid #000000; background-color:#000066; color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><div style="width:300px"><?php echo $row_sales_staff['rep_name'];?>
                        - <?php echo $row_sales_staff['rep_email']; ?></div>
                        <div style="margin-top:2px;color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 10px;">Sales in Date Range: <?php echo "$".money_format('%.2n',getgrandtotalsales($row_sales_staff['id'],$mysqldate1,$mysqldate2));
	
	  ?>&nbsp;&nbsp;&nbsp;&nbsp;Commissions in Date Range: <?php echo "$".money_format('%.2n',getgrandtotalcomms($row_sales_staff['id'],$mysqldate1,$mysqldate2)); ?>
                          </div>
                        <div style="margin-top:2px;color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 10px;">Grand Total Sales: <?php echo "$".money_format('%.2n',allmysales($row_sales_staff['id']));
	
	  ?>&nbsp;&nbsp;&nbsp;&nbsp;Grand Total Commissions: <?php echo "$".money_format('%.2n',allmycomms($row_sales_staff['id'])); ?>
                          </div></div></td>
                      </tr><?php
					mysql_select_db($database_directlens, $directlens);
$query_orders = "SELECT * FROM orders LEFT JOIN labs on (orders.lab=labs.primary_key) LEFT JOIN accounts on (accounts.user_id = orders.user_id) WHERE sales_rep = '".$row_sales_staff['id']."' and order_date_shipped = '".$mysqldate1."' order by company asc, order_date_shipped asc";
$orders = mysql_query($query_orders, $directlens) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
?>         
                    <?php $mycustomer = $row_orders["user_id"]; ?>             
                    <?php $mycolor = 1; ?>
                    
                    <tr>
                      <td colspan="5" bgcolor="#96A7CB"><span class="listheader"  style="font-size:12px">Orders Placed by : <?php echo $row_orders["company"]."<br>(".$totalRows_orders." Orders)"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                        Customer Number: <?php echo "#".$row_orders["account_num"]; ?> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                      <td colspan="2" bgcolor="#96A7CB"><span class="listheader">Total Sales: $<?php echo money_format('%.2n',getmytotalsales($row_orders["account_num"],$mysqldate1,$mysqldate2));?></span></td>
                      <td colspan="2" bgcolor="#96A7CB"><span class="listheader">Total Commissions: $<?php echo money_format('%.2n',getmytotalcommissions($row_orders["account_num"],$mysqldate1,$mysqldate2));?></span></td>
                      </tr>
                    <tr>
                      <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                        Number:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                        Quantity:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                        Status:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Date <br />
                        Shipped:</span></td>
                      <td colspan="2" bgcolor="#B4C8F3"><span class="listheader">Order Total:</span><span class="listheader"><br />
                        Sales Commission:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Patient <br />
                        Name:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Laboratory:</span></td>
                      <td bgcolor="#B4C8F3"><span class="listheader">Lab Phone  Number:</span></td>
                      </tr>
                    
                    <?php 
		$themonth = "0";
		$theyear = "0";
		do { ?>
                      <?php if ($mycustomer != $row_orders["user_id"]){
			$mycustomer = $row_orders["user_id"];
		?>  
                      
                      <tr>
                        <td colspan="5" bgcolor="#96A7CB"><span class="listheader" style="font-size:12px">Orders Placed by Customer: <?php echo $row_orders["company"]; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br />
                          Customer Number: <?php echo "#".$row_orders["account_num"]; ?> &nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                        <td colspan="2" bgcolor="#96A7CB"><span class="listheader">Total Sales: $<?php echo money_format('%.2n',getmytotalsales($row_orders["account_num"],$mysqldate1,$mysqldate2));?></span></td>
                        <td colspan="2" bgcolor="#96A7CB"><span class="listheader">Total Commissions: $<?php echo money_format('%.2n',getmytotalcommissions($row_orders["account_num"],$mysqldate1,$mysqldate2));?></span></td>
                        </tr>
                      <tr>
                        <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                          Number:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                          Quantity:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Order <br />
                          Status:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Date <br />
                          Shipped:</span></td>
                        <td colspan="2" bgcolor="#B4C8F3"><span class="listheader">Order Total:</span><span class="listheader"><br />
                          Sales Commission:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Patient <br />
                          Name:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Laboratory:</span></td>
                        <td bgcolor="#B4C8F3"><span class="listheader">Lab Phone  Number:</span></td>
                        </tr>
  <?php } 

?>
                      <?php if($mycolor == 1){ $thecolor = "#e1e1e1"; $mycolor = 2;}else{$thecolor = "#ffffff"; $mycolor = 1;} ?>
                      <?php 
		 if ((($themonth != date('M',strtotime($row_orders["order_date_shipped"]))) or ($theyear != date('Y',strtotime($row_orders["order_date_shipped"])))) || ($mycustomer != $row_orders["user_id"])){
			 if ($mycustomer != $row_orders["user_id"]){
				 $mycustomer = $row_orders["user_id"];
			 }
			$themonth = date('M',strtotime($row_orders["order_date_shipped"]));
			$theyear = date('Y',strtotime($row_orders["order_date_shipped"]));
		?>  
                      <tr><td colspan="9">
                        <?php 
		echo "<div class='listheader' style='padding:5px; background:#B4C8F3;text-align:center;'>";
		echo $themonth."-".date('Y',strtotime($row_orders["order_date_shipped"]));
		$mymosales = getmysalesformonth($mycustomer,$row_orders["order_date_shipped"]);
		$mycredmo = getmycredformonth($mycustomer,$row_orders["order_date_shipped"]);
		$myfinal = $mymosales - $mycredmo;
		$myfinal = $myfinal * ($row_orders['sales_commission']/100);
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."Sales for Month: $".$mymosales;
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."Credit for Month: $".$mycredmo;
		echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."Final Commissions for Month: $".$myfinal."  (".$row_orders['sales_commission']."%)";
		echo "</div>"; 
		?>
                        </td></tr>
                      <?php 

		} ?>
                      <tr bgcolor="<?php echo $thecolor;?>">
                        <td>
                          <span class="listheader"><?php echo $row_orders['order_num']; ?></span></td>
                        <td><span class="listheader"><?php echo $row_orders['order_quantity']; ?></span></td>
                        <td><span class="listheader"><?php echo $row_orders['order_status']; ?></span></td>
                        <td><span class="listheader"><?php echo $row_orders['order_date_shipped']; ?></span></td>
                        <td colspan="2"><span class="listheader"><?php echo $row_orders['order_total']; ?><br />
                          </span><span class="listheader"><?php echo money_format('%.2n',$row_orders['order_total'] * ($row_orders['sales_commission']/100)); ?>(<?php echo $row_orders['sales_commission']; ?>%)</span></td>
                        <td><span class="listheader"><?php echo $row_orders['order_patient_last']; ?>, <?php echo $row_orders['order_patient_first']; ?></span></td>
                        <td><span class="listheader"><?php echo $row_orders['lab_name']; ?></span></td>
                        <td><span class="listheader"><?php echo $row_orders['phone']; ?></span></td>
                        </tr><?php 
		  } while ($row_orders = mysql_fetch_assoc($orders)); ?>
                    <tr>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="60" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="60" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="80" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="80" height="1" /></td>
                      <td colspan="2"><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="120" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="110" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="80" height="1" /></td>
                      <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="80" height="1" /></td>
                      </tr>
                    </table>
                  <?php } while ($row_sales_staff = mysql_fetch_assoc($sales_staff)); ?>
                </div>
              </td>
            </tr>
  </table></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($orders);

mysql_free_result($sales_staff);
?>
