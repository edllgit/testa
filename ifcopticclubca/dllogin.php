<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Démarrer la session
session_start();

//Inclusions
include "../sec_connectEDLL.inc.php";
//include('../includes/phpmailer_email_functions.inc.php');
//require_once('../includes/class.ses.php');

//$login    = anti_injection($_POST[user_id],$_POST[password]); // on lance la fonction anti injection

$_SESSION["sessionUserData"]=login_to_dl($_POST[user_id], $_POST[password]);
$_SESSION["sessionUserData"]["id"]=stripslashes($_SESSION["sessionUserData"]["primary_key"]);
$_SESSION["sessionUserData"]["company"]=stripslashes($_SESSION["sessionUserData"]["company"]);
$_SESSION["sessionUserData"]["first_name"]=stripslashes($_SESSION["sessionUserData"]["first_name"]);
$_SESSION["sessionUserData"]["last_name"]=stripslashes($_SESSION["sessionUserData"]["last_name"]);
$_SESSION["sessionUserData"]["address1"]=stripslashes($_SESSION["sessionUserData"]["address1"]);
$_SESSION["sessionUserData"]["address2"]=stripslashes($_SESSION["sessionUserData"]["address2"]);
$_SESSION["sessionUserData"]["city"]=stripslashes($_SESSION["sessionUserData"]["city"]);
$_SESSION["sessionUserData"]["zip"]=stripslashes($_SESSION["sessionUserData"]["zip"]);
$_SESSION["sessionUserData"]["phone"]=stripslashes($_SESSION["sessionUserData"]["phone"]);
$_SESSION["sessionUserData"]["fax"]=stripslashes($_SESSION["sessionUserData"]["fax"]);
$_SESSION["sessionUserData"]["email"]=stripslashes($_SESSION["sessionUserData"]["email"]);
$_SESSION["sessionUser_Id"]=$_SESSION["sessionUserData"]["user_id"];
$_SESSION["product_line"]="ifcclubca";
$_SESSION["id"]=$_SESSION["sessionUserData"]["id"];
echo '<br>2b';

$_SESSION["CompteEntrepot"] = 'no';//Par default
//Nouveau pour  gérer les comptes entrepots 2015-03-10
switch($_SESSION["sessionUser_Id"]){//N'inclue pas les compte SAFE
	//ENTREPOTS FRANCOPHONES
	//Trois-Rivières
	case 'entrepotifc'		:  		 $_SESSION["CompteEntrepot"] = 'yes';  break;
	case 'entrepotframes'	:  		 $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Drummondville		
	case 'entrepotdr'		:  		 $_SESSION["CompteEntrepot"] = 'yes';  break;
	case 'entrepotdrframes' :  		 $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Longueuil		
	case 'longueuil'		:  		 $_SESSION["CompteEntrepot"] = 'yes';  break;
	case 'longueuilsafe' :  		 $_SESSION["CompteEntrepot"] = 'yes';  break;		
	//Laval
	case 'laval'			 : 	     $_SESSION["CompteEntrepot"] = 'yes';  break;
	case 'entrepotlavalframe':  	 $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Terrebonne	
	case 'terrebonne'			  :  $_SESSION["CompteEntrepot"] = 'yes';  break;
	case 'entrepotterrebonneframe':  $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Quebec
	case 'levis'			  :  	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	case 'entrepotlevisframe':  	 $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Sherbrooke
	case 'sherbrooke'		  :  	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	case 'entrepotsherbrookeframe':	 $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Chicoutimi
	case 'chicoutimi'		  :  	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	case 'entrepotchicoutimiframes': $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Granby
	case 'granby'		           : $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Montreal
	case 'montreal'		           : $_SESSION["CompteEntrepot"] = 'yes';  break;		
	//Québec
	case 'entrepotquebec'		   : $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Gatineau
	case 'gatineau'		   			: $_SESSION["CompteEntrepot"] = 'yes';  break;
	//Saint-Jérôme
	case 'stjerome'		   			: $_SESSION["CompteEntrepot"] = 'yes';  break;
	//ENTREPOTS ANGLOPHONES
	//Halifax	
	case 'warehousehal' 	 :  	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	case 'warehousehalframes':   	 $_SESSION["CompteEntrepot"] = 'yes';  break;	
	//Edmundston
	case 'edmundston' 	 :  	 	$_SESSION["CompteEntrepot"] = 'yes';  break;	
	case 'edmundstonsafe':   	 	$_SESSION["CompteEntrepot"] = 'yes';  break;	
}

echo '<br>passe ici';

$_SESSION['account_type'] = 'normal';//Comptes restricted n'existe plus
header("Location:lens_cat_selection.php");
	
	/*
	function anti_injection( $user, $pass ) {
# on regarde s'il n'y a pas de commandes SQL
    $banlist = array (
        "insert", "select", "update", "delete", "distinct", "having", "truncate",
        "replace", "handler", "like", "procedure", "limit", "order by", "group by" 
        );
    if ( eregi ( "[a-zA-Z0-9]+", $user ) ) {
        $user = trim ( str_replace ( $banlist, '', strtolower ( $user ) ) );
    } else {
        $user = NULL;
    }

    # on regarde si le mot de passe est bien alphanumérique
    # on utilise strtolower() pour faire marcher str_ireplace()
    if ( eregi ( "[a-zA-Z0-9]+", $pass ) ) {
        $pass = trim ( str_replace ( $banlist, '', strtolower ( $pass ) ) );
    } else {
        $pass = NULL;
    }

    # on fait un tableau
    # s'il y a des charactères illégaux, on arrête tout
    $array = array ( 'user' => $user, 'pass' => $pass );
    if ( in_array ( NULL, $array ) ) {
        die ( 'ERREUR : Injection SQL détectée' );
    } else {
        return $array;
    }
} // ##########*/





function login_to_dl($user_test, $password_test){/* check user_id and password on login */
include "../sec_connectEDLL.inc.php";	
	
$ip		      = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$level   	  = 'IFC Optic Club.ca';
$datetime 	  = date("Y-m-d G i:s");
$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	
$ip2 		  = $_SERVER['HTTP_X_FORWARDED_FOR'];	
	
$queryInsert="INSERT INTO login_attempt(username, password, datetime, ip, ip2, level, provient_de, browser) VALUES 
('$user_test', '$password_test', '$datetime', '$ip', '$ip2', '$level', '$provient_de', '$browser')";

echo $queryInsert.'<br>';

//$resultInsert=mysqli_query($con,$queryInsert)	or die ("Could not insert" . mysqli_error($con));
$resultInsert=mysqli_query($con,$queryInsert)	or die ("Could not insert" . mysqli_error($con));

$query="select * from accounts where  product_line = 'directlens' and user_id = '$user_test' and password = '$password_test'";

echo  'Query:'. $query.'<br>';

$result=mysqli_query($con,$query)		or die ("Could not find account");
	
	$query="SELECT * from accounts where product_line = 'ifcclubca' and user_id = '$user_test' and password = '$password_test'";
	echo $query. '<br>';
	$result=mysqli_query($con,$query)		or die ("Could not find account");
	$usercount=mysqli_num_rows($result);
	if ($usercount == 0)/* user id and/or password are not valid */
	{
		
	header("Location:loginfail.php");
	exit();
	}else{/* user id and password are valid and a match -- fetch user data  */
	$sessionUserData=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
		
	$date1 = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$ajd = date("Y/m/d", $date1);
	//We save the connexion as this customer last login
	$QueryLastLogin = "Update accounts set last_connexion = '$ajd' where user_id = '$user_test'";
	$resultLastLogin=mysqli_query($con,$QueryLastLogin)		or die ("Could not find account");

	$compPW=strcmp($password_test, $sessionUserData[password]);/* check that login password is case-sensitive to password in db*/
	if ($compPW != 0){
		header("Location:loginfail.php");
		exit();
		}
	if ($sessionUserData[approved] != "approved"){
		header("Location:loginpending.php");
		exit();
		}
	}
	return($sessionUserData);
}



function editAccount($sessionUser_Id)
{
	include_once("../Connections/sec_connect.inc.php");
	foreach($_POST as $x => $y){
		$_POST[$x] = addslashes($y);
	}
	reset ($_POST);
	
		if(($_POST[ship_address1]=="")||($_POST[ship_city]=="")||($_POST[ship_state]=="")||($_POST[ship_country]=="")){
		$_POST[ship_address1]=$_POST[bill_address1];
		$_POST[ship_address2]=$_POST[bill_address2];
		$_POST[ship_city]=$_POST[bill_city];
		$_POST[ship_state]=$_POST[bill_state];
		$_POST[ship_zip]=$_POST[bill_zip];
		$_POST[ship_country]=$_POST[bill_country];
	}else{
		$_POST[ship_address1]=ucwords($_POST[ship_address1]);
		$_POST[ship_address2]=ucwords($_POST[ship_address2]);
		$_POST[ship_city]=ucwords($_POST[ship_city]);
	}

	if(($_POST[newPW]!="")&&($_POST[oldPW]!="")){ /* if user is updating pw */
		$query="select user_id, password from accounts where user_id = '$sessionUser_Id'";
		$result=mysqli_query($con,$query)
			or die ("Could not find account");
		$PWtest=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$compPW=strcmp($_POST[oldPW], $PWtest[password]);/* check that login password is case-sensitive to password in db*/
		if ($compPW != 0){
			header("Location:pwproblem.php");
			exit();
		}else{
			$query="update accounts set password='$_POST[newPW]' where user_id = '$sessionUser_Id'";
			$result=mysqli_query($con,$query) or die ("Could not update password");
		}
	}

	$query="update accounts set title='$_POST[title]', first_name='$_POST[first_name]', last_name='$_POST[last_name]', company='$_POST[company]', business_type='$_POST[business_type]', buying_group='$_POST[buying_group]', VAT_no='$_POST[VAT_no]', bill_address1='$_POST[bill_address1]', bill_address2='$_POST[bill_address2]', bill_city='$_POST[bill_city]', bill_state='$_POST[bill_state]', bill_zip='$_POST[bill_zip]', bill_country='$_POST[bill_country]', ship_address1='$_POST[ship_address1]', ship_address2='$_POST[ship_address2]', ship_city='$_POST[ship_city]', ship_state='$_POST[ship_state]', ship_zip='$_POST[ship_zip]', ship_country='$_POST[ship_country]', phone='$_POST[phone]', other_phone='$_POST[other_phone]', fax='$_POST[fax]', email='$_POST[email]', currency='$_POST[currency]', purchase_unit='$_POST[purchase_unit]', mfg_pref='$_POST[mfg_pref]' where user_id = '$sessionUser_Id'";
	$result=mysqli_query($con,$query) or die ("Could not update account");
	$query="select * from accounts where user_id = '$sessionUser_Id'";
	$result=mysqli_query($con,$query) or die ("Could not find account");
return ($result);
}




function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because: ' . mysqli_error($con));	
}
?>
