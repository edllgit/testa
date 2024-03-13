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

//Do all work in UTC
date_default_timezone_set('UTC');


function feed_to_string($date, $feed, $format, $header){

	$feeds = get_feeds($date);

	$formats = array('xml'=>'application/xml', 
					'csv' => 'text/csv');

	//Validate feed name and format
	if (!isset($feeds[$feed])) die ("Feed '$feed' does not exist");
	if (!isset($formats[$format])) die ("Format '$format' is not supported. Only xml and csv are valid formats.");

	//Configure Exporter instance and run, on output stream
	$e = $feeds[$feed];
	$e->format = $format;
	$e->includeHeaderRow = $header == true;
	$e->targetUri = "php://memory";
	return $e->export();
}

echo feed_to_string(date('Y-m-d'),'conant','csv',true);