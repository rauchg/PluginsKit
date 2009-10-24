<html>
	<body style="font-family: Helvetica; color: #313334; background: #f9f9f9;">
		<h1 style="font-size: 16px; color: rgb(120, 186, 145)">Confirm your MooTools Plugin Email</h1>
		
		<p>Hey <?php echo $name ?></p>		
		<p>We need to confirm you own this email address.</p>
		
		<p>Please click the following URL to do so:<br /><?php echo link_to(url_for('user/confirmEmail?hash=' . $hash, array('absolute' => true)), 'user/confirmEmail?hash=' . $hash, array('absolute' => true)) ?></p>
		
		<hr />
		
		<p>If you have any problems with your account, or the link above doesn't work, please let us know by emailing info@mootools.net</p>
		<p>Thanks,<br />The MooTools Team</p>		
	</body>
</html>