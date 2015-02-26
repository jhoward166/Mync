<!DOCTYPE html>
<html>
	<head>
		<?php
			include("conn.php")
		?>
		<link rel="stylesheet" type="text/css" href="stylesheet.css">
		<style>
			#banner {
				background-image: url("mynk.png");
				background-color: black;
				background-repeat: no-repeat;
			    height: 100px;
			    width: 100%;
			    background-position: center;
			    background-size: 20%;
			    margin: 0;
			    padding: 0;
			}
		</style>
		<script type="text/javascript">
			function checkDuplicate(){
				var pwd = document.getElementById("password").value;
				var room = document.getElementById("roomName").value;
				var host = document.getElementById("hostName").value;
				if(room.length == 0){
					alert("Please enter a Party ID");
				}else if(host.length == 0){
					alert("Please enter a Host Name.");
				}else{
					//alert(pwd+" "+room+" "+host);
					var ajaxResponse = "";
					if (window.XMLHttpRequest){
						xmlhttp=new XMLHttpRequest();
					}else{
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange=function(){
						if (xmlhttp.readyState==4 && xmlhttp.status==200){
						    ajaxResponse = xmlhttp.responseText;
					    }
					}
					xmlhttp.open("GET","getRoom.php?pwd="+pwd+"&host="+host+"&room="+room, false);
					xmlhttp.send();
					//alert(ajaxResponse);

					var ajaxResponse = "";
					if (window.XMLHttpRequest){
						xmlhttp=new XMLHttpRequest();
					}else{
						xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
					}
					xmlhttp.onreadystatechange=function(){
						if (xmlhttp.readyState==4 && xmlhttp.status==200){
						    ajaxResponse = xmlhttp.responseText;
					    }
					}
					xmlhttp.open("GET","session.php?page=create&room="+room, false);
					xmlhttp.send();

					location.href="http://www.emilygoetz.com/mynk/partyPage.php"
				}
			}
		</script>
	</head>

	<body>
		<div id="banner">
		</div>
		<center>
			<h1>Create A Party</h1>
			Party ID
			<input type="text" id="roomName">
			<br><br>
			Password
			<input type="password" id="password">
			<br><br>
			Host Name
			<input type="text" id="hostName">
			<br><br>

			<input type="button" onclick="checkDuplicate()"; value="Enter Party">
		</center>
	</body>

</html>