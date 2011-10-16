<a onclick="loadPage('users.php?ajax=true','users');return false;" href="users/">&larr; Back to the users list</a>

<?php
if( $selectedUser )
{
	if( $userBans = $selectedUser->isBanned() )
	{
		echo '<div class="errorDisplay"><b>This user\'s account is currently suspended on ENL</b><br /><ul>';
		foreach($userBans as $singleBan)
		{
			echo '<li>'.$singleBan['reason'].' - expires on '.$singleBan['expirationDate'].'</li>';
		}
		echo '</ul></div>';
	}
	
	?>
	<table id="avatarWrapper">
	<tr>
	<td style="width:165px;">
	<div id="avatar" onclick="toggleAvatarUpload();">
	<div id="avatarImage"><img src="<?php echo $selectedUser->avatar; ?>" style="width:117px;height:91px;"/></div>
	</div>
	</td>
	<td>
	<h2><?php echo $selectedUser->displayName(); ?>'s Profile</h2>
	</td>
	</tr>
	</table>
	<?php
		
	//echo '<h3>Profile of: '.$selectedUser->displayName().'</h3>';
	echo '<table>';
	echo '<tr><td>Registered on</td><td>'.$selectedUser->creationDate.'</td></tr>';
	echo '<tr><td>User ID</td><td>'.$selectedUser->userID.'</td></tr>';
	echo '<tr><td>Username</td><td>'.$selectedUser->username.'</td></tr>';
	echo '<tr><td>Nickname</td><td>'.$selectedUser->nickname.'</td></tr>';
	
	echo '<tr class="alternate"><td>&nbsp; </td><td></td></tr>';
	
	echo '<tr><td>Xfire</td><td><a href="xfire:add_friend?user='.$selectedUser->xfire.'">'.$selectedUser->xfire.'</a></td></tr>';
	echo '<tr><td>Homepage</td><td><a href="'.$selectedUser->homepage.'">'.$selectedUser->homepage.'</a></td></tr>';
	echo '<tr><td>Preferred class</td><td>'.classToString($selectedUser->preferredClass).'</td></tr>';
	echo '<tr><td>Preferred format</td><td>'.formatToString($selectedUser->preferredFormat).'</td></tr>';
	echo '<tr><td>Profile visits</td><td>'.$selectedUser->profileVisits.'</td></tr>';
	
	echo '<tr class="alternate"><td>&nbsp; </td><td></td></tr>';
	
	echo '<tr><td>TZ Anticheat ID</td><td>'.$selectedUser->slacID.'&nbsp; &nbsp; <a href="http://tz-ac.com/profile.php?id='.$selectedUser->slacID.'">View on tz-ac.com</a></td></tr>';
	echo '<tr><td>TZAC Status</td><td><span id="tzacStatus"><img src="resources/loader3.gif"/> Loading…</span></td></tr>';
	/*echo '<tr><td>Cheater</td><td>';
	if( $selectedUser->isBanned() )
	{
		echo '<span style="color:red;">Yes</span>';
	}
	else
	{
		echo '<span class="success">No, clean</span>';
	}*/
	echo '</td></tr>';
	echo '</table>';
}
else
{
	echo '<b><i>The user you selected does not exist</i></b>';
}

if( $userSession->adminLevel == $LevelAdmin && $ipAddresses )
{
	echo '<h4>User\'s IP Addresses</h4>';
	echo '<table><tr><th>IP</th><th>Date</th></tr>';
	foreach($ipAddresses as $ip)
	{
		echo '<tr><td>'.$ip['IP'].'</td><td>'.$ip['date'].'</td></tr>';
	}
	echo '</table>';
}

if( $teams )
{
	echo '<h3>Teams</h3><table><tr><th>Team name</th></tr>';
	foreach($teams as $team)
	{
		echo '<tr><td><a href="'.$baseURL.'viewTeam/?tID='.$team['teamID'].'">'.$team['teamName'].'</a></td></tr>';
	}
	echo '</table>';
}
else
{
	echo '<br /><b>'.$selectedUser->displayName.' is currently not in any team on ENL</b>';
}
?>