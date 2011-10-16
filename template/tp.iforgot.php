<?php 
if( $_GET['key'] )
{
	if( $success )
	{
		echo 'An email has been sent to '.$row['email'].' with your new password. Don\'t forget to check your spam box!';
	}
	else
	{
		echo 'Something went wrong, entered the URL correctly ?';
	}
}
else if( strlen($_GET['username']) < 1 )
{
	echo '<form method="get">Username:<input type="text" name="username" value=""/><input type="submit" name="go" value="Reset"/></form>';
}
else if( $error )
{
	echo $error.'<br /><br />';
	echo '<div style="margin:0px auto;width:500px;"><img src="resources/cat-fail.jpg"/></div>';
}
else
{
	echo '<b>An email was sent to '.$email.'</b><br />Do not forget to check your spam folder!<br /><br />';
	echo '<div style="margin:0px auto;width:500px;"><img src="resources/cat-fail.jpg"/></div>';
}
?>