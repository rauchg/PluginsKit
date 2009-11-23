<div class="block">	
	<h3 class="red"><span>Add a plugin</span></h3>	
	
	<div class="block">
		<p>Remember to read <?php echo link_to('How to Add a Plugin', '@howtoadd') ?> first.</p>
	</div>
	
	<form action="<?php echo url_for('plugin/add') ?>" method="post" accept-charset="utf-8" class="vertical-form" id="add-plugin-form">
		
		<?php if ($form->hasGlobalErrors()): ?>
	  <ul class="form-global-errors error_list">
	    <?php foreach ($form->getGlobalErrors() as $name => $error): ?>
	    <li><?php echo $name.': '.$error ?></li>
	    <?php endforeach; ?>
	  </ul>
	  <?php endif; ?>
		
		<hr class="clear" />		
		
		<div class="input_text block required">
			<?php echo $form['url']->renderLabel('GitHub repository URL') ?>
			<?php echo $form['url']->render(array('class' => 'required', 'autocomplete' => 'off')) ?>
			<?php echo $form['url']->renderError() ?>
		</div>
	
		<hr class="clear" />		
	
		<div class="input_submit">
			<input type="submit" name="plugin_add_submit" value="Submit" id="plugin_add_submit" />
		</div>
	
		<?php echo $form->renderHiddenFields() ?>

	</form>
	
</div>