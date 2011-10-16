<h3>Create a new tournament</h3>

<form method="post">
Tournament name:<input type="text" name="tournamentName" value="<?php echo $_POST['tournamentName']; ?>" maxlength="60" size="60"/>
<br />
<br />
Rules ( you can leave this empty ):
<br />
<textarea name="tournamentRules" cols="80" rows="5"><?php echo $_POST['tournamentRules']; ?></textarea>
<br />
<br />
<input type="submit" name="create" value="Create"/>
</form>