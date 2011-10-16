<?php
if( $messages )
{
	echo '<table><tr><th>Subject</th><th>From</th><th>Delete</th></tr>';
	foreach($messages as $message)
	{
		$displayName = $message['nickname'];
		if( strlen($displayName) < 1 )
			$displayName = $message['username'];
			
			
		echo '<tr><td><a href="'.$baseURL.'viewMessage/?mID='.$message['messageID'].'">';
		
		if( $message['read'] == 0 )
			echo '<img alt="" class="tableIcon" src="resources/icons/unread.png"/><b>'.$message['subject'].'</b></a></td><td>'.$displayName.'</td>';
		else
			echo '<img alt="" class="tableIcon" src="resources/icons/read.png"/>'.$message['subject'].'</a></td><td>'.$displayName.'</td>';
			
		echo '<td><a href="'.$baseURL.'messages/?delete='.$message['messageID'].'"><img class="tableIcon" src="resources/icons/message_delete.png" alt=""/>Delete</a></td></tr>';
	}
	echo '</table>';
}
else
{
	echo '<i>You do not have any messages at the moment, come back later</i>';
}
?>
<br />
<br />
<p>
<a href="<?php echo $baseURL; ?>createMessage/">Create a new message</a>
</p>