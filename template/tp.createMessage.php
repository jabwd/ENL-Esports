<a href="messages/">&larr; Back to your inbox</a>
<br />
<br />
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
<b>The message was not sent because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
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


<form method="post">
To ( Username ):<input type="text" name="username" value="<?php echo $_POST['username']; ?>" maxlength="40" size="41"/>
<br />
Subject:<input type="text" name="subject" value="<?php echo $_POST['subject']; ?>" maxlength="40" size="41"/>
<br />
<textarea cols="70" rows="20" name="content"><?php echo $_POST['content']; ?></textarea>
<br />
<input type="submit" name="sendMessage" value="Send"/>
</form>