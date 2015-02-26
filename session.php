<?php
	$lastPage = $_GET['page'];
	$room =  $_GET['room'];
	session_start();
	if($lastPage == "create"){
		$_SESSION['userType'] = "host";
	}else{
		$_SESSION['userType'] = "client";
	}
	$_SESSION['homeRoom'] = $room;
?>