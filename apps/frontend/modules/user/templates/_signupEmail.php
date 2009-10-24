<html>
	<body style="font-family: Helvetica; color: #313334; background: #f9f9f9;">
		<h1 style="font-size: 16px; color: rgb(120, 186, 145)">Welcome to MooTools plugins!</h1>
		
		<p>Hey <?php echo $name ?></p>		
		<p>First, thanks for joining our development community. We look forward to your contributions.<br />This is your login information:</p>
		
		<table>
			<thead>
				<tr>
					<th>Email</th>
					<th>Password</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $email ?></td>
					<td><?php echo $password ?></td>
				</tr>
			</tbody>
		</table>
		
		<hr />
		
		<p>Please take a moment to confirm your email at:</p>
		<p><?php echo link_to(url_for('user/confirmEmail?hash=' . $hash, array('absolute' => true)), 'user/confirmEmail?hash=' . $hash, array('absolute' => true)) ?></p>
		
		<hr />
		
		<p>If you have any problems with your account, or the link above doesn't work, please let us know by emailing info@mootools.net</p>
		<p>Thanks,<br />The MooTools Team</p>		
	</body>
</html>