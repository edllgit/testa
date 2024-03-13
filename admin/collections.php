<?php include("../sec_connectEDLL.inc.php"); ?>

<?php
function inmylist($myid,$collid){
	include("../sec_connectEDLL.inc.php"); 
	mysqli_select_db($con,$database_directlens);
	$query_rs = "SELECT * FROM acct_collections where acct_id = '".$myid."' and collection_id = '".$collid."'";
	$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
	$row_rs = mysqli_fetch_array($rs,MYSQLI_ASSOC);
	$totalRows_rs = mysqli_num_rows($rs);
if($totalRows_rs > 0){
	return " checked='yes'";
	}else{
		return " ";
		}
}
?>


<?php
function writemycollections($acctid){
	include("../sec_connectEDLL.inc.php"); 
	mysqli_select_db($con,$database_directlens);
	$query_collectionlist = "delete FROM acct_collections where acct_id = '".$acctid."'";
	$collectionlist = mysqli_query($con,$query_collectionlist) or die(mysqli_error($con));
		
	//////// check fields for ccheckmark
	$query_collist = "select * FROM liste_collection_info";
	$collist = mysqli_query($con,$query_collist) or die(mysqli_error($con));
	$row_collist = mysqli_fetch_array($collist,MYSQLI_ASSOC);
	$totalRows_collist = mysqli_num_rows($collist);
	do {
		$myfield = "chk".$row_collist['liste_collection_id'];
		//echo $myfield."--".$_POST[$myfield]."<br>";
		if(isset($_POST[$myfield])){
			mysqli_select_db($con,$database_directlens);
			$query_rs = "insert into acct_collections (acct_id,collection_id) values  ('".$acctid."','".$row_collist['liste_collection_id']."')";
			$rs = mysqli_query($con,$query_rs) or die(mysqli_error($con));
		}		
	} while ($row_collist = mysqli_fetch_array($collist,MYSQLI_ASSOC)); 
}?>

<?php
function showcollections($acctid){
	include("../sec_connectEDLL.inc.php"); 
	//mysqli_select_db($con,$database_directlens);
	mysqli_select_db($con,$directlens);
	$query_collectionlist = "SELECT * FROM liste_collection_info";
	$collectionlist = mysqli_query($con,$query_collectionlist) or die(mysqli_error($con));
	$row_collectionlist = mysqli_fetch_array($collectionlist,MYSQLI_ASSOC);
	$totalRows_collectionlist = mysqli_num_rows($collectionlist);
?>
<?php 
$mycolor = "#ffffff";
$cycle = 1;
do { ?>
<?php if ($cycle == 1){$mycolor = "#ffffff";
		$cycle = 0;
	 	} else {$mycolor = "#e1e1e1";
		$cycle = 1;}?>
 <div style="background-color:<?php echo $mycolor; ?>; padding:5px;">
<input type="checkbox" name="<?php echo "chk".$row_collectionlist['liste_collection_id'];?>" id="<?php echo "chk".$row_collectionlist['liste_collection_id'];?>" <?php echo inmylist($acctid,$row_collectionlist['liste_collection_id']); ?> 
value="yes"><?php echo $row_collectionlist['collection_name'];?></div>
<?php } while ($row_collectionlist = mysqli_fetch_array($collectionlist,MYSQLI_ASSOC)); 

}?>

<input type="hidden" name="updatecollections" id="updatecollections" value="update">
