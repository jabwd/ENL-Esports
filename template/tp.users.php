<p>
Seeing something you don't like? These are your own settings. Edit them on your <a href="<?php echo $baseURL;?>profile/">profile</a>
<br />
A red table row is a banned <b>player</b>.
<br />
<br />
</p>

<br />
<div class="box">
	<h4>Filter the users list</h4>
	<form method="get" action="users.php">
	<input type="checkbox" name="cheatersOnly" value="1" <?php if( $_GET['cheatersOnly'] ) { echo 'checked="true"'; }?>/><label>Only show suspended accounts</label>
	<br />
	<input type="checkbox" name="clessOnly" value="1" <?php if( $_GET['clessOnly'] ) { echo 'checked="true"'; } ?>/><label>Only show cless players</label>
	<br />
	<input type="submit" name="filter" value="Filter"/>
	</form>
	
	<p>
	<form method="post">
	Search<input type="text" name="userName" value=""/>
	<input type="submit" name="search" value="Search"/>
	</form>
	</p>

</div>
<br />
<br />
<br />
<?php
if( $users && !$_GET['showIP'] )
{
	echo '<table><tr><th>UserID</th><th>Name</th><th>Preferred class</th><th>Preferred format</th></tr>';
	$alternate = false;
	$extra = "";
	foreach($users as $user)
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
		
		if( $user['suspended'] )
		{
			$extra = 'class="cheater"';
		}
		$displayName = $user['nickname'];
		if( strlen($displayName) < 1 )
			$displayName = $user['username'];
		echo '<tr '.$extra.'><td style="width:40px;">'.$user['userID'].'</td><td><a href="'.$baseURL.'viewUser/?userID='.$user['userID'].'" >'.$displayName.'</a></td><td>'.classToString($user['preferredClass']).'</td><td>'.formatToString($user['preferredFormat']).'</td></tr>';
	}// <img style="margin-top:5px;margin-right:6px;float:left;" src="resources/flags/'.$user['country'].'.png"/>
	echo '</table>';
}
else if( $_GET['showIP'] && $users )
{
	echo '<table><tr><th>UserID</th><th>Name</th><th>IP Address</th></tr>';
	$alternate = false;
	$extra = "";
	foreach($users as $user)
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
		$displayName = $user['nickname'];
		if( strlen($displayName) < 1 )
			$displayName = $user['username'];
		echo '<tr '.$extra.'><td style="width:40px;">'.$user['userID'].'</td><td><a href="'.$baseURL.'viewUser/?userID='.$user['userID'].'">'.$displayName.'</a></td><td>'.$user['IP'].'</td></tr>';
	}// <img style="margin-top:5px;margin-right:6px;float:left;" src="resources/flags/'.$user['country'].'.png"/>
	echo '</table>';
}
else
{
	echo 'Your search did not return any results';
}

if( $pagesCount > 0 )
{
	echo '<br />';
	echo '<h4>Page</h4>';
	$cPage++;
	if( $cPage > 1 )
		echo '<a href="'.$baseURL.'users/?page='.($cPage-1).'">&larr; Previous</a> ';
	for($i = 0;$i<$pagesCount;$i++)
	{
		if( $cPage == ($i+1) )
		{
			echo '['.($i+1).']';
			continue;
		}
		echo ' <a href="'.$baseURL.'users/?page='.($i+1).'">['.($i+1).']</a> ';
	}
	if( $cPage < ($pagesCount) )
		echo ' <a href="'.$baseURL.'users/?page='.($cPage+1).'">&rarr; Next</a> ';
}
?>