<?php 
	include("connect.php");
	$homeRoom = $_GET['room'];
	$songQ = "SELECT * FROM Songs WHERE room = '$homeRoom' AND place = '1'";
	$returnedQuery = mysql_query($songQ, $conn);
	$row = mysql_fetch_assoc($returnedQuery);
	//echo $row['token']."-".$row['']
?>