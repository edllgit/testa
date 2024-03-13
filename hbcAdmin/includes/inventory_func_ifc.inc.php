<?php

function update_inventory_IFC(){
	
	$lab_id=$_POST['lab_id'];
	
	$inventory=$_POST['inventory'];
	$prod_inventory_id=$_POST['prod_inventory_id'];
	$previous_inventory=$_POST['previous_inventory'];
	$previous_min_inventory=$_POST['previous_min_inventory'];
	$min_inventory=$_POST['min_inventory'];
	
	if ($lab_id!=""){
		foreach($inventory as $prod_id => $inventory_amount){
			if (empty($inventory_amount)){
				
				//DO NOTHING
			}
			else{//NOT EMPTY
				if ($prod_inventory_id[$prod_id]=="no value"){//NO INVENTORY TABLE ITEM - ADD ONE
					$sql="INSERT INTO product_inventory_ifc (lab_id, product_id, inventory, min_inventory) VALUES ('$lab_id','$prod_id', '$inventory_amount','$min_inventory[$prod_id]')";
					$result=mysql_query($sql)
					 	or die ("ERROR:".mysql_error());
					}
				else{
					if (($previous_inventory[$prod_id]!=$inventory_amount)||($previous_min_inventory[$prod_id]!=$min_inventory[$prod_id])){//ONLY UPDATE IF CHANGED
						$sql="UPDATE product_inventory_ifc SET inventory='$inventory_amount', min_inventory='$min_inventory[$prod_id]' WHERE product_inventory_id='$prod_inventory_id[$prod_id]'";
						$result=mysql_query($sql)
							or die ("ERROR:".mysql_error());
					}//END IF CHANGED
				}//END IF NO VALUE
			}//END NOT EMPTY
		}//END FOREACH
	}//END IF LAB ID
}
?>