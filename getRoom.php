<?php
	include("conn.php");
	$roomName = $_GET['room'];
	$password = $_GET['pwd'];
	$hostName = $_GET['host'];
	$dupQuery = "SELECT COUNT(*) FROM Rooms WHERE name = '$roomName' AND pwd = '$password'";
	$row = mysql_fetch_array(mysql_query($dupQuery));;
	if($row[0] == 0){
		$myQuery = "INSERT INTO Rooms (name, pwd, host) VALUES ('$roomName', '$password', '$hostName')";
		$result = mysql_query($myQuery, $conn);
		echo "success";
	}else{
		echo "found";
	}
	
?>