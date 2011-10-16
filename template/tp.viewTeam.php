<?php
echo '<a href="'.$baseURL.'teams/">&larr; Back to teams list</a><br /><br />';
if( $success && ! $errors )
{
	echo '<p><span style="color:green;">'.$success.'</span></p>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>Some error<?php if( count($errors) > 1 ) { echo 's'; } ?> occured:</b>
<br />
<ul>
	<?php
	foreach($errors as $error)
	{
		echo '<li>'.$error.'</li>';
	}
	?>
</ul>
</div>
<?php
}
?>

<div style="float:left;width:900px;">
<table id="avatarWrapper">
<tr>
<td style="width:165px;">
<div id="avatar" onclick="toggleAvatarUpload();">
<div id="avatarImage"><img src="resources/avatar.jpg" style="width:117px;height:91px;"/></div>
</div>
</td>
<td>
<h2><?php echo $currentTeam['teamName']; ?></h2>
</td>
</tr>
</table>
</div>

<div class="subMenu">
<a <?php if( count($_GET) == 1 ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>"><img class="tableIcon" src="resources/house.png"/>Overview</a>
<a <?php if( $_GET['members'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&members=true"><img class="tableIcon" src="resources/group.png"/>Members</a>
<a <?php if( $_GET['matches'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&matches=true"><img class="tableIcon" src="resources/Trophy-Gold-icon-5.png"/>Matches</a>
<a <?php if( $_GET['ladderInfo'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&ladderInfo=true"><img class="tableIcon" src="resources/table.png"/>Ladder info</a>
<?php
if( $foundUser )
{
	echo '<a href="'.$baseURL.'viewTeam/?tID='.$_GET['tID'].'&leave=true"><img class="tableIcon" src="resources/group_delete.png"/>Leave team</a>';
}
else
{
	echo '<a href="'.$baseURL.'viewTeam/?tID='.$_GET['tID'].'&joinTeam=true"><img class="tableIcon" src="resources/group_add.png"/>Join team</a>';
}
if( $userLevel == $LevelAdmin )
{
	echo '<a href="'.$baseURL.'teamSettings/?tID='.$currentTeam['teamID'].'"><img class="tableIcon" src="resources/icons/advanced.png"/>Settings</a>';
}
?>
</div>
<br />
<br />
<?php
if( count($_GET) == 1 )
{
	echo '<br /><table>';
	echo '<tr><td>Team name</td><td>'.stripslashes($currentTeam['teamName']).'</td></tr>';
	echo '<tr><td>Tag</td><td>'.stripslashes($currentTeam['tag']).'</td></tr>';
	echo '<tr><td>IRC</td><td>'.stripslashes($currentTeam['ircChannel']).'</td></tr>';
	echo '<tr><td>Home page</td><td>'.stripslashes($currentTeam['homepage']).'</td></tr>';
	echo '<tr><td>Created on</td><td>'.$currentTeam['creationDate'].'</td></tr>';
	echo '</table>';
}
else if( $_GET['members'] && $members )
{
	if( $userLevel == $LevelAdmin )
	{
		$extra = "<th>Manage</th";
	}
	echo '<h3>Team members</h3><table><tr><th>Player name</th><th>Player level</th>'.$extra.'</tr>';
	$alternate = false;
	foreach($members as $member)
	{
		if( $alternate )
		{
			$extra = ' class="alternate"';
			$alternate = false;
		}
		else
		{
			$extra = '';
			$alternate = true;
		}
		$displayname = $member['nickname'];
		if( strlen($displayname) < 1 )
			$displayname = $member['username'];
			
		echo '<tr'.$extra.'><td><a href="'.$baseURL.'viewUser/?userID='.$member['userID'].'">'.$currentTeam['tag'].$displayname.'</a></td><td>'.levelToString($member['level']);
		if( $userLevel == $LevelAdmin && $member['level'] != 10 )
		{
			echo '&nbsp; &nbsp; <a href="'.$baseURL.'viewTeam/?tID='.$_GET['tID'].'&op='.$member['userID'].'">Make admin</a>';
		}
		else if( $userLevel == $LevelAdmin )
		{
			echo '&nbsp; &nbsp; <a href="'.$baseURL.'viewTeam/?tID='.$_GET['tID'].'&deop='.$member['userID'].'">Strip admin</a>';
		}
		echo '</td>';
		if( $userLevel == $LevelAdmin )
		{
			echo '<td>';
			if( $userSession->userID != $member['userID'] )
			{
				echo '<a href="viewTeam/?tID='.(int)$_GET['tID'].'&kick='.$member['userID'].'"><img class="tableIcon" src="resources/icons/delete.png"/></a>';
			}
			echo '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
	
	if( $membersHistory )
	{
		echo '<h3>Members history</h3>';
		echo '<table>';
		foreach($membersHistory as $member)
		{
			$displayName = $member['nickname'];
			if( strlen($displayName) < 1 )
				$displayName = $member['username'];
			
			echo '<tr><td><a href="'.$baseURL.'viewUser/?userID='.$member['userID'].'">'.$displayName.'</a></td><td>'.teamActionToString($member['action']).'</td><td>'.$member['date'].'</td></tr>';
		}
		echo '</table>';
	}

}
else if( $_GET['members'] )
{
	echo 'This team currently hasn\'t got any members.';
	
	if( $membersHistory )
	{
		echo '<h3>Members history</h3>';
		echo '<table>';
		foreach($membersHistory as $member)
		{
			$displayName = $member['nickname'];
			if( strlen($displayName) < 1 )
				$displayName = $member['username'];
			
			echo '<tr><td><a href="'.$baseURL.'viewUser/?userID='.$member['userID'].'">'.$displayName.'</a></td><td>'.teamActionToString($member['action']).'</td><td>'.$member['date'].'</td></tr>';
		}
		echo '</table>';
	}
}
else if( $_GET['matches'] && $matches )
{
	echo '<h3>Matches played by this team</h3>';
	echo '<table><tr><th>Opponent</th><th>Score</th><th>Points earned</th><th>Ladder</th></tr>';
	$oppoID = 0;
	foreach($matches as $match)
	{
		$oppoID = $match['opponentID'];
		if( $oppoID == $currentTeam['teamID'] )
			$oppoID = $match['challengerID'];
			
		$extra = '';
		
		if( $currentTeam['teamID'] == $match['challengerID'] )
			$score = $match['challengerScore'].'-'.$match['opponentScore'];
		else
			$score = $match['opponentScore'].'-'.$match['challengerScore'];
			
		if( $match['winnerID'] != $currentTeam['teamID'] )
		{
			$pointsEarned = '-'.$match['pointsEarned'];
			$extra = 'style="background-color:rgb(255,200,200);"';
		}
		else
		{
			$pointsEarned = '+'.$match['pointsEarned'];
			$extra = 'style="background-color:rgb(200,255,200);"';
		}
		
			
		$query = mysql_query("SELECT `teamName` FROM `teams` WHERE `teamID`=".(int)$oppoID);
		$row = mysql_fetch_array($query);
		if( strlen($row['teamName']) < 1 )
			$row['teamName'] = 'Unknown ( Team no longer exists )';
		echo '<tr '.$extra.'><td><a href="viewTeam/?tID='.$oppoID.'">'.$row['teamName'].'</a></td><td>'.$score.'</td><td>'.$pointsEarned.'</td><td>'.$match['ladderName'].'</td><td><a href="warInfo/?matchID='.$match['matchID'].'">Match link</a></td></tr>';
	}
	echo '</table>';
}
else if( $_GET['matches'] )
{
	echo '<b>This team has\'t played any matches yet</b>';
}
else if( $_GET['ladderInfo'] )
{
	if( $ladders )
	{
		echo '<table><tr><th>Ladder</th><th>Points</th><th>Wins</th><th>Losses</th></tr>';
		$alternate = false;
		foreach($ladders as $ladder)
		{
				
			if( $alternate )
			{
				$extra = 'class="alternate"';
				$alternate = false;
			}
			else
			{
				$alternate = true;
				$extra = '';
			}
			
			if( $ladder['inactive'] == 1 )
				$extra = 'class="cheater"';
			
			echo '<tr '.$extra.'><td><a href="viewLadder/?ladderID='.$ladder['ladderID'].'">'.$ladder['ladderName'].'</a></td><td>'.$ladder['points'].'</td><td style="width:20px">'.$ladder['wins'].'</td><td style="width:20px">'.$ladder['losses'].'</td></tr>';
		}
		echo '</table>';
		echo '<br />A red row means that the team is hibernating in that ladder.';
	}
	else
	{
		echo '<b>This team is currently not participating in any ladders</b>';
	}
}
else if( $_GET['joinTeam'] )
{
?>
<form method="post">
<div>
Join Password: <input type="password" value="" name="joinPassword" maxlength="50"/>
<input type="submit" name="joinTeam" value="Join"/>
</div>
</form>
<?php
}
else if( $_GET['leave'] )
{
?>
Are you sure you want to leave <?php echo $currentTeam['teamName']; ?>?
<br />
<form method="post">
<input type="submit" name="leaveTeam" value="Yes"/><input type="submit" name="cancelLeave" value="No"/>
</form>
<?php
}
else
{
	echo 'This tab does not exist.';
}
?>