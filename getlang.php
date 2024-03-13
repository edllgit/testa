<?php
//AFFICHER LES ERREURS

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/constants/ftp.constant.php');

mysqli_select_db($con,$database_directlens);
mysqli_query($con,"SET CHARACTER SET UTF8");
$query_languages = "SELECT * FROM languages";
$languages = mysqli_query($con,$query_languages) or die(mysqli_error($con));
$row_languages = mysqli_fetch_array($languages,MYSQLI_ASSOC);
$totalRows_languages = mysqli_num_rows($languages);


		if(!isset($_COOKIE["mylang"])){
			//assign eng
			$mylang = "lang_english";
			} else {
			$mylang = $_COOKIE["mylang"];
			}
			
	if ($_SERVER['HTTP_HOST']=="server.lensnetclub.com"){
		
	}	



	/////////////////////////////////////////////////////////////////////////////////////////////

	if ($_SERVER['HTTP_HOST']=="lensnetclub.com" || $_SERVER['HTTP_HOST']=="www.lensnetclub.com"){
		if (isset($_GET['ld'])){
			//assign french
			$mylang = "lang_french";
			setcookie("mylang","lang_french",0,"/");
			header("location:http://www.lensnetclub.com");
			}
	
		if(!isset($_COOKIE["mylang"])){
			//assign eng
			$mylang = "lang_english";
			} else {
			$mylang = $_COOKIE["mylang"];
			}
		if ($_SERVER['REQUEST_URI']=="/"){
			header("location:http://www.lensnetclub.com/lensnet");
		} 		
	}	
	
	
	

	//if they came from direct-lens.ca
	if ($_SERVER['HTTP_HOST']=="direct-lens.ca" || $_SERVER['HTTP_HOST']=="www.direct-lens.ca"){
	//load tunneling page
	header("location:https://www.direct-lens.com?rd=f");
	}

	if ($_SERVER['HTTP_HOST']=="direct-lens.net" || $_SERVER['HTTP_HOST']=="www.direct-lens.net"){
			header("location:https://www.direct-lens.com");
	}
	if (isset($_GET['rd'])){
			//assign french
			$mylang = "lang_french";
			setcookie("mylang","lang_french",0,"/");
			header("location:https://www.direct-lens.com");
	}
	if ($_SERVER['HTTP_HOST']=="direct-lens.com" || $_SERVER['HTTP_HOST']=="www.direct-lens.com"){
				
		if(!isset($_COOKIE["mylang"])){
			//assign eng
			$mylang = "lang_english";
			} else {
			$mylang = $_COOKIE["mylang"];
			
			}
		if($_SERVER['HTTPS'] == 'off' && $_SERVER['HTTP_HOST']!="www.lensnetclub.com" && $_SERVER['HTTP_HOST']!="lensnetclub.com"){
				header("location:https://www.direct-lens.com");
		}
	} 
	

mysqli_select_db($con,$database_directlens);
$query_languagetext = "SELECT * FROM ".$mylang;	
$languagetext = mysqli_query($con,$query_languagetext) or die(mysqli_error($con));
$row_languagetext = mysqli_fetch_array($languagetext,MYSQLI_ASSOC);
$totalRows_languagetext = mysqli_num_rows($languagetext);
do { 	
	$mystr = "$".$row_languagetext['progkey']."=\"".$row_languagetext['languagetext']."\";";
	eval($mystr);
	//echo $mystr."<br>";
	} while ($row_languagetext = mysqli_fetch_array($languagetext,MYSQLI_ASSOC)); 

?>
<?php
if (!function_exists("uploadfinish")) {
function uploadfinish(){
	$mybasketid=$_SESSION['PrescrData']['mybasketid'];
	//   use a data table instead of a session variable
	$basketquery="SELECT * from upload_basket WHERE basket_id ='$mybasketid'";
	//echo "query1: ".$query."<BR>";
	$mybasket=mysqli_query($con,$basketquery) or die($lbl_error1_txt . mysqli_error($con).$basketquery);
	$basketcount=mysqli_num_rows($mybasket);
	//echo "basketcount: ".$basketcount."<br>";
	
	if ($basketcount != 0){
			///////////////// change the file uploaded name to match the key
			while ($row_basket=mysqli_fetch_array($mybasket,MYSQLI_ASSOC)){
				$mypkey = $row_basket['order_id'];
				$orderquery="SELECT * from orders WHERE primary_key ='$mypkey'";
				$myorder=mysqli_query($con,$orderquery) or die($lbl_error1_txt . mysqli_error($con).$orderquery);
				$row_order=mysqli_fetch_array($myorder,MYSQLI_ASSOC);
					
				if ($row_order['myupload']){
					$main_lab = $row_order['prescript_lab'];
					$myupload = $row_order['myupload'];
					$orderNum = $row_order['order_num'];
				
				//echo "myupload: ".$myupload."<br>";
				//echo "orderNum: ".$orderNum."<br>";
				//echo "primary key: ".$mypkey."<br>";
				
					if ($myupload != 'none' || !$myupload){
							$myupload2 = $orderNum.".".substr($myupload, strrpos($myupload, '.') + 1);
							//echo "myupload2: ".$myupload2."<br>";
							$updatequery="update ORDERS set myupload = '$myupload2' WHERE primary_key ='$mypkey'";
							$updateresult=mysqli_query($con,$updatequery) or die ( "Query failed: " . mysqli_error($con) . "<br/>" . $updatequery );
					}
					rename("C:\\cdrive\\websites\\directlens\\holdingfiles\\".$myupload, "C:\\cdrive\\websites\\directlens\\holdingfiles\\".$myupload2);
					$file = "C:\\cdrive\\websites\\directlens\\holdingfiles\\".$myupload2;
				//echo "path to newfile: ".$file."<br><br>";
									
					if ($main_lab == 10)
					{
					//Copy the file ti Swisscoat FTP
					$remote_file = "FROM_DL/shapes/".$myupload2;
					// set up basic connection
					$conn_id = ftp_connect(constant('SWISSCOAT_FTP'));
					// login with username and password
					$login_result = ftp_login($conn_id, constant('FTP_USER_RCO'), constant('FTP_PASSWORD_RCO'));
					ftp_pasv($conn_id,true);
							
					// upload a file
					if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
						//echo "successfully uploaded $file\n";
						} else {
						// echo "There was a problem while uploading $file\n";
					}
					// close the connection
					ftp_close($conn_id);
					}
					
					if ($main_lab == 76)
					{
					//Copy the file to OVG_LAB FTP
					$remote_file = "Orders/".$myupload2;
					// set up basic connection
					$conn_id = ftp_connect(constant('OVG_LAB_FTP'));
					// login with username and password
					$login_result = ftp_login($conn_id, constant('FTP_USER_OVG_LAB'), constant('FTP_PASSWORD_OVG_LAB'));
					ftp_pasv($conn_id,true);
							
					// upload a file
					if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
						//echo "successfully uploaded $file\n";
						} else {
						// echo "There was a problem while uploading $file\n";
					}
					// close the connection
					ftp_close($conn_id);
					}
					
					
					if ($main_lab == 25)
					{ 
					//COPY THE FILE FOR HKO
					$remote_file = "FROM DIRECT-LENS/shapes/".$myupload2;
						// set up basic connection
					$conn_id = ftp_connect(constant("HKO_FTP"));
					// login with username and password
					$login_result = ftp_login($conn_id, constant('FTP_USER_HKO'), constant('FTP_PASSWORD_HKO_ALT'));
					ftp_pasv($conn_id,true);
							
					// upload a file
					if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
						//echo "successfully uploaded $file\n";
						} else {
						// echo "There was a problem while uploading $file\n";
					}
					// close the connection
					ftp_close($conn_id);
					}
					
					
				
				}
			}
	}
$_SESSION['PrescrData']['mybasketid'] = "";
}
}
?>
