<div class="block">	
	<h3 class="red"><span>README.md / package.yml Syntax Checker</span></h3>	
	
	<div class="block">
		<p>Remember to read the <?php echo link_to('files templates', '@howtoadd') ?> instructions first.</p>
	</div>
	
	<form action="<?php echo url_for('plugin/syntaxChecker') ?>" method="post" accept-charset="utf-8" class="vertical-form" id="syntax-check-form">
		
		<hr class="clear" />		
		
		<div class="input_area block">
			<?php echo $form['readme']->renderLabel('README.md') ?>
			<?php echo $form['readme']->render(array('class' => 'required')) ?>
			<?php echo $form['readme']->renderError() ?>
		</div>
	
		<hr class="clear" />		
		
		<div class="input_area block">
			<?php echo $form['yaml']->renderLabel('package.yml') ?>
			<?php echo $form['yaml']->render(array('class' => 'required')) ?>
			<?php echo $form['yaml']->renderError() ?>
		</div>
	
		<hr class="clear" />
	
		<div class="input_submit">
			<input type="submit" name="twitter_details_submit" value="Submit" id="twitter_details_submit" />
		</div>
	
		<?php echo $form->renderHiddenFields() ?>

	</form>
	
</div>