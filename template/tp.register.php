<?php

if( $success )
{
	echo '<span class="success">Registration success! You can now login <a href="login/">here</a></span>';
}
else
{


if( $errors )
{
?>
<br />
<div class="errorDisplay">
<b>The following errors occured:</b>
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

<p>
<span style="color:rgb(120,120,120);">
<b>Tip:</b> Don't use capitals in your username, it makes remembering it harder. Once you forget your username there is nothing you can do to recover your account.
<br />
<b>Tip:</b> When entering your slac ID enter your number, and then append as many 0's on the start as the field allows, then you are sure your ID is correct</b>
</span>
</p>

<form method="post">
<table>
<tr>
	<td>Username</td>
	<td><input class="text" type="text" name="username" value="<?php echo $_POST['username']; ?>" maxlength="40"/></td>
</tr>
<tr>
	<td>Password</td>
	<td><input class="text" type="password" name="password" value="" maxlength="50"/></td>
</tr>
<tr>
	<td>Password repeat</td>
	<td><input class="text" type="password" name="password2" value="" maxlength="50"/></td>
</tr>
<tr>
	<td>TZ Anticheat ID ( required )</td>
	<td><input class="text" type="text" name="slacID" value="<?php echo $_POST['slacID']; ?>" maxlength="8"/></td>
</tr>


<tr>
	<td>Email ( optional, recommended! )</td>
	<td><input class="text" type="text" name="email" value="<?php echo $_POST['email']; ?>" maxlength="100"/></td>
</tr>

<tr>
	<td>Nickname ( optional )</td>
	<td><input class="text" type="text" name="nickname" value="<?php echo $_POST['nickname']; ?>" maxlength="20"/></td>
</tr>


<tr>
	<td>
	</td>
	<td>
	<input type="submit" name="register" value="Register"/>
	</td>
</tr>
</table>

</form>

<div class="box">
By registering you note that you have read, agree and understand the ENL esports <a href="disclaimer/">disclaimer</a>
</div>
<?php

}
?>