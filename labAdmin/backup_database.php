	<?php
	require_once(__DIR__.'/../constants/mysql.constant.php');

	function dump($table)
	{
	 $server = constant("MYSQL_HOST");
	 $database = constant("MYSQL_DB_DIRECT_LENS");
	 $user = constant("MYSQL_USER");
	 $password = constant("MYSQL_PASSWORD");
	 //Connexion à la base
	 $db = mysql_connect($server, $user, $password) or die(mysql_error());
	 mysql_select_db($database, $db) or die(mysql_error());
	 
	 $sql = 'SHOW CREATE TABLE '.$table;
	 $res = mysql_query($sql) or die(mysql_error().$sql);
	 if ($res)
	 {
	 $ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$datecomplete = date("Y-m-d", $ladate);
			
			
	   $backup_file = '../../backup_db_direct54_dirlens/backup_' . $datecomplete .'_'  . $table . '.sql.gz';
	   $fp = gzopen($backup_file, 'w');
	 
	   $tableau = mysql_fetch_array($res);
	   $tableau[1] .= ";\n";
	   $insertions = $tableau[1];
	   gzwrite($fp, $insertions);
	 
	   $req_table = mysql_query('SELECT * FROM '.$table) or die(mysql_error());
	   $nbr_champs = mysql_num_fields($req_table);
	   while ($ligne = mysql_fetch_array($req_table))
	   {
	    $insertions = 'INSERT INTO '.$table.' VALUES (';
	    for ($i=0; $i<$nbr_champs; $i++)
	    {
	     $insertions .= '\'' . mysql_real_escape_string($ligne[$i]) . '\', ';
	    }
	    $insertions = substr($insertions, 0, -2);
	    $insertions .= ");\n";
	    gzwrite($fp, $insertions);
	   }
	 } // fin if ($res)
	 mysql_free_result($res);
	 gzclose($fp);
	 return true;
	}
	 
	//appel de la fonction
	$dump = dump('access');
	$dump = dump('access_admin');
	$dump = dump('accounts');
	$dump = dump('accounts_stock_collections');
	$dump = dump('acct_collections');
	$dump = dump('acct_credit_limit');
	$dump = dump('additional_discounts');
	$dump = dump('buying_groups');
	$dump = dump('admin');
	$dump = dump('buying_levels');
	$dump = dump('coupon_codes');
	$dump = dump('collection_rewards');
	$dump = dump('coupon_use');
	$dump = dump('cron_duration');
	$dump = dump('dlab_orders');
	$dump = dump('est_ship_date');
	$dump = dump('employes');
	$dump = dump('exclusive');
	$dump = dump('exclusive_bu');
	$dump = dump('exclusive_test');
	$dump = dump('extra_products');
	$dump = dump('extra_product_orders');
	$dump = dump('extra_prod_price_lab');
	$dump = dump('frames');
	$dump = dump('frames_collections');
	$dump = dump('frames_colors');
	$dump = dump('ifc_products');
	$dump = dump('ifc_products_french');
	$dump = dump('labs');
	$dump = dump('labs_stock_redirections');
	$dump = dump('languages_admin');
	$dump = dump('languages');
	$dump = dump('lang_english');
	$dump = dump('lang_french');
	$dump = dump('lang_italian');
	$dump = dump('lang_spanish');
	$dump = dump('last_order_num');
	$dump = dump('liste_collection_info');
	$dump = dump('lnc_reward_history');
	$dump = dump('login_attempt');
	$dump = dump('manufacturer');
	$dump = dump('manufacturer_to_lab');
	$dump = dump('memo_codes');
	$dump = dump('memo_credits');
	$dump = dump('memo_credits_rebilling');
	$dump = dump('newletters');
	$dump = dump('memo_credits_temp');
	$dump = dump('order_num_master_id_ref');
	$dump = dump('orders');
	$dump = dump('order_master_id');
	$dump = dump('password_history');
	$dump = dump('payments');
	$dump = dump('payment_history');
	$dump = dump('prices');
	$dump = dump('products');
	$dump = dump('product_inventory_notification');
	$dump = dump('product_inventory_before_setzeros');
	$dump = dump('product_inventory');
	$dump = dump('products_info_exclusive');
	$dump = dump('products_info');
	$dump = dump('redo_reasons');
	$dump = dump('sales_managers');
	$dump = dump('sales_reps');	
	$dump = dump('status_history');	
	$dump = dump('statement_credits');	
	$dump = dump('salespeople');	
	$dump = dump('stock_discounts');	
	$dump = dump('stock_collections');	
	$dump = dump('superadmin_access');
	$dump = dump('tickets');		
	$dump = dump('super_admin');		
	$dump = dump('supplier_order_report');				

?>