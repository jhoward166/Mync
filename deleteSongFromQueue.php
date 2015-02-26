<?php
	include("conn.php");
	$roomId = $_GET['room'];
	$song = $_GET['songid'];
	$findSongQueueQuery = "SELECT * FROM Songs WHERE id = '$song' AND room = '$roomId'";
	$findSongReturned = mysql_query($findSongQueueQuery, $conn);
	$row = mysql_fetch_assoc($findSongReturned);
	$queuePos = $row['place'];
	$findSongsUpdate = "SELECT * WHERE room = '$roomId' AND place > '$queuePos'";
	$songsUpdateReturned = mysql_query($findSongsUpdate, $conn);
	while($row = mysql_fetch_assoc($songsUpdateReturned)){
		$newPos = $row['place'] - 1;
		$changeQueuePosQuery = "UPDATE Songs SET place = '$newPos' WHERE room = '$roomId' AND place > '$queuePos'"
		$ChangePosReturned = mysql_query($changeQueuePosQuery, $conn);
	}
	$deleteEntryQuery = "DELETE FROM Songs WHERE id = '$song' AND room = '$roomId'";
	$deleteReturned = mysql_query($deleteEntryQuery, $conn);
	if(!$deleteReturned){
		echo $deleteEntryQuery;
		echo mysql_error($conn);
		echo "error";
	}
//dontworryaboutit
?>
