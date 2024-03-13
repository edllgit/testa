<?php

//XML, CSV, DB schema mappings
require_once("schemas.inc.php");

//Array utility functions
function pair_array($array){
	$n = array();
	for($i = 0, $size = count($array); $i < $size-1; $i += 2) {
		$n[$array[$i]] = $array[$i + 1];
	}
	return $n;
}

function map_fields(&$map, &$from, &$to){
	foreach($map as $key => &$value)
		$to[$value] = $from[$key];
	return $to;
}
function map_fields_inv(&$map, &$from, &$to){
	foreach($map as $key => &$value)
		$to[$key] = $from[$value];
	return $to;
}

function copy_fields(&$list, &$from, &$to){
	foreach($list as $key)
		$to[$key] = $from[$key];
	return $to;
}



function q_array($query){
	$result = mysql_query($query) or die ("\nFailed to complete query $query\n". mysql_error()."\n\n");
	return mysql_fetch_array($result);
}

function set_fields(&$array, $fields, $value){
	foreach($fields as $key)
		$array[$key] = $value;
}

function apply_xml_schema(&$data, &$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$key = $schema[$i];
		$n[$key] = isset($data[$key]) ?  $data[$key] : "";
	}
	return $n;
}
function apply_csv_schema(&$data, &$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$key = $schema[$i];
		$n[] = isset($data[$key]) ? $data[$key] : "";
	}
	return $n;
}
function get_header_for(&$schema){
	$n = array();
	for($i = 0, $size = count($schema); $i < $size-1; $i += 2) {
		$n[] = $schema[$i + 1];;
	}
	return $n;
}

function write_xml_row(&$writer, &$values){
	$writer->startElement('row');
	foreach($values as $key => $value){
		$writer->startElement($key);
		$writer->text($value);
		$writer->endElement();
	}
	$writer->endElement();
}

class Exporter{
	public $targetUri ="php://output";
	public $query;
	public $schema;
	public $format = 'xml';
	public $includeHeaderRow = true;
	public $filter;
	public $comment;

	public function __construct(){
	}

	public function export(){
		$isMem = ($this->targetUri == "php://memory");

		$csv = strcasecmp($this->format, 'csv') == 0;
		//$tdf = strcasecmp($format, 'tdf') == 0;
		$xml = strcasecmp($this->format, 'xml') == 0;

		if ($csv)
			$f = fopen($this->targetUri,'w');

		if (($csv) && $this->includeHeaderRow){
			fputcsv($f,get_header_for($this->schema));
		}
		if ($xml){
			$w = new XmlWriter();
			if ($isMem) 
				$w->openMemory();
			else
				$w->openURI($this->targetUri);

			$w->setIndent(true);
        	$w->setIndentString(' ');
			$w->startDocument();
			$w->startElement('rows');
			$w->startComment();
			$w->text($this->comment);
			$w->endComment();
		}

		//Run main query
		$result=mysql_query($this->query)	or die  ('I cannot select items because: ' . mysql_error());
		while ($r = mysql_fetch_array($result)){
			//Get completed row
			$r = call_user_func($this->filter,&$r);

			//Limit to schema and serialize
			if ($csv){
				fputcsv($f,apply_csv_schema($r, $this->schema));
			}else if ($xml){
				write_xml_row($w,apply_xml_schema($r, $this->schema));
			}

		}
		if (isset($w)){
			$w->endDocument();
			if ($isMem)
				return $w->outputMemory();
			else
				$w->flush();
		}
		if (isset($f)){
			$contents = true;
			if ($isMem){
				rewind($f);
				$contents = stream_get_contents($f);
			}
			fclose($f);
			return $contents;
		}

	}

}


?>