<?php 
require_once("../../Connections/sec_connect.inc.php");
//General purpose data methods
require_once("data_functions.inc.php");

//XML, CSV, DB schema mappings
require_once("schemas.inc.php");
//Custom export filters
require_once("export_filters.inc.php");

//Feed definitions
require_once("feeds.inc.php");

//Support CLI
parse_str(implode('&', array_slice($argv, 1)), $_GET);

//Do all work in UTC
date_default_timezone_set('UTC');

//Time this file
$time_start = microtime(true);

//Parse querystring values and arguments
$feed = strtolower(isset($_GET['feed']) ? $_GET['feed'] : 'sct');
$format = strtolower(isset($_GET['format']) ? $_GET['format'] : 'csv');
$formats = array('xml'=>'application/xml', 
				'csv' => 'text/csv');
$header = isset($_GET['header']) ? ($_GET['header'] && strtolower($_GET['header']) !== "false") : true;

//Validate master key
$masterKey = $_GET['masterkey'];
if ($masterKey <> "InvolutedPyterodactylus") die ("You have specified an invalid 'masterkey' value.");

//Generate feed array
$feeds = get_feeds(date("Y-m-d"));


//Validate feed name and format
if (!isset($feeds[$feed])) die ("Feed '$feed' does not exist");
if (!isset($formats[$format])) die ("Format '$format' is not supported. Only xml and csv are valid formats.");



//Send HTTP headers definiting type
header('Content-type: '.$formats[$format]);
header('Pragma: public');
header('Content-disposition: attachment;filename='.$feed.'_'.$today.'.'.$format);

//Configure Exporter instance and run, on output stream
$e = $feeds[$feed];
$e->format = $format;
$e->includeHeaderRow = $header;
$e->export();




//Log elapsed time
$elapsed = microtime(true) - $time_start;
$title = 'Feed: '.$e->comment. ' - ' . $feed;
$cron_method = $feed.' (orderfeed.php)';
$today = date("Y-m-d");// current date
$time = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('$title', '$elapsed','$today','$time','$cron_method') "  ; 					
$cronResult=mysql_query($CronQuery);
?>
