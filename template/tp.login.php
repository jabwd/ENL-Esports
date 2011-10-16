<?php

if( $success )
{
	echo '<span style="color:green;">Login success!</span>';
}

if( ! $userSession )
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

<form method="post">
<table>
<tr>
	<td>
		Username
	</td>
	<td>
		<input type="text" id="usernameField" name="username" value="<?php echo $_POST['username']; ?>" maxlength="40"/>
	</td>
</tr>
<tr>
	<td>
		Password
	</td>
	<td>
		<input type="password" name="password" value="" maxlength="50"/>
	</td>
</tr>

<tr>
	<td>
	Remember me
	</td>
	<td>
		<input type="checkbox" value="1" name="rememberMe"/>
	</td>
</tr>

<tr>
	<td>
	</td>
	<td>
		<input type="submit" name="login" value="Log in"/>
	</td>
</tr>

<tr class="alternate">
	<td>&nbsp; </td><td></td>
</tr>

<tr>
	<td></td><td><a href="iforgot.php" onclick="iforgot();return false;">I forgot my password</a></td>
</tr>
</table>
</form>
<br />
<div class="box" style="float:none;">
By logging in you note that you have read, agree and understand the ENL esports <a href="disclaimer/">disclaimer</a>
<br />
<h4><img src="resources/shield.png" class="tableIcon"/>Security tips</h4>
<p><img src="resources/lock.png" class="tableIcon"/>Never tell anyone your password</p>
<p><img src="resources/lock.png" class="tableIcon"/>Pick a nice long password, which is about 5-9 characters long</p>
<p><img src="resources/lock.png" class="tableIcon"/>Make sure your browser remembers the password ( anti-keylogger )</p>
</div>

<?php
}
else
{
	echo 'You are already logged in.';
}
?>