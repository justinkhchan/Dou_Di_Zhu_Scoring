<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>DDZ - Scoring</title>
	<style>
		@import url(//fonts.googleapis.com/css?family=Lato:700);

		body {
			margin:0;
			font-family:'Lato', sans-serif;
			text-align:center;
			color: #030303;
		}

		.titleBar {
			width: 100%;
			top: 10px;
			margin-top: 10px;
			text-align:center;
		}
		
		.mainContent {
			width: 100%;
			margin-top: 10px;
			text-align:center;
		}

		#scoreTable	{
			border: 1px solid #AAAAAA;
			border-collapse: collapse;
			margin: 0px auto;
		}
		
		.scoreClass {
			border: 1px solid #AAAAAA;
			padding: 5px;
		}
		
		#textNumBombs {
			width: 50px;
		}

		a, a:visited {
			text-decoration:none;
		}

		h1 {
			font-size: 32px;
			margin: 16px 0 0 0;
		}
	</style>
	<script type="text/javascript" src="/playernames"></script>
	<script type="text/javascript">
	
		var scoreModifier = 10;
	
		/*
		var playerNames = new Array();
		playerNames[0] = "Player 0";
		playerNames[1] = "Player 1";
		playerNames[2] = "Player 2";
		playerNames[3] = "Player 3";
		playerNames[4] = "Player 4";
		
		var playerInitials = new Array();
		playerInitials[0] = "P0";
		playerInitials[1] = "P1";
		playerInitials[2] = "P2";
		playerInitials[3] = "P3";
		playerInitials[4] = "P4";
		*/
	
		
		var playerTotalScores = new Array(0,0,0,0,0);	// total scores of all games played so far
		var playerWins = new Array(0,0,0,0,0);
		var playerLosses = new Array(0,0,0,0,0);
		var totalGames = 0;		// number of games played

		var playerGameScores = new Array();	// two dimensional array of each game's scores
		
		function insertPlayerNames() {
			var scoreTable = document.getElementById("scoreTable");
			var rowNames = scoreTable.insertRow(-1);
			rowNames.className = "scoreClass";
			
			var cellNames = new Array();
			cellNames[0] = rowNames.insertCell(0);
			//cellNames[0].innerHTML = "";
			cellNames[0].className = "scoreClass";
			for (var count = 0; count < 5; count++) {
				cellNames[count+1] = rowNames.insertCell(count+1);
				cellNames[count+1].innerHTML = playerNames[count];
				cellNames[count+1].className = "scoreClass";
			}
			
			var lastCell = rowNames.insertCell(6);
			lastCell.innerHTML = "Notes";
			lastCell.className = "scoreClass";
		}
		
		function insertNextGameRow() {
			var scoreTable = document.getElementById("scoreTable");
			var row = scoreTable.insertRow(-1);
			row.className = "scoreClass";
			
			var cellNames = new Array();
			cellNames[0] = row.insertCell(0);
			cellNames[0].innerHTML = "Game " + (totalGames + 1) + ": ";
			cellNames[0].className = "scoreClass";			
			for (var count = 0; count < 5; count++) {
				cellNames[count+1] = row.insertCell(count+1);
				cellNames[count+1].innerHTML = "<input type=\"checkbox\" id=\"win" + count + "\" value=\"true\" /> " +  playerInitials[count];
				cellNames[count+1].className = "scoreClass";
			}
			var lastCell = row.insertCell(6);
			lastCell.innerHTML = "<form><input type=\"radio\" name=\"numBombs\" id=\"zeroBomb\" checked /> 0b" + 
				"<input type=\"radio\" name=\"numBombs\" id=\"oneBomb\" /> 1b" + 
				"<input type=\"radio\" name=\"numBombs\" id=\"twoBomb\" /> 2b " + 
				"<input type=\"radio\" name=\"numBombs\" id=\"threeBomb\" /> 3b " + 
				"<input type=\"radio\" name=\"numBombs\" id=\"fourBomb\" /> 4b " +
				"<input type=\"text\" id=\"textNumBombs\" /> # Bombs " + 
				"<input type=\"checkbox\" id=\"spring\" /> Spring </form>";
			lastCell.className = "scoreClass";			
		}
		
		function updateRowTotals() {
			var scoreTable = document.getElementById("scoreTable");
			var row = scoreTable.insertRow(-1);
			row.className = "scoreClass";
			
			var cellNames = new Array();
			cellNames[0] = row.insertCell(0);
			cellNames[0].innerHTML = "Score Totals:";
			cellNames[0].className = "scoreClass";			
			for (var count = 0; count < 5; count++) {
				cellNames[count+1] = row.insertCell(count+1);
				cellNames[count+1].innerHTML = playerTotalScores[count];
				cellNames[count+1].className = "scoreClass";
			}
			
			var lastCell = row.insertCell(6);
			lastCell.innerHTML = "-";
			lastCell.className = "scoreClass";
		}

		function undoLastGame() {
			if (totalGames > 0) {
				var lastGameScores = playerGameScores.pop();
				totalGames--;
				
				for (var count = 0; count < 5; count++) {
					if (lastGameScores[count] != 0) {
						if (lastGameScores[count] < 0) {
							playerLosses[count]--;
						} else {
							playerWins[count]--;
						}
					}
					playerTotalScores[count] -= lastGameScores[count];
				}
				var scoreTable = document.getElementById("scoreTable");
				scoreTable.deleteRow(totalGames+1); // delete last game row
				scoreTable.deleteRow(totalGames+1); // delete game input row
				scoreTable.deleteRow(totalGames+1); // delete totals row
				insertNextGameRow();
				updateRowTotals();				
			} else {
				alert("No games played yet!");
			}
		}
		
		function calculateGame() {
			var numWinners = 0;
			var numLosers = 0;
			var gameModifier = 1;
			var gameNotes = "";
			
			totalGames++;
			
			for (var count = 0; count < 5; count++) {

				if (document.getElementById("win" + count).checked) {
					numWinners++;
				}
			}
			
			numLosers = 5 - numWinners;
			//document.getElementById("testDiv").innerHTML = "Winners: " + numWinners + ", Losers: " + numLosers;
			
			var textNumBombs = document.getElementById("textNumBombs").value;
			if (textNumBombs == "") {
				if (document.getElementById("zeroBomb").checked) {
					gameNotes += "Zero bombs";
				} else if (document.getElementById("oneBomb").checked) {
					gameNotes += "One bomb";
					gameModifier *= 2;
				} else if (document.getElementById("twoBomb").checked) {
					gameNotes += "Two bombs";
					gameModifier *= 4;				
				} else if (document.getElementById("threeBomb").checked) {
					gameNotes += "Three bombs";
					gameModifier *= 8;
				} else if (document.getElementById("fourBomb").checked) {
					gameNotes += "Four bombs";
					gameModifier *= 16;
				}
			} else {
				gameNotes += textNumBombs + " bombs";
				gameModifier *= (Math.pow(2, textNumBombs));
			}
			
			if (document.getElementById("spring").checked) {
				gameModifier *= numLosers;
				gameNotes += ", spring";
			}
			
			var scoreTable = document.getElementById("scoreTable");
			
			var row = scoreTable.insertRow(-1);
			row.className = "scoreClass";
			var cellNames = new Array();
			
			var scoresArray = new Array();
			cellNames[0] = row.insertCell(0);
			cellNames[0].innerHTML = "Game " + totalGames + ": ";
			cellNames[0].className = "scoreClass";	
			
			for (var count2 = 0; count2 < 5; count2++) {
				cellNames[count2+1] = row.insertCell(count2+1);
				if (document.getElementById("win" + count2).checked) {
					playerWins[count2]++;
					var currScore = scoreModifier * numLosers * gameModifier;
					scoresArray.push(currScore);
					playerTotalScores[count2] += currScore;
					cellNames[count2+1].innerHTML = currScore;
				} else {
					playerLosses[count2]++;
					var currScore = scoreModifier * numWinners * gameModifier * -1;
					scoresArray.push(currScore);
					playerTotalScores[count2] += currScore;
					cellNames[count2+1].innerHTML = currScore;
				}
				cellNames[count2+1].className = "scoreClass";
			}
			
			cellNames[6] = row.insertCell(6);
			cellNames[6].innerHTML = gameNotes;
			cellNames[6].className = "scoreClass";
			
			playerGameScores.push(scoresArray);
			
			scoreTable.deleteRow(totalGames); // delete game input row
			scoreTable.deleteRow(totalGames); // delete totals row
			insertNextGameRow();
			updateRowTotals();
		}
		
		window.onload = function() {
			insertPlayerNames();
			insertNextGameRow();
			updateRowTotals();
		};
		
		window.onbeforeunload = function() {
			if (totalGames > 0) {
				return "Close game? Statistics will be lost.";
			}
		};
	
	</script>
</head>
<body>
	<div class="titleBar">
		DDZ 鬥地主 (Fighting the Landlord) - Scoring
	</div>
	<div class="mainContent">
		<table id="scoreTable">
		</table>
		<div id="testDiv">
		</div>
		<div>

			<!-- <input type="button" value="New Row" onClick="insertNextGameRow();" /> -->
			<input type="button" value="Game Finished" onClick="calculateGame();" />
			<input type="button" value="Undo Last Game" onClick="undoLastGame();" />
		</div>
	</div>
</body>
</html>
