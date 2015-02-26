<?php
	include("conn.php");
	$token = $_GET['token'];
	$source = $_GET['source'];
	$account = $_GET['account'];
	$room = $_GET['room'];
	$song = $_GET['song'];
	$query = "INSERT INTO Songs (account, station, token, source, room) VALUES ('$account', '$song', '$token', '$source', '$room')";
	$returnedInsertQuery = mysql_query($query, $conn);
?>