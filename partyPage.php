<html>
<head>
	<title>Mynk</title>
	<link rel="stylesheet" type="text/css" href="partyStyles.css">
	<link rel="stylesheet" type="text/css" href="newstylesheet.css">
	<?php
		include("conn.php");
	?>
</head>
<body>
	<?php
		session_start();
		$homeRoom = $_SESSION['homeRoom'];
		echo $_SESSION['userType'];
		echo $_SESSION['homeRoom'];
	?>
	<script>
		

		/**/
		var ajaxResponse;
		if (window.XMLHttpRequest){
			xmlhttp=new XMLHttpRequest();
		}else{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function(){
			if (xmlhttp.readyState==4 && xmlhttp.status==200){
				ajaxResponse = xmlhttp.responseXML;
			}
		}
		xmlhttp.open("POST","http://192.168.1.15:8090/addStation", false);
		xmlhttp.send('<addStation source="PANDORA" sourceAccount="jhoward166@gmail.com" token="R5932">The Strokes</addStation>');
		//xmlhttp.send('<addStation source="PANDORA" sourceAccount="jhoward166@gmail.com" token="R323801">Pretty Lights</addStation>');
		console.log(ajaxResponse);
		alert("change");




		setInterval(function(){
			var ajaxResponse = "";
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					ajaxResponse = xmlhttp.responseXML;
				}
			}
			//alert(deleteThisRow.childNodes[15].value);
			xmlhttp.open("GET","http://192.168.1.15:8090/now_playing", false);
			xmlhttp.send();
			if(ajaxResponse.childNodes[0].childNodes[0].attributes.getNamedItem("source").childNodes[0].data == "INVALID_SOURCE"){
				//play next song
			}else if(ajaxResponse.childNodes[0].childNodes[0].attributes.getNamedItem("source").childNodes[0].data == "PANDORA"){
				document.getElementById("songPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[1].childNodes[0].data;
				document.getElementById("artistPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[2].childNodes[0].data;
				document.getElementById("lengthPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[6].childNodes[0].data;
			}else{
				document.getElementById("songPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[1].childNodes[0].data;
				document.getElementById("artistPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[2].childNodes[0].data;
				document.getElementById("lengthPlaying").childNodes[0].data = ajaxResponse.childNodes[0].childNodes[5].childNodes[0].data;
			}
			//console.log(ajaxResponse.childNodes[0].childNodes[0].attributes.getNamedItem("source").childNodes[0].data);
			//console.log(ajaxResponse);
		}, 1000);
	</script>
	<div id="banner">
		<a href="http://www.emilygoetz.com/mynk/index.php"><img src="mynk.png" /></a>
	</div>
	<div id="bg">
		<div id="description">
			<center><b>Now Playing:</b></center><br />
			
				<table class="tableStyle">
			<th class="tableStyle">Song</th>
			<th class="tableStyle">Artist</th>
			<th class="tableStyle">Length</th>
			<tr class="tableStyle">
				<td id="songPlaying" class="tableStyle">
				</td>
				<td id="artistPlaying" class="tableStyle">
				</td>
				<td id="lengthPlaying" class="tableStyle">
				</td>

			</tr>
		</table>

		<button id="setSource" type="button" onclick="addMusicSource();">Add Account</button>
		<select id="selectSource">
			<option value="PANDORA">Pandora</option>
			<option value="SPOTIFY">Spotify</option>
		</select>
		<input id="userName" placeholder="Username" type="text"/>
		<input id="password" placeholder="Password" type="password"/>
		<br>
		<button onclick="stationSearch()">Search Station</button>
		<select id="selectSearchSource">
			<option value="PANDORA">Pandora</option>
		</select>
		<input id="accountName" placeholder="Account Name" type="text"/>
		<input id="searchTerm" type="text" placeholder="Search Term"/>

		<br>
		<table align="center" id="queue" class = "tableStyle">
			<th class="tableStyle">Move</th>
			<th class = "tableStyle"> Order </th>
			<th class = "tableStyle"> Song Name </th>
			<th class = "tableStyle"> Artist </th>
			<th class = "tableStyle"> Length </th>
			<th class = "tableStyle"> Drop </th>
			<?php
				$songsInQueue = "SELECT * FROM Songs WHERE room = '1' ORDER BY place ASC";
				$returnedSongsQuery = mysql_query($songsInQueue, $conn);
				if(!$returnedSongsQuery){
					echo "Could not connect to Database";
				}else{
					while($row = mysql_fetch_assoc($returnedSongsQuery)){
						?>
							<tr class = "tableStyle">
								<td>
									<div class="centerIt">
										<button id="plusButton" align="center" class="changeQueueButtons" onclick="moveSongUpInQueue(this);">+</button>
										<br>
										<button id="minusButton" align="center" class="changeQueueButtons" onclick="moveSongDownInQueue(this);">-</button>
									</div>
								</td>
								<td class = "tableStyle">
									<?php
										echo $row['place'];
									?>
								</td>
								<td class = "tableStyle">
									<?php
										echo $row['songTitle'];
									?>
								</td>
								<td class = "tableStyle">
									<?php
										echo $row['artist'];
									?>
								</td>
								<td class = "tableStyle">
									<?php
										echo $row['length'];
									?>
								</td>
								<td class = "tableStyle">
									<button onclick="deleteAndMove(this);"> Drop </button>
								</td>
								<!-- row.childNodes[13].value accesses this data -->
								<input type="hidden" value = <?php echo $row['id']; ?> /> 
							</tr>
						<?php
					}
				}
			?>
		</table>
			
		</div>
	</div>
	
	<script>
		/*
		function is called by the add song button. It takes the value the is currently in the songInput input field, and adds it to the end of the table;
		*/
		function latch(){

			var socket = io.connect('http://192.168.1.161:3000');
		}
		function anotherOne(){
			var songQueueTable = document.getElementById("queue");
			var songQueueTableSize = songQueueTable.rows.length;
			//alert(songQueueTableSize);
			//create a new row
			var newRow = songQueueTable.insertRow(songQueueTableSize);
			newRow.className = "tableStyle";

			var newHiddenId = document.createElement("input");
			newHiddenId.type="hidden";
			//newHiddenId.value = 

			//create the new cells
			var newMoveCell = newRow.insertCell(0);
			newMoveCell.className = "tableStyle";
			var newOrderCell = newRow.insertCell(1);
			newOrderCell.className = "tableStyle";
			var newSongCell = newRow.insertCell(2);
			newSongCell.className = "tableStyles";
			var newArtistCell = newRow.insertCell(3);
			newArtistCell.className = "tableStyle";
			var newTimeCell = newRow.insertCell(4);
			newTimeCell.className = "tableStyle";
			var newDropCell = newRow.insertCell(5);
			newTimeCell.className = "tableStyle";
			
			var newButtonDiv=document.createElement("div");
			newButtonDiv.className = "centerIt";
			//create buttons
			var newPlusButton =document.createElement("input");
			newPlusButton.type="button";
			newPlusButton.className = "changeQueueButtons";
			newPlusButton.value = "+";
			newPlusButton.onclick = function(){
				moveSongUpInQueue(this);
			}

			var newNewline = document.createElement("br");

			var newMinusButton =document.createElement("input");
			newMinusButton.type = "button";
			newMinusButton.className = "changeQueueButtons";
			newMinusButton.value = "-";
			newMinusButton.onclick = function(){
				moveSongDownInQueue(this);
			}

			var newDropButton =document.createElement("input");
			newDropButton.type = "button";
			newDropButton.value = "Drop";
			newDropButton.onclick = function(){
				deleteAndMove(this);
			}

			//create text objects
			var newOrderText = document.createTextNode(songQueueTableSize);
			var newSongText = document.createTextNode("Song Name Here");
			var newArtistText = document.createTextNode("That Artist You Like");
			var newTimeText = document.createTextNode("ONE MILLION YEARS DUNGEON");

			newButtonDiv.appendChild(newPlusButton);
			newButtonDiv.appendChild(newNewline);
			newButtonDiv.appendChild(newMinusButton);
			newMoveCell.appendChild(newButtonDiv);
			newOrderCell.appendChild(newOrderText);
			newSongCell.appendChild(newSongText);
			newArtistCell.appendChild(newArtistText);
			newTimeCell.appendChild(newTimeText);
			newDropCell.appendChild(newDropButton);

							
		}

		function moveSongUpInQueue(callingRow){
			var lowerRow = callingRow.parentNode.parentNode.parentNode;
			var songQueueTable = lowerRow.parentNode;
			var rowIndex = lowerRow.rowIndex;
			var higherRow = lowerRow.parentNode.rows.item(rowIndex-1);
			if(rowIndex != 1){
				var tempSongName = lowerRow.cells.item(2).childNodes[0].data;
				var tempArtist = lowerRow.cells.item(3).childNodes[0].data;
				var tempLength = lowerRow.cells.item(4).childNodes[0].data;
				lowerRow.cells.item(2).childNodes[0].data = higherRow.cells.item(2).childNodes[0].data;
				lowerRow.cells.item(3).childNodes[0].data = higherRow.cells.item(3).childNodes[0].data;
				lowerRow.cells.item(4).childNodes[0].data = higherRow.cells.item(4).childNodes[0].data;

				higherRow.cells.item(2).childNodes[0].data = tempSongName;
				higherRow.cells.item(3).childNodes[0].data = tempArtist;
				higherRow.cells.item(4).childNodes[0].data = tempLength;
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
				//alert(deleteThisRow.childNodes[15].value);
				xmlhttp.open("GET","moveUpInQueue.php?room="+getElementById("homeRoom").value+"&songid="+lowerRow.childNodes[15].value, false);
				xmlhttp.send();
			}

		}

		function moveSongDownInQueue(callingRow){
			var higherRow = callingRow.parentNode.parentNode.parentNode;
			var songQueueTable = higherRow.parentNode;
			var rowIndex = higherRow.rowIndex;
			var lowerRow = higherRow.parentNode.rows.item(rowIndex+1);
			if(rowIndex !=  songQueueTable.rows.length-1){
				//alert(lowerRow.cells.item(2).childNodes[0].data);
				var tempSongName = higherRow.cells.item(2).childNodes[0].data;
				var tempArtist = higherRow.cells.item(3).childNodes[0].data;
				var tempLength = higherRow.cells.item(4).childNodes[0].data;

				higherRow.cells.item(2).childNodes[0].data = lowerRow.cells.item(2).childNodes[0].data;
				higherRow.cells.item(3).childNodes[0].data = lowerRow.cells.item(3).childNodes[0].data;
				higherRow.cells.item(4).childNodes[0].data = lowerRow.cells.item(4).childNodes[0].data;

				lowerRow.cells.item(2).childNodes[0].data = tempSongName;
				lowerRow.cells.item(3).childNodes[0].data = tempArtist;
				lowerRow.cells.item(4).childNodes[0].data = tempLength;
			
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
				//alert(deleteThisRow.childNodes[15].value);
				xmlhttp.open("GET","moveDownInQueue.php?room="+getElementById("homeRoom").value+"&songid="+higherRow.childNodes[15].value, false);
				xmlhttp.send();
			}
			
		}

		function deleteAndMove(callingRow){
			var deleteThisRow = callingRow.parentNode.parentNode;
			var songQueueTable = deleteThisRow.parentNode;
			var indexOfRow = deleteThisRow.rowIndex;
			for(var i = indexOfRow+1; i < songQueueTable.rows.length; i++){
				for(var j = 2; j<songQueueTable.rows.item(i).cells.length-1; j++){
					songQueueTable.rows.item(i-1).cells.item(j).childNodes[0].data = songQueueTable.rows.item(i).cells.item(j).childNodes[0].data;
				}
			}
			songQueueTable.deleteRow(songQueueTable.rows.length-1);
			//alert(songQueueTable.rows.length-1);
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
			//alert(deleteThisRow.childNodes[15].value);
			xmlhttp.open("GET","deleteSongFromQueue.php?room="+getElementById("homeRoom").value+"&songid="+deleteThisRow.childNodes[15].value, false);
			xmlhttp.send();
			//alert(ajaxResponse);
		}

		function addMusicSource(){
			var source = document.getElementById("selectSource").value;
			var user = document.getElementById("userName").value;
			var pwd = document.getElementById("password").value;
			var ajaxResponse = "";
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					ajaxResponse = xmlhttp.responseXML;
				}
			}
			//alert(deleteThisRow.childNodes[15].value);
			xmlhttp.open("POST","http://192.168.1.15:8090/setMusicServiceAccount", false);
			xmlhttp.send('<credentials source="'+source+'" displayName=""> <user>'+user+'</user> <pass>'+pwd+'</pass> </credentials>');
			console.log(ajaxResponse);
		}

		function stationSearch(){
			var source = document.getElementById("selectSource").value;
			var account = document.getElementById("accountName").value;
			var term = document.getElementById("searchTerm").value;
			var ajaxResponse = "";
			if (window.XMLHttpRequest){
				xmlhttp=new XMLHttpRequest();
			}else{
				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			xmlhttp.onreadystatechange=function(){
				if (xmlhttp.readyState==4 && xmlhttp.status==200){
					ajaxResponse = xmlhttp.responseXML;
				}
			}
			//alert(deleteThisRow.childNodes[15].value);
			xmlhttp.open("POST","http://192.168.1.15:8090/searchStation", false);
			xmlhttp.send('<search source="'+source+'" sourceAccount="'+account+'">'+term+'</search>');
			for(var i=0; i<ajaxResponse.childNodes[0].childNodes.length; i++){
				for(j=0; j<ajaxResponse.childNodes[0].childNodes[i].childNodes[j]; j++){
					 var token = ajaxResponse.childNodes[0].childNodes[i].childNodes[j].attributes.getNamedItem("token").data;
				}

			}
			console.log(ajaxResponse);
		}
	</script>
</body>
</html>
