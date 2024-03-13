<?php
/*
//Afficher toutes les erreurs/avertissements
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
echo 'Passer les lignes du fichier de trace (OMA)<br>';


//$file = 'KUBIK KK3061 55.OMA';

//$file='ELEVENPARIS EPAM030 49.OMA';
//$file='MARC JACOBS MJ 1014 52.OMA';
$file='UNDER ARMOUR UA5034G 56.OMA';
$searchfor = 'DBL=';

// the following line prevents the browser from parsing this as HTML.
//header('Content-Type: text/plain');

// get the file contents, assuming the file to be readable (and exist)
$contents = file_get_contents($file);

// escape special characters in the query
$pattern = preg_quote($searchfor, '/');

// finalise the regular expression, matching the whole line
$pattern = "/^.*$pattern.*\$/m";

// search, and store all matching occurences in $matches
if (preg_match_all($pattern, $contents, $matches))
{
  //echo "Found matches:\n";
   //echo implode("\n", $matches[0]);
   $DBL_Fichier_Trace = implode("\n", $matches[0]);
}
else
{
   echo "Impossible de trouver la valeur du champ DBL";
   exit();
}

//echo  $DBL_Fichier_Trace;

$DBL_Fichier_Trace=str_replace(' ','',$DBL_Fichier_Trace);

$Longeur = strlen($DBL_Fichier_Trace);

echo 'Longeur du fichier: '. $Longeur.'<br>';

if ($Longeur==10){ //Ex: DLB=15.13
		$VraiDBL = substr($DBL_Fichier_Trace,4,5);
	}elseif($Longeur==9){//Ex: DBL=15.1
		$VraiDBL = substr($DBL_Fichier_Trace,4,4).'0';
	}elseif($Longeur==8){//Ex: DBL=15.1
		$VraiDBL = substr($DBL_Fichier_Trace,4,3) . '00';
	}//END IF
	
	echo '<br><br>Valeur du champ DBL: '. $VraiDBL


?>

