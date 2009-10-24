<?php

class appValidatorForgeMD extends sfValidatorBase
{
	
	protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('readme_field', 'readme');

    parent::configure($options, $messages);
  }
  
	// instead of ugly html here, it should be a ErrorSchema
  protected function doClean($value)
  {
		$mdparser = new ForgeMDParser($value);	
		
		$errors = array();
		
		if (!$mdparser->getTitle()){
			$errors[] = new sfValidatorError($this, 'No title found');
		}
		
		if (!$mdparser->getSection('how-to-use')){
			$errors[] = new sfValidatorError($this, 'No "How to Use" section found.');
		}
		
		if (!$mdparser->getSection('description')){
			$errors[] = new sfValidatorError($this, 'No description found.');
		}
			
		if (!empty($errors)){
			throw new sfValidatorErrorSchema($this, array($this->getOption('readme_field') => $errors));
		}		

    return $value;
  }
	
	function getMessage($code){
		return $code;
	}
	
}
