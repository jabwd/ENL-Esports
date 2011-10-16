<?php
if( ! $errors && $success )
{
	echo '<span class="success">Your team has been created</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>The team was not registered because:</b>
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
if( $_GET['register'] )
{
?>

<form method="post">
<table>
<tr><td>Team name</td><td><input type="text" name="teamName" maxlength="60" size="60" value="<?php echo $_POST['teamName']; ?>"/></td></tr>
<tr><td>Tag</td><td><input type="text" name="teamTag" maxlength="9" size="9" value="<?php echo $_POST['teamTag']; ?>"/></td></tr>
<tr><td>IRC</td><td><input type="text" name="teamIRC" maxlength="20" size="20" value="<?php echo $_POST['teamIRC']; ?>"/></td></tr>
<tr><td>Join password</td><td><input type="text" name="joinPassword" value="" maxlength="60" size="60"/></td></tr>
<tr><td></td><td><input type="submit" name="registerTeam" value="Register team"/></td></tr>
</table>
</form>

<?php
}
else
{

if( $teams )
{
?>
<p>
<form method="post">
Search<input type="text" name="teamName" value=""/>
<input type="submit" name="search" value="Search"/>
</form>
</p>

<p>
<a href="teams/?register=true">Register a new team</a>
</p>
<br /><br />
<?php
	$alternate = false;
	$extra = "";
	echo "\n<table><tr><th>Tag</th><th>Team name</th></tr>";
	foreach($teams as $team)
	{
		if( $alternate )
		{
			$extra = 'class="alternate"';
			$alternate = false;
		}
		else
		{
			$extra = "";
			$alternate = true;
		}
		echo "\n\t".'<tr '.$extra.'><td style="width:100px;">'.$team['tag'].'</td><td><a href="viewTeam/?tID='.(int)$team['teamID'].'">'.$team['teamName'].'</a></td></tr>';
	}
	echo "\n</table>";
}
else
{
?>
<p>
<form method="post">
Search<input type="text" name="teamName" value="<?php echo $_POST['teamName']; ?>"/>
<input type="submit" name="search" value="Search"/>
</form>
</p>

<?php
	if( ! $_POST['search'] )
		echo 'There are currently no teams registered with this league.';
	else
		echo 'Your search query did not return any results';
}
}

if( $pagesCount > 0 )
{
	echo '<br />';
	echo '<h4>Page</h4>';
	$cPage++;
	if( $cPage > 1 )
		echo '<a href="'.$baseURL.'teams/?page='.($cPage-1).'">&larr; Previous</a> ';
	for($i = 0;$i<$pagesCount;$i++)
	{
		if( $cPage == ($i+1) )
		{
			echo '['.($i+1).']';
			continue;
		}
		echo ' <a href="'.$baseURL.'teams/?page='.($i+1).'">['.($i+1).']</a> ';
	}
	if( $cPage < ($pagesCount) )
		echo ' <a href="'.$baseURL.'teams/?page='.($cPage+1).'">&rarr; Next</a> ';
}
?>
