<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>UEFA Champions League 2023/24 Draw Simulator</title>
		<link rel="stylesheet" href="style.css">
		<link rel="icon" type="image/png" href="img/favicon.png">
	</head>
	<body>

		<div class="container">
			<header>
			
			</header>
			<div class="title">
				<!-- TITLE -->
				UEFA Champions League 2023/24 Draw Simulator
			</div>
			
			<div class="col-1">
				<!-- left column -->
				
				<?php
					//opening a connection to MySQL database
					$user = "root";
					$pass = "";
					$host = "127.0.0.1";
					$dbname = "ucl_draw";
					
					$conn = new mysqli($host, $user, $pass, $dbname);
					
					if ($conn->connect_error) 
					{	
						die("Connection failed: " . $conn->connect_error);
					}
						
					$team_drawn = 0;	
						
					$choice = 0;	
						
					// counting how many teams were drawn and saving it to 'counter' variable
					$counter = 0;

					$drawn = mysqli_query($conn, "SELECT COUNT(name) AS drawn FROM teams WHERE groups != 0");
					while($c = $drawn->fetch_assoc()) 
					{ 
						$counter = $c["drawn"]; 
					}
					
					// calculating the current pot, increasing it every 8 teams drawn
					$pot = 1 + floor($counter / 8);
					
					echo "<h3>Draw</h3>";
					echo "<div class=\"bowl\"><p>Pick a ball!</p>";
					
					if($team_drawn == 0)
					{
						//form to draw teams
						echo "<form method=\"post\">";

						//selecting undrawn teams from given pot in random order
						$teams = mysqli_query($conn, "SELECT * FROM teams WHERE pot = $pot and groups = 0 ORDER BY rand()");
						while($t = $teams->fetch_assoc())
						{						
							echo "<button name=\"choice\" value=".$t["id"]." class=\"draw_ball\"><img src=\"img/ball_team.png\"></button>";
						}

						echo "</form>";

						//saving the user's choice
						if(isset($_POST["choice"])) 
						{ 
							$choice = $_POST["choice"];
						}

						//updating database
						if($pot == 1 && $choice !=0)
						{
							$drawn = "UPDATE teams SET groups = $counter+1 WHERE id = $choice;";
							$conn->query($drawn);
							header("Refresh:0");
						}
					}
					
					echo "</div>";


					// displaying a list of undrawn teams from the current pot
					echo "<table id=\"group\" style=\"width: 50%;\">";
					echo "<tr><th>Pot $pot</th></tr>";				
					
					$pot_teams = mysqli_query($conn, "SELECT t.name, t.country_id, c.id, c.short country FROM teams t, country c 
					WHERE c.id = t.country_id AND pot = $pot AND groups = 0 ORDER BY t.name");
					while($pt = $pot_teams->fetch_assoc())
					{
						echo "<tr><td>".$pt['name']." (".$pt['country'].")</td></tr>";				
					}
					echo "</table>";
					
					// displaying 8 groups
					for($i = 1; $i < 9; $i++)
					{	
						echo "<div class=\"group_container\">";
						echo "<table id=\"group\">";
						echo "<tr><th>GROUP $i</th></tr>";
						
						$groups = mysqli_query($conn, "SELECT t.*, c.short code FROM teams t, country c WHERE t.groups = $i AND c.id = t.country_id ORDER BY t.pot");
						while($g = $groups->fetch_assoc())
						{
							echo "<tr><td>".$g["name"]." (".$g['code'].")</td></tr>";
						}
						// adding empty rows if group contains less than 4 teams
						for($r = $groups->num_rows; $r < 4; $r++)
						{
							echo "<tr><td>-</td></tr>";	
						}
						
						echo "</table>";
						echo "</div>";
					}
				?>
				
				<button class="reset" name="reset">RESET</button>
				
			</div>

			<div class="col-2">
				<!-- right column -->
				<h3>Draw rules</h3>
				<p>The 32 teams were drawn into eight groups of four. For the draw, the teams were seeded into four pots, each of eight teams, based on the following principles:</p>

				<p>&raquo; Pot 1 contained the Europa League title holders, and the champions of the top seven associations based on their 2022–23 UEFA country coefficients.</p>
				<p>&raquo; Pot 2, 3 and 4 contained the remaining teams, seeded based on their 2022–23 UEFA club coefficients.</p>
				<p>Teams from the same association could not be drawn into the same group. Prior to the draw, UEFA formed pairings of teams from the same association (one pairing for associations with two or three teams, two pairings for associations with four or five teams) based on television audiences, where one team would be drawn into Groups A–D and another team would be drawn into Groups E–H, so that the two teams would play on different days. The following pairings were announced by UEFA after the group stage teams were confirmed:</p>
				<p>
				<p>&raquo; Manchester City and Manchester United</p>
				<p>&raquo; Sevilla and Atlético Madrid</p>
				<p>&raquo; Barcelona and Real Madrid</p>
				<p>&raquo; Napoli and Lazio</p>
				<p>&raquo; Bayern Munich and Borussia Dortmund</p>
				<p>&raquo; Paris Saint-Germain and Lens</p>
				<p>&raquo; Benfica and Porto</p>
				<p>&raquo; Feyenoord and PSV Eindhoven</p>
				<p>&raquo; Inter Milan and Milan</p>
				<p>&raquo; RB Leipzig and Union Berlin</p>
				<p>&raquo; Arsenal and Newcastle United</p>				
			</div>
			
			<footer>
				Author: Marek Kopystyński | <a href="https://github.com/markop87">Github/markop87</a>
			</footer>
		
		</div>
	</body>
</html>