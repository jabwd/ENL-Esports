<a href="messages/">&larr; Back to your inbox</a>
<br />
<br />
<?php
$displayName = $message['nickname'];
if( strlen($message['nickname']) < 1 )
	$displayName = $message['username'];
	
echo '<b><i>From:</i></b> '.$displayName;
echo '<br /><b><i>Subject:</i></b> ' .$message['subject'];
echo '<br />';
echo '<p>';
echo $message['content'];
echo '</p>';
echo '<b><i>Sent on: '.$message['creationDate'].'</i></b>';
?>