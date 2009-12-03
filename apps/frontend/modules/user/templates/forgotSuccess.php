<div class="block">
	<h3 class="green"><span>Forgot your password?</span></h3>
	
	<div class="block">
		<p>No problem, happens to everyone.</p>
	</div>
	
	<?php if ($form->hasGlobalErrors()): ?>
  <ul class="form-global-errors error_list">
    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
    <li><?php echo $error ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
	
	<form action="<?php echo url_for('user/forgot') ?>" method="post" accept-charset="utf-8" class="vertical-form">

		<div class="input_text block required">
			<?php echo $form['email']->renderLabel('Email') ?>
			<?php echo $form['email']->render() ?>
			<?php echo $form['email']->renderError() ?>
		</div>
		
		<hr class="clear" />

		<div class="input_submit">
			<?php echo $form->renderHiddenFields() ?>
			<input type="submit" name="twitter_details_submit" value="Submit" id="twitter_details_submit" />
		</div>
		
	</form>
	
</div>