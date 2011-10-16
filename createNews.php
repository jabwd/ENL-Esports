<?php
require 'config.php';

checkLogin($LevelAdmin);

if( $_POST['addNews'] )
{
	$title = secureInput($_POST['title']);
	
	// HTML tags are allowed here, an admin is entering thec ontent
	$content = mysql_real_escape_string(nl2br($_POST['content']));
	
	if( strlen($content) > 6000 )
	{
		$errors[] = 'The content is too large!';
	}
	
	if( strlen($title) > 60 )
	{
		$errors[] = 'The title is too long!';
	}
	
	if( strlen($title) < 1 )
	{
		$errors[] = 'A title is required';
	}
	
	if( strlen($content) < 5 )
	{
		$errors[] = 'The content is not long enough';
	}
	
	if( ! $errors )
	{			
		$displayName = mysql_real_escape_string($userSession->displayName());
		if( mysql_query("INSERT INTO `news` (`title`,`content`,`author`) VALUES ('".$title."','".$content."','".$displayName."')") )
		{
			$success = 'Added your news item!';
		}
	}
}

require 'template/tp.head.php';
require 'template/tp.createNews.php';
require 'template/tp.foot.php';
?>