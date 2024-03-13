<?
include('codeBarreC128.class.php');

$code = new codeBarreC128('www.ilbee.net');
$code->setTitle();
$code->setFramedTitle(true);
$code->setHeight(50);
$code->Output();
?>