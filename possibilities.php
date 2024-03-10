<?php
	function CheckPossibilities($conn, $team_id)
		{	
			$team_pot = 0; $team_pair = 0;
		
			// obtaining data of the drawn team
			$team_data = mysqli_query($conn, "SELECT * FROM teams WHERE id = $team_id");
			while($t = $team_data->fetch_assoc())
			{
				$team_pot = $t['pot'];
				$team_pair = $t['pair'];
				$team_country = $t['country_id'];
				
			}

			// creating array with all possibilities
			$possibilities = array(1, 2, 3, 4, 5, 6, 7, 8);
				
			// Two teams from the same country are paired up. 
			// One team from the pair must be placed in groups 1-4 and the other in groups 5-8. 
			// If one team from a pair has already been placed in one group, 
			// the four groups will be removed from the array
			$check_pair = mysqli_query($conn, "SELECT id, pair, groups FROM teams WHERE (pair != 0 AND pair = $team_pair) AND groups != 0");
			while($p = $check_pair->fetch_assoc())
			{			
				if($p['groups'] < 5)
				{
					$possibilities = array_diff($possibilities, [1, 2, 3, 4]);
				}
				else if($p['groups'] > 4)
				{
					$possibilities = array_diff($possibilities, [5, 6, 7, 8]);	
				}
			}
			
			// Teams from the same country could not be drawn into the same group.
			// If a team from the same country as the drawn team is already assigned to one of the groups, 
			// this group will be removed from the array of possible groups
			$check_groups = mysqli_query($conn, "SELECT pot, groups, country_id FROM teams WHERE groups != 0");
			while($g = $check_groups->fetch_assoc())
			{
				if($g['pot'] == $team_pot || $g['country_id'] == $team_country)
				{
					$possibilities = array_diff($possibilities, [$g['groups']]);
				}
			}
			
			return $possibilities;
		};
?>