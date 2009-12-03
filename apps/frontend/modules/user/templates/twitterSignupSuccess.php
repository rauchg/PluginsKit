<div class="block">	
	<h3 class="red"><span>Complete Twitter Signup</span></h3>	
	<div class="block">		
		<p>We need some other details from you so that we can complete the creation of your account.</p>
	</div>
	
	<?php if ($form->hasGlobalErrors()): ?>
  <ul class="form-global-errors error_list">
    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
    <li><?php echo $error ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
	
	<?php echo form_tag_for($form, 'user/twitterSignup', array('class' => 'vertical-form')); ?>		
		<div class="input_text block required">
			<?php echo $form['username']->renderLabel() ?>
			<?php echo $form['username']->render() ?>
			<?php echo $form['username']->renderError() ?>
		</div>
		
		<hr class="clear" />

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
