<a href="viewTeam/?tID=<?php echo $_GET['tID']; ?>">&larr; Back to team overview</a>
<br />
<br />
<?php
if( $success && ! $errors )
{
	echo '<p><span class="success">'.$success.'</span><br /></p>';
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
<h2><?php echo $team['teamName']; ?></h2>
</td>
</tr>
</table>
</div>

<div class="subMenu">
<a href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>"><img class="tableIcon" src="resources/house.png"/>Overview</a>
<a href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&members=true"><img class="tableIcon" src="resources/group.png"/>Members</a>
<a href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&matches=true"><img class="tableIcon" src="resources/Trophy-Gold-icon-5.png"/>Matches</a>
<a <?php if( $_GET['ladderInfo'] ) echo 'class="selected"'; ?> href="<?php echo $baseURL; ?>viewTeam/?tID=<?php echo $_GET['tID']; ?>&ladderInfo=true"><img class="tableIcon" src="resources/table.png"/>Ladder info</a>
<?php
echo '<a href="'.$baseURL.'viewTeam/?tID='.$_GET['tID'].'&leave=true"><img class="tableIcon" src="resources/group_delete.png"/>Leave team</a>';
echo '<a class="selected" href="'.$baseURL.'teamSettings/?tID='.$_GET['tID'].'"><img class="tableIcon" src="resources/icons/advanced.png"/>Settings</a>';
?>
</div>
<br />
<br />
<?php
if( ! $_POST['deleteTeam'] )
{
?>
<form method="post">
<table>
	<tr><td>Team name</td><td><input type="text" class="text" name="teamName" value="<?php echo $team['teamName']; ?>" maxlength="60"/></td></tr>
	<tr><td>Tag</td><td><input type="text" name="teamTag" value="<?php echo $team['tag']; ?>" maxlength="9" size="9"/></td></tr>
	<tr><td>IRC</td><td><input type="text" name="teamIRC" value="<?php echo $team['ircChannel']; ?>" maxlength="20" size="20"/></td></tr>
	<tr><td>Home page</td><td><input type="text" name="homepage" value="<?php echo $team['homepage']; ?>" maxlength="100" size="60"/></td></tr>
	<tr><td>New join password</td><td><input type="text" class="text" name="password" value=""/></td></tr>
	<tr><td></td><td><input type="submit" name="save" value="Save settings"/></td></tr>
	
	
	<tr><td>&nbsp; </td><td></td></tr>
	
	
	
	<tr>
		<td>Ladder options</td>
		
		<td>
			<?php
			if( $ladders )
			{
				echo '<select id="ladderSelect" name="ladderSelect" onclick="getLadderInfo('.$_GET['tID'].');">';
				echo '<option value="0">Select ladder</option>';
				foreach($ladders as $ladder)
				{
					
					echo '<option value="'.$ladder['ladderID'].'">'.$ladder['ladderName'].'</option>';
				}
				echo '</select>';
			}
			else
			{
				echo '<b><i>No ladders</i></b>';
			}
			?>
		</td>
	</tr>
	
	<tr id="toggleLadderActivityTR" style="display:none;">
		<td>Allow challenges</td>
		<td><input type="submit" name="toggleLadderActivity" value="Go inactive" id="toggleLadderActivity"/></td>
	</tr>
	
	
	
	<tr><td>&nbsp;</td><td></td></tr>
	<tr><td>&nbsp;</td><td></td></tr>
	<tr><td>&nbsp;</td><td></td></tr>
	
	
	
	<tr><td>Delete <?php echo $team['teamName']; ?></td><td><input type="submit" name="deleteTeam" value="Delete team"/></td></tr>
</table>
</form>
<?php 
}
else
{
	echo 'Are you sure you want to delete the team '.$team['teamName'].'? <form method="post"><input type="submit" name="deleteTeamConfirm" value="Yes"/><input type="submit" name="nothing" value="No"/></form>';
}
?>