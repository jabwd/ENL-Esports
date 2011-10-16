<a href="adminControl/">&larr; Back to the admin panel</a>
<br />
<br />
<?php
if( ! $errors && $success )
{
	echo '<span style="color:rgb(100,255,100);">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>The news item has not been added because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
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
Title:<input type="text" name="title" value="" maxlength="60" size="60"/>
<br />
<textarea cols="70" rows="20" name="content"></textarea>
<br />
<input type="submit" name="addNews" value="Post news"/>
</form>