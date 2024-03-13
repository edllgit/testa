<link href="admin.css" rel="stylesheet" type="text/css" />
<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
include "../sec_connectEDLL.inc.php";

	if ($_SESSION["accessid"] <> ""){
	$queryAccess = "SELECT * FROM access WHERE id=" . $_SESSION["accessid"];
	$resultAccess=mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
	$AccessData=mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);
	
	
	
	$check_credit_status		            = $AccessData[check_credit_status];
    $report_shipped_today					= $AccessData[report_shipped_today];
	$access_customer_portrait				= $AccessData[access_customer_portrait];
	$lnc_reward_management					= $AccessData[lnc_reward_management];
	$print_order_with_price					= $AccessData[print_order_with_price];
	$print_order 							= $AccessData[print_order];
	$enter_payment_notification				= $AccessData[enter_payment_notification];
	$limited_order_report_and_status_update = $AccessData[limited_order_report_and_status_update];
	$order_report 							= $AccessData[order_report];
	$late_job_report						= $AccessData[late_job_report];
	$coupon_code_usage_report				= $AccessData[coupon_code_usage_report];
	$delay_order_report						= $AccessData[delay_order_report];
	$redirection_report						= $AccessData[redirection_report];
	$all_product_total						= $AccessData[all_product_total];
	$dream_ar_total							= $AccessData[dream_ar_total];
	$exclusive_products_total				= $AccessData[exclusive_products_total];
	$index_total							= $AccessData[index_total];
	$sales_reports							= $AccessData[sales_reports];
	$issue_monthly_credit					= $AccessData[issue_monthly_credit];
	$issue_memo_credit						= $AccessData[issue_memo_credit];
	$memo_codes								= $AccessData[memo_codes];
	$memo_credit_usage_report				= $AccessData[memo_credit_usage_report];	
	$print_monthly_statement				= $AccessData[print_monthly_statement];
	$pay_monthly_statement					= $AccessData[pay_monthly_statement];
	$lab_rebilling_statement				= $AccessData[lab_rebilling_statement];
	$product_inventory_report				= $AccessData[product_inventory_report];
	$supplier_order_report					= $AccessData[supplier_order_report];
	$update_product_inventory				= $AccessData[update_product_inventory];
	$product_inventory_ifc					= $AccessData[product_inventory_ifc];
	$update_inventory_notification_settings	= $AccessData[update_inventory_notification_settings];
	$process_product_inventory_orders		= $AccessData[process_product_inventory_orders];
	$extra_product_pricing					= $AccessData[extra_product_pricing];
	$can_view_sales_management_report		= $AccessData[can_view_sales_management_report];
	$can_approve_account					= $AccessData[can_approve_account];
	$can_edit_sales_manager					= $AccessData[can_edit_sales_manager];
	$can_manage_inventory					= $AccessData[can_manage_inventory];
	$can_manage_credit						= $AccessData[can_manage_credit];
	$can_edit_account						= $AccessData[can_edit_account];
	$listofallaccount 						= $AccessData[listofallaccount];
	$newsletter_management 					= $AccessData[newsletter_management];
	$report_last_login 						= $AccessData[report_last_login];
	$management_people 						= $AccessData[management_people];
	$manage_additional_discount				= $AccessData[manage_additional_discount];
	$manage_additional_item					= $AccessData[manage_additional_item];
	}
?>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField print_hidden">
	<tr><td><?php include("../translator.php"); ?></td></tr>     
    <tr bgcolor="#000000">
    	<td><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><div style="width:300px;text-align:center; margin:0px auto;">
        	<b><?php echo $adm_title_home;?></b></div><br /><b><?php echo $adm_account_txt;?></b></font>
        </td>
    </tr>
  
	<?php if ($can_edit_account == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
        <td align="left"><form action="getAccount.php" method="post" name="form1" id="form1">
			<?php echo $adm_selectexist_txt;?><br />
            <select name="acctName" id="acctName" class="formField">
            <option value=""><?php echo $adm_selectaccount_txt;?></option>
            <?php
            $query="SELECT primary_key, company, last_name, first_name FROM accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company, last_name";
            $resultAcct=mysqli_query($con,$query) or die ($adm_error1_txt);
            while ($accountList=mysqli_fetch_array($resultAcct,MYSQLI_ASSOC)){
            	echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
            }
            ?>
            </select>
            <input type="submit" name="Submit" value="<?php echo $btn_go_txt;?>" class="formField" />
            <br />
            <?php echo $adm_editordel_txt;?>
            </form>
        </td>
    </tr>
    <?php  }//end if can_edit_account ?>
    
    <?php if ($can_approve_account == "yes"){ ?>

    <tr>    
        <td align="left">
        <form action="getAccount.php" method="post" name="form2" id="form2">
			<?php echo $adm_selectnewacct_txt;?> <br />
            <select name="acctName" id="acctName" class="formField">
            <option value=""><?php echo $adm_selectaccount_txt;?></option>
            <?php
            $query="select primary_key, company, last_name, first_name from accounts where main_lab='$_SESSION[lab_pkey]' and approved='pending' order by company, last_name";
            $resultApprove=mysqli_query($con,$query)
            or die ($adm_error1_txt);
            while ($accountList=mysqli_fetch_array($resultApprove,MYSQLI_ASSOC)){
            echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
            }
            ?>
            </select>
            <input type="submit" name="Submit" value="<?php echo $btn_go_txt;?>" class="formField" />
            <br />
            <?php echo $adm_editordel_txt;?>
            </form>
        </td>
    </tr>
	<?php  }//end if can_approve_account ?>
      
    <?php if ($print_order == 'yes'){ ?>
	<tr bgcolor="#DDDDDD">
		<td align="left">
			<form target="_blank" action="fastPrint.php" method="post" name="form1" id="form1">
				<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer commande #';
                }else {
                echo 'Print Order #';
                }?>			
                <input type="text" name="print_order_num" id="print_order_num" size="7">  
                <input type="submit"  name="Submit" value="<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer';
                }else {
                echo 'Print';
                }?>" class="formField" />			
                <?php if ($print_order_with_price == "yes"){  ?>
                <input type="hidden" name="pr" value="yes">  
                <?php  } //end if ?>
            </form>
       	</td>
	</tr>
	<?php  }//end if Print order or  print_order_with_price ?>
    
    
    
      <?php if ($print_order == 'yes'){ ?>
	<tr>
		<td align="left">
			<form target="_blank" action="CreditPrint.php" method="post" name="form1" id="form1">
				<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer credit #';
                }else {
                echo 'Print Credit #';
                }?>			
                <input type="text" name="print_credit_num" id="print_credit_num" size="7">  
                <input type="submit"  name="Submit" value="<?php
                if ($mylang == 'lang_french' || $mylang == 'lang_France')
                {
                echo 'Imprimer';
                }else {
                echo 'Print';
                }?>" class="formField" />			
            </form>
       	</td>
	</tr>
	<?php  }//end if Print order ?>
   
   
   
   

    <?php
	$afficherReport = "no";
	
	if ($order_report == "yes")
	$afficherReport = "yes";
	
	if ($late_job_report == "yes")
	$afficherReport = "yes";
	
	if ($delay_order_report == "yes")
	$afficherReport = "yes";
	
	if ($redirection_report == "yes")
	$afficherReport = "yes";
	
	if ($all_product_total == "yes")
	$afficherReport = "yes";
	
	if ($dream_ar_total == "yes")
	$afficherReport = "yes";
	
	if ($exclusive_products_total == "yes")
	$afficherReport = "yes";
	
	if ($index_total == "yes")
	$afficherReport = "yes";
	
	if ($sales_reports == "yes")
	$afficherReport = "yes";
	
	if ($afficherReport == "yes") { ?>
    <tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><?php echo $adm_reports_txt;?></font></b></td>
   	</tr>
  	<?php  }//end mega if ?>
 
	<?php   
	$queryReport="select report_link from labs where primary_key='$_SESSION[lab_pkey]'";
	$resultReport=mysqli_query($con,$queryReport)		or die ('error');
	$DataReport=mysqli_fetch_array($resultReport,MYSQLI_ASSOC);
	$report_link = $DataReport['report_link'];
	?>  
   
	<?php if ($order_report == "yes"){ ?>
    <tr class="formField print_hidden">
    	<tr class="formField print_hidden">
    	<td align="left">
        	<p><a style="text-decoration:none;" href="report.php?reset=y">
       		<?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
			echo 'Rapport sur les commandes';
			}else {
			echo 'Order Reports';
			}
			?></a></p></td>
    </tr>    
    
    <?php  }//end if order_report ?>
    
    
	
	<?php if (($_SESSION["accessid"] <> '225')&&($_SESSION["accessid"] <> '226')) { ?>
     <tr class="formField print_hidden">
    	<tr class="formField print_hidden">
    	<td align="left">
        	<p><a style="text-decoration:none;" href="edll_fast_print.php?reset=y">
       		<?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
			echo 'EDLL Impression Rapide';
			}else {
			echo 'EDLL Fast Print';
			}
			?></a></p></td>
    </tr>    
    <?php  }//end if ?>
  
        

	<?php if ($late_job_report == "yes"){ ?>
    <tr  bgcolor="#FFFFFF">    
        <td bgcolor="#DDDDDD" align="left"><p><a style="text-decoration:none;" target="_blank" href="<?php echo $report_link;?> ">
			<?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Rapports sur les retards';
            }else {
            echo 'Late job report';
            }
            ?></a></p>
        </td>
    </tr>
    <?php  }//end if late_job_report ?>
    
    
    
	<?php if (($_SESSION["accessid"] <> '225')&&($_SESSION["accessid"] <> '226')) { ?>
    <tr  bgcolor="#FFFFFF">    
        <td  align="left"><p><a style="text-decoration:none;" href="rapport_monture_pour_labo.php">
			<?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Rapport montures envoyées au lab';
            }else {
            echo 'Report: Frames sent to the lab';
            }
            ?></a></p>
        </td>
    </tr>
      <?php  }//end if ?>
 

    <?php   
    $afficherAccounting = "no";
    if ($print_monthly_statement == "yes")
    $afficherAccounting = "yes";
    if ($pay_monthly_statement == "yes")
    $afficherAccounting = "yes";
    if ($lab_rebilling_statement == "yes")
    $afficherAccounting = "yes";
	?> 

	<tr  bgcolor="#FFFFFF">    
        <td  align="left">
			<p>
				<a style="text-decoration:none;" href="rapport_suivi_verres_credits.php"><?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Rapport: suivi des credits de verres';
            }else {
            echo 'Report: Glasses credits follow up';
            }
            ?></a>
			</p>
        </td>
    </tr>
    
    <tr>
		<td align="left"><p><a style="text-decoration:none;" href="logout.php"><?php echo $adm_logout_txt;?></a></p></td>
   	</tr>
    <tr><td><?php include("../translator.php"); ?></td></tr>
</table>
