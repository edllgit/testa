   <?php 
   /*
   <form id="<?php print $listItem[order_item_number] ?>" name="<?php print $listItem[order_item_number] ?>" method="post" action="basket.php">
      <tr>
       <td colspan="8"  bgcolor="#FFFFFF" class="formCellNosides">
           <?php  if ($mylang == 'lang_french') echo 'Montant du paiement:'; else echo 'Payment Amount:';?>
           <input name="payment_amount" type="text" class="formText" id="payment_amount" value="<?php echo $PaidAmount; ?>" size="5" />
           
           <?php  if ($mylang == 'lang_french') echo 'Payé à:'; else echo 'Paid At:';?>
           <select name="paid_at" id="paid_at" >
           		<option value="entrepot trois-rivieres" <?php if ($PaidAt=='entrepot trois-rivieres') echo ' selected'; ?>>Entrepot Trois-Rivières</option>
                <option value="entrepot laval"          <?php if ($PaidAt=='entrepot laval')          echo ' selected'; ?>>Entrepot Laval</option>
                <option value="entrepot quebec"         <?php if ($PaidAt=='entrepot quebec')         echo ' selected'; ?>>Entrepot Québec</option>
           </select> 

           <input name="textVar<?php print $listItem[primary_key]?>" type="hidden" id="textVar<?php print $listItem[primary_key]?>" value="EMPTY"/>		   
           <input name="Submit"         type="submit" value="<?php  if ($mylang == 'lang_french') echo 'Appliquer le paiement'; else echo 'Apply Payment';?>" class="formText" />
           <input name="pkey"           type="hidden" value="<?php print $listItem[primary_key]?>" />
           <input name="orderTotal"     type="hidden" value="<?php print $itemSubtotal?>" />
           <input name="apply_payment"  type="hidden" value="true" />
       </td> 
       </tr>
   </form>*/
?>
