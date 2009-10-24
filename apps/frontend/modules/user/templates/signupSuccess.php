<div class="block">	
	<h3 class="red"><span>Signup</span></h3>	
	
	<?php if ($form->hasGlobalErrors()): ?>
  <ul class="form-global-errors error_list">
    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
    <li><?php echo $name.': '.$error ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
	
	<?php echo form_tag_for($form, 'user/signup', array('class' => 'vertical-form')); ?>		
		<div class="input_text block required">
			<?php echo $form['fullname']->renderLabel('Full name') ?>
			<?php echo $form['fullname']->render() ?>
			<?php echo $form['fullname']->renderError() ?>
		</div>
		
		<hr class="clear" />
		
		<div class="input_text block required">
			<?php echo $form['email']->renderLabel('Email') ?>
			<?php echo $form['email']->render() ?>
			<?php echo $form['email']->renderError() ?>
		</div>

		<hr class="clear" />
		
		<div class="input_text block required">
			<?php echo $form['username']->renderLabel('Username') ?>
			<?php echo $form['username']->render() ?>
			<?php echo $form['username']->renderError() ?>
		</div>
		
		<hr class="clear" />
		
		<div class="input_text inputs_password block required">
			<?php echo $form['password']->renderLabel('Password') ?>
			<?php echo $form['password']->render(array('autocomplete' => 'off')) ?>
			<?php echo $form['password']->renderError() ?>

			<div class="again">
				<?php echo $form['password_again']->renderLabel('Password again:') ?>
				<?php echo $form['password_again']->render() ?>
				<?php echo $form['password_again']->renderError() ?>
			</div>
		</div>
		
		<hr class="clear" />
		
		<div class="input_text block">
			<?php echo $form['location']->renderLabel('Location') ?>
			<?php echo $form['location']->render() ?>
			<?php echo $form['location']->renderError() ?>
		</div>
		
		<hr class="clear" />		
		
		<div class="input_text block">
			<?php echo $form['homepageurl']->renderLabel('Your Website') ?>
			<?php echo $form['homepageurl']->render() ?>
			<?php echo $form['homepageurl']->renderError() ?>
		</div>
		
		<hr class="clear" />		
		
		<div class="input_text block">
			<?php echo $form['twitter_id']->renderLabel('Twitter User') ?>
			http://twitter.com/<?php echo $form['twitter_id']->render() ?>/
			<?php echo $form['twitter_id']->renderError() ?>
		</div>
		
		<hr class="clear" />		
		
		<div class="input_textarea block">
			<?php echo $form['about']->renderLabel('About') ?>
			<?php echo $form['about']->render() ?>
			<?php echo $form['about']->renderError() ?>
		</div>
	
		<hr class="clear" />		
	
		<div class="input_submit">
			<input type="submit" name="twitter_details_submit" value="Submit" id="twitter_details_submit" />
		</div>
	
		<?php echo $form->renderHiddenFields() ?>		
	</form>	
</div>
