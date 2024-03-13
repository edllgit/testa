<?php
	switch($listItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";				break;
						case 'order imported':			$order_status = "Order Imported";			break;
						case 'job started':				$order_status = "In Production";			break;
						case 'in coating':				$order_status = "In Coating";				break;
						case 'in mounting':				$order_status = "In Mounting";				break;
						case 'in edging':				$order_status = "In Edging";				break;
						case 'order completed':		    $order_status = "Order Completed";			break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";		break;
						case 'in transit':				$order_status = "In Transit";				break;
						case 'filled':					$order_status = "Shipped";					break;
						case 'cancelled':				$order_status = "Cancelled";				break;
						case 'waiting for frame store':		$order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$order_status = "Waiting for Frame Head Office/Supplier";		break;
	}
	
	 if ($mylang == 'lang_french'){
		$order_status = "Envoyer";	
		}else {
		$order_status = "Shipped";	
		}

	if($runningBalance < 0)
		$sign = "-$";
	else
		$sign = "$";
	$formatBalance=$sign . money_format('%.2n',abs($runningBalance));
?>
	<tr><td class="formCellNosides"><?php echo "$listItem[order_num]"; ?></td>
                <td class="formCellNosides"><?php echo "$order_date"; ?></td>
                <td class="formCellNosides"><?php echo "$ship_date"; ?></td>
 <?php /*?>                 <td class="formCellNosides"><?php if($_POST[stmt_sort]=="account") echo "$listItem[po_num]"; else  echo "$listItem[company]"; ?></td><?php */?> 
 
              <td class="formCellNosides"><?php echo "$listItem[order_patient_first] $listItem[order_patient_last]"; ?></td>
                
                <td class="formCellNosides"><?php echo "$listItem[patient_ref_num]"; ?></td>
              <td class="formCellNosides"><div align="right"><?php echo "\$$orderTotal"; ?></div></td>
                <td class="formCellNosides"><div align="center"><?php echo "$pmt_status"; ?></div></td>
                <td class="formCellNosides"><?php echo "$pmt_type"; ?></td>
                <td class="formCellNosides" nowrap="nowrap"><div align="right"><?php if($pmt_amount!=0) echo "\$$pmt_amount"; ?></div></td>
                <td class="formCellNosides"><div align="right"><?php echo "$formatBalance"; ?></div></td>
</tr>