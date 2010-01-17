<div class="block">	
	<h3 class="red"><span>Login</span></h3>
	
	<?php if ($form->hasGlobalErrors()): ?>
  <ul class="form-global-errors error_list">
    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
    <li><?php echo ($name ? $name.': ' : '') . $error ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>

	<form action="<?php echo url_for('user/login') ?>" method="post" class="vertical-form" accept-charset="utf-8">	
		<div class="input_text block required">
			<?php echo $form['email']->renderLabel('Email') ?>
			<?php echo $form['email']->render() ?>
			<?php echo $form['email']->renderError() ?>
		</div>
		
		<hr class="clear" />
		
		<div class="input_text block required">
			<?php echo $form['password']->renderLabel('Password') ?>
			<?php echo $form['password']->render() ?>
			<?php echo $form['password']->renderError() ?>
		</div>

		<hr class="clear" />		
	
		<div class="input_submit">
			<input type="submit" name="login_submit" value="Submit" id="login_submit" />
		</div>
	
		<?php echo $form->renderHiddenFields() ?>		
	</form>	
	
</div>