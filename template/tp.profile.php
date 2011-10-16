<?php
if( $userBans = $userSession->isBanned() )
{
	echo '<div class="errorDisplay"><b>Your account is currently suspended on ENL</b><br /><ul>';
	foreach($userBans as $singleBan)
	{
		echo '<li>'.$singleBan['reason'].' - expires on '.$singleBan['expirationDate'].'</li>';
	}
	echo '</ul></div>';
}

if( ! $errors && $success )
{
	echo '<span class="success">'.$success.'</span>';
}
else if( $errors )
{
	?>
<br />
<div class="errorDisplay">
<b>Your settings were not saved because of the following reason<?php if( count($errors) > 0 ) { echo 's'; } ?>:</b>
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

<table id="avatarWrapper">
<tr>
<td style="width:165px;">
<div id="avatar" onclick="toggleAvatarUpload();">
<div id="avatarImage"><img src="<?php echo $userSession->avatar; ?>" style="width:117px;height:91px;"/></div>
</div>
</td>
<td>
<h2><?php echo $userSession->displayName(); ?>'s Profile ( This is you )</h2>
</td>
</tr>
</table>
<span style="font-size:10pt;">Click on your avatar to upload a new one.</span>

<div class="box" style="display:none;">
Upload a new avatar image.
<br />
This supports: bmp, png, gif, jpeg, pict, tiff
<br />
<br />
<form method="post"
enctype="multipart/form-data">
<label for="file">Avatar image:</label>
<input type="file" name="file" id="file" /> 
<br />
<input type="submit" name="upload" value="upload" />
</form>
</div>
<br />
<br />
<br />
<form method="post">
<table>
	<tr>
		<td>Username</td><td><?php echo $userSession->username; ?></td>
	</tr>

	<tr>
		<td>Nickname</td><td><input type="text" name="nickname" value="<?php echo $userSession->nickname; ?>" maxlength="20"/></td>
	</tr>
	
	<tr>
		<td>TZAC ID</td><td><?php echo $userSession->slacID; ?></td>
	</tr>
	
	<tr>
		<td>TZAC Status</td><td><span id="tzacStatus"><img src="resources/loader3.gif"/> Loading…</span></td>
	</tr>
	
	
	<tr class="alternate">
		<td>&nbsp;</td><td></td>
	</tr>
	
	<!--<tr>
		<td>Security question</td><td><input type="text" name="securityQuestion" value=""/></td>
	</tr>
	
	<tr>
		<td>Answer</td><td><input type="text" name="securityAnswer" value=""/></td>
	</tr>-->
	
	<tr>
		<td>E-mail ( Private )</td><td><input type="text" name="email" value="<?php echo $userSession->email; ?>" maxlength="100"/></td>
	</tr>
	
	
	<tr class="alternate">
		<td>&nbsp; </td><td></td>
	</tr>
	
	<tr>
		<td>Xfire ( Public! )</td><td><input type="text" name="xfire" value="<?php echo $userSession->xfire; ?>" maxlength="40"/></td>
	</tr>
	
	<tr>
		<td>Homepage ( Public! )</td><td><input type="text" name="homepage" value="<?php echo $userSession->homepage; ?>" maxlength="100"/></td>
	</tr>
	
	<tr>
		<td>Preferred class</td>
		<td>
			<select name="class">
				<option value="1" <?php if( $userSession->preferredClass == 1 ) { echo 'selected="true"'; } ?>>
					Soldier
				</option>
				<option value="2" <?php if( $userSession->preferredClass == 2 ) { echo 'selected="true"'; } ?>>
					Injecting medic
				</option>
				<option value="3" <?php if( $userSession->preferredClass == 3 ) { echo 'selected="true"'; } ?>>
					Medic
				</option>
				<option value="4" <?php if( $userSession->preferredClass == 4 ) { echo 'selected="true"'; } ?>>
					Ninja engineer
				</option>
				<option value="5" <?php if( $userSession->preferredClass == 5 ) { echo 'selected="true"'; } ?>>
					Rifle engineer
				</option>
				<option value="6" <?php if( $userSession->preferredClass == 6 || $userSession->preferredClass == 7 ) { echo 'selected="true"'; } ?>>
					Fieldops
				</option>
				<option value="8" <?php if( $userSession->preferredClass == 8 ) { echo 'selected="true"'; } ?>>
					Covertops
				</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Preferred match format</td>
		<td>
			<select name="matchFormat">
				<option value="1" <?php if( $userSession->preferredFormat == 1 ) { echo 'selected="true"'; } ?>>
					1on1
				</option>
				<option value="2" <?php if( $userSession->preferredFormat == 2 ) { echo 'selected="true"'; } ?>>
					2on2
				</option>
				<option value="3" <?php if( $userSession->preferredFormat == 3 ) { echo 'selected="true"'; } ?>>
					3on3
				</option>
				<option value="6" <?php if( $userSession->preferredFormat == 6 ) { echo 'selected="true"'; } ?>>
					6on6
				</option>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Searching for new team ( cless )</td><td><input type="checkbox" name="cless" value="1" <?php if( $userSession->cless ) { echo 'checked="true"'; } ?>/></td>
	</tr>
	
	<tr class="alternate">
		<td>&nbsp; </td>
		<td></td>
	</tr>
	
	<tr>
		<td>Password</td><td><input type="password" name="password" value="" maxlength="50"/></td>
	</tr>
	
	<tr>
		<td>Repeat password</td><td><input type="password" name="password2" value="" maxlength="50"/></td>
	</tr>
	
	<tr class="alternate">
		<td>&nbsp; </td><td></td>
	</tr>
	
	<tr>
		<td></td><td><input type="submit" name="save" value="Save settings"/></td>
	</tr>
	
	<tr>
		<td>&nbsp; </td><td></td>
	</tr>
	
	<tr>
		<td>&nbsp; </td><td></td>
	</tr>
	
	<tr>
		<td></td><td><a href="logout/">Logout &rarr;</a></td>
	</tr>
</table>
</form>
<br />
<!--The security question is used whenever you forget your password. By answering the security question correctly<br />
you will be able to reset your password.-->

Your e-mail address is not required for anything on ENL. The only reason for you to enter it is so you can recover your password whenever you forgot it.