
<?php
if( ! $errors && $success )
{
	echo '<span class="success">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>Your challenge wasn't created because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
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
else
{
?>

<h3>Select the team you want to challenge <?php echo $opponentTeam['teamName']; ?> with</h3>
<form method="post">
Team:<select name="yourTeam">
				<?php
				if( $yourTeams )
				{
					foreach($yourTeams as $yourTeam)
					{
						echo '<option value="'.$yourTeam['teamID'].'">'.$yourTeam['teamName'].'</option>';
					}
				}
				?>
			</select>
<input type="hidden" name="opponentID" value="<?php echo $_GET['tID']; ?>"/>
<input type="hidden" name="ladderID"	value="<?php echo $_GET['ladderID']; ?>"/>
<input type="submit" name="challenge" value="Challenge"/>
</form>
<?php
}
?>