<div class="block">	
	<h3 class="red"><span>Edit your profile</span></h3>
	
	<?php if ($form->hasGlobalErrors()): ?>
  <ul class="form-global-errors error_list">
    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
    <li><?php echo $name.': '.$error ?></li>
    <?php endforeach; ?>
  </ul>
  <?php endif; ?>
	
	<form action="<?php echo url_for('@settings') ?>" method="post" class="vertical-form">
		<div class="input_text block">
			<?php echo $form['fullname']->renderLabel('Full name') ?>
			<?php echo $form['fullname']->render() ?>
			<?php echo $form['fullname']->renderError() ?>
		</div>
		
		<hr class="clear" />
		
		<div class="input_text block">
			<?php echo $form['email']->renderLabel('Email') ?>
			<?php echo $form['email']->render() ?>
			<?php echo $form['email']->renderError() ?>
		</div>
		<?php if (!$sf_user->hasConfirmedEmail()): ?>
		<p class="note note-warning">Email address not confirmed. <?php echo link_to('Send confirmation email',  'user/requestConfirm') ?></p>
		<?php endif ?>

		<?php if($sf_user->getPassword()): # only if change is needed ?>
		<hr class="clear" />

		<div class="input_text inputs_password block">
			<?php echo $form['password']->renderLabel('Password') ?>
			<?php echo $form['password']->render(array('autocomplete' => 'off')) ?>
			<?php echo $form['password']->renderError() ?>
			
			<div class="again">
				<?php echo $form['password_again']->renderLabel('Password again:') ?>
				<?php echo $form['password_again']->render() ?>
				<?php echo $form['password_again']->renderError() ?>
			</div>
		</div>
		<p class="note">Leave passwords empty if you want them to remain unchanged.</p>
		<?php endif; ?>
		
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
		
		<?php if ($sf_user->getPassword()): ?>
		<hr class="clear" />		
		
		<div class="input_text block">
			<?php echo $form['twitter_id']->renderLabel('Twitter') ?>
			http://twitter.com/<?php echo $form['twitter_id']->render() ?>/
			<?php echo $form['twitter_id']->renderError() ?>
		</div>
		<?php endif ?>
		
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