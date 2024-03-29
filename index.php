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

					if(isset($_GET['team'])) 
					{
						$team_drawn = $_GET['team'];
					}
						
					$choice = 0;
					$possibilities = array();
						
					// counting how many teams were drawn and saving it to 'counter' variable
					$counter = 0;

					$drawn = mysqli_query($conn, "SELECT COUNT(name) AS drawn FROM teams WHERE groups != 0");
					while($c = $drawn->fetch_assoc()) 
					{ 
						$counter = $c["drawn"]; 
					}
					
					// calculating the current pot, increasing it every 8 teams drawn
					$pot = 1 + floor($counter / 8);
					
					// counting how many groups left during draw in current pot
					// groupA -> groups 1-4, group B -> groups 5-8
					$groupA = 4; $groupB = 4; 
					
					// counting possibilities in groups 1 - 4 and in groups 5 - 8
					$possibilitiesA = 0; $possibilitiesB = 0;
					
					// calculating how many available groups are left in halves 1-4 and 5-8
					$check_teams = mysqli_query($conn, "SELECT * FROM teams WHERE pot = $pot AND groups != 0");
					while($ct = $check_teams->fetch_assoc()) 
					{
						if($ct['groups'] < 5) { $groupA--; }
						else if($ct['groups'] > 4) { $groupB--; }
					}
					
					echo "<h3>Draw</h3>";

					if($pot < 5)
					{
					echo "<div class=\"bowl\"><p>Pick a ball!</p>";
					}
					else echo "<div class=\"bowl\"><h2>Draw concluded.</h2>";
					
					if($team_drawn == 0)
					{
						//form to draw teams
						echo "<form method=\"post\">";

						//selecting undrawn teams from given pot in random order
						$teams = mysqli_query($conn, "SELECT * FROM teams WHERE pot = $pot and groups = 0 ORDER BY rand()");
						while($t = $teams->fetch_assoc())
						{						
							echo "<button name=\"choice\" value=".$t["id"]."><img src=\"img/ball_team.png\"></button>";
						}

						echo "</form>";

						//saving the user's choice
						if(isset($_POST["choice"])) 
						{ 
							$choice = $_POST["choice"];
						}

						//updating database in pot 1 or going to draw group in pot 2, 3 and 4
						if($pot == 1 && $choice !=0)
						{
							$drawn = "UPDATE teams SET groups = $counter+1 WHERE id = $choice;";
							$conn->query($drawn);
							header("Refresh:0");
						}
						else if($pot != 1 && $choice != 0)
						{
							header("Location: index.php?team=$choice");
						}
					}
					else
					{
						include 'possibilities.php'; //file with CheckPossibilities function

						$possibilities = CheckPossibilities($conn, $team_drawn);
						
						// An array to count how many teams can be placed in a given group
						$occurrences = array(0, 0, 0, 0, 0, 0, 0, 0);
						
						$check_rivals = mysqli_query($conn, "SELECT * FROM teams WHERE pot = $pot AND groups = 0 AND id != $team_drawn");
						while($cr = $check_rivals ->fetch_assoc())
						{
							// checking possibilities of every undrawn teams
							$check = CheckPossibilities($conn, $cr['id']);
							
							// saving team possibilities to occurrences array
							for($i = 0; $i < 8; $i++)
							{
								if(array_key_exists($i, $check)) { $occurrences[$i]++; }
							}

							// counting how many teams can be placed in groups 1-4 and groups 5-8
							if(max($check) < 5) { $possibilitiesA++; }
							else if(min($check) > 4) { $possibilitiesB++; }

							// If the number of teams that can be placed in a given half of the 
							// groups is equal to the available places in that half of the groups, 
							// the drawn team will not be able to be placed there.
							if(($possibilitiesA == $groupA && $possibilitiesB != 0) || ($possibilitiesA == $groupA && max($possibilities) > 4))
							{
								$possibilities = array_diff($possibilities, [1, 2, 3, 4]);
							}
							else if(($possibilitiesB == $groupB && $possibilitiesA != 0) || ($possibilitiesB == $groupB && min($possibilities) < 5))
							{
								$possibilities = array_diff($possibilities, [5, 6, 7, 8]);				
							}
							
							// if one of the remaining teams has one option
							// the team drawn cannot be placed in that group
							if(sizeof($check) == 1)
							{
								$possibilities = array_diff($possibilities, [reset($check)]);							
							}
						}
						
						// adding drawn team possibilities to occurrences array
						for($i = 0; $i < 8; $i++)
						{
							if(array_key_exists($i, $possibilities)) { $occurrences[$i]++; }
						}
						
						// counting how many teams can be placed in given group
						$group_counter = array_count_values($occurrences);
						
						// if one of the groups is an option for only one team 
						// and it is a drawn team, this will be their only option
						if (array_key_exists(1, $group_counter)) 
						{
							if($group_counter[1] == 1)
							{
								$key = array_search('1', $occurrences);	
								if (array_key_exists($key, $possibilities)) 
								{
									$possibilities = array($key => $key+1);
								}
							}
						}
								
						//shuffling array with possible groups
						shuffle($possibilities);
								
						echo "<form method = \"post\">";
						
						//displaying draw balls
						for($i = 0; $i < sizeof($possibilities); $i++)
						{
							echo "<button name=\"choice\" value=\"$possibilities[$i]\" onclick=\"showGroup($possibilities[$i])\"><img src=\"img/ball_group.png\"></button>";
						}
						
						echo "</form>";
						
						//saving user's choice
						if(isset($_POST["choice"])) { $choice = $_POST["choice"]; }
						
						//updating database 
						if($choice != 0)
						{
							$drawn = "UPDATE teams SET groups = $choice WHERE id = $team_drawn";
							$conn->query($drawn);
							header("Location: index.php");

						}
					}
					
					echo "</div>";
					
					// displaying available groups to draw for
					// teams drawn in pots 2, 3, 4.
					if($team_drawn != 0 && $pot > 1)
					{
						sort($possibilities);
						echo "<p style='text-align: center;'>Possible groups: ";
								
						foreach ($possibilities as $value) 
						{
							echo "<span class=\"available\">".$value."</span>";
						}
						echo "</p>";
					}
					
					// displaying a list of undrawn teams from the current pot
					if($pot < 5)
					{
						echo "<table id=\"group\" style=\"width: 50%; margin-bottom: 0;\">";
						echo "<tr><th>Pot $pot</th></tr>";				
						
						$pot_teams = mysqli_query($conn, "SELECT t.name, t.country_id, t.id tid, c.id, c.short country FROM teams t, country c 
						WHERE c.id = t.country_id AND pot = $pot AND groups = 0 ORDER BY t.name");
						while($pt = $pot_teams->fetch_assoc())
						{
							if($pt['tid'] == $team_drawn)
							{
								echo "<tr style='background-color: #75d0f3;'>";
							}
							else echo "<tr>";
							
							echo "<td>".$pt['name']." (".$pt['country'].")</td></tr>";				
						}
						echo "</table>";
					}
					
					//displaying drawn group
					echo "<p id=\"drawn\"></p>";
					if($team_drawn != 0 && $choice != 0) { sleep(1); }
					
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
					
					//reset button
					echo "<form method = \"post\">";
					echo "<button class=\"reset\" name=\"reset\">RESET</button>";
					echo "</form>";
	
					if(isset($_POST["reset"])) 
					{ 
						$reset = "UPDATE teams SET groups = 0";
						$conn->query($reset);
						header("Location: index.php");
					}
				?>
				
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
		<!-- 
			A javascript function that retrieves the number of drawn group 
			and displays to the user which group the team will be placed in   
		-->
		<script>
			function showGroup(x) 
			{
				document.getElementById("drawn").innerHTML = "Goes to group "+x;
			}
		</script>
	</body>
</html>