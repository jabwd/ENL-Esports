<?php
if( $success && ! $errors )
{
	echo '<span class="success">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>The following error<?php if( count($errors) > 0 ) { echo 's'; } ?> occured:</b>
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

<div class="subMenu">
	<a href="createNews/">Write a news item</a>
</div>
<br />
<br />
<br />
<table>
<tr><td>Suspending a user's account</td><td></td></tr>
<form method="post">
<tr><td>UserID</td><td><input type="text" name="username" value="<?php echo $_POST['username']; ?>" maxlength="40"/></td></tr>
<tr><td>Reason</td><td><input type="text" name="reason" value="<?php echo $_POST['reason']; ?>" maxlength="255"/></td></tr>
<tr><td></td><td><input type="submit" name="banUser" value="Ban"/></td></tr>
</form>
</table>
<br />
<br />
<h3>IP Bans on ENL</h3>
Create a new ban:<br />
<form method="post">
IP Address:<input type="text" name="IPAddress" value="" maxlength="15"/><input type="submit" name="ipBan" value="Ban IP"/>
</form>
<br />
<br />
<?php
if( $ipBans )
{
	echo '<h4>Current IP bans</h4><table style="width:300px;"><tr><th>IP Address</th><th></th></tr>';
	foreach($ipBans as $ipBan)
	{
		echo '<tr><td>'.$ipBan['IP'].'</td><td><a href="adminControl/?deleteIPBan='.$ipBan['entryID'].'"><img class="tableIcon" src="resources/icons/delete.png"/></a></td></tr>';
	}
	echo '</table>';
}
else
	echo '<i>Currently there are no IP addresses banned on ENL</i>';
?>