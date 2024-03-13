<?php
session_start();
include("../Connections/sec_connect.inc.php");

	$addData[title]=ucwords(addslashes($_POST[title]));
	$addData[email]=ucwords(addslashes($_POST[email]));
	$addData[message]=ucwords(addslashes($_POST[message]));
	$addData[priority]=$_POST[priority];
	$addData[author]=ucwords(addslashes($_POST[author]));


$date1 = mktime(0,0,0,date("m"),date("d"),date("Y"));
$fulldate = date("l, d-M-y", $date1);


if($_SESSION["sessionUser_Id"]!=""){
$addData[user_id] =$_SESSION["sessionUser_Id"];
}
	

if ($_SESSION[labAdminData][username]==""){
$addData[lab_id] =$_SESSION[labAdminData][username];
}
	


	$query="insert into tickets (title, message, priority,author,email,date,user_id) 
	values 
	('$addData[title]', '$addData[message]', '$addData[priority]', '$addData[author]','$addData[email]','$fulldate','$addData[user_id]')";
	

	$result=mysql_query($query)		or die ("Could not create ticket: " . mysql_error());
			
	$message="Nouveau ticket de support.\r\n\r\n";
	$message.="NEW SUPPORT TICKET INFORMATION\r\n\r\n";
	$message.="Date: $fulldate \r\n";
	$message.="Author: $addData[author] \r\n";
	$message.="Email: $addData[email] \r\n";
	$message.="Priority: $addData[priority]\r\n";
	$message.="Title: $addData[title]\r\n\r\n";
	$message.="Message: $addData[message]\r\n\r\n";
	$headers = "From: info@direct-lens.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("dbeaulieu@direct-lens.com", "New direct-lens.com Ticket Request", "$message", "$headers");
	$emailSent = mail("dbeaulieu@direct-lens.com", "New direct-lens.com Ticket Request", "$message", "$headers");



	header("Location:ticketThanks.php");
?>
