<?php
	$roomId = $_GET['room'];
	$song = $_GET['songid'];
	$findSong1Query = "SELECT * FROM Songs WHERE room = '$roomId' AND id = '$song'";
	$findSong1Returned = mysql_query($findSong1Query, $conn);
	$row = mysql_fetch_assoc($findSong1Returned);
	$song1ID = $row['id'];
	$song1NewPos = $row['place'] + 1;
	$findSong2Query = "SELECT * FROM Songs WHERE room = '$roomId' AND place = '$song1NewPos'";
	$findSong2Returned = mysql_query($findSong2Query, $conn);
	$row = mysql_fetch_assoc($findSong2Returned);
	$song2ID = $row['id'];
	$song2NewPos = $row['place'] - 1;
	$song1Update = "UPDATE Songs SET place = '$song1NewPos' WHERE id = '$song1ID'";
	$song1RetUpdate = mysql_query($song1Update, $conn);
	$song2Update = "UPDATE Songs SET place = '$song2NewPos' WHERE id = '$song2ID'";
	$song2RetUpdate = mysql_query($song2Update, $conn);
?>