<html>
	<body style="font-family: Helvetica; color: #313334; background: #f9f9f9;">
		<h1 style="font-size: 16px; color: rgb(120, 186, 145)">Retrieve your password</h1>
		
		<p>Hey <?php echo $name ?></p>
				
		<p>To set a new password, which we're sure you won't forget, follow this link:</p>
		<p><?php echo link_to(url_for('user/loginForgot?hash=' . $hash, array('absolute' => true)), 'user/loginForgot?hash=' . $hash, array('absolute' => true)) ?></p>
		
		<hr />
		
		<p>If you have any problems with your account, or the link above doesn't work, please let us know by emailing info@mootools.net</p>
		<p>Thanks,<br />The MooTools Team</p>		
	</body>
</html>