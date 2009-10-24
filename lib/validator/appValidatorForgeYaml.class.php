<?php

class appValidatorForgeYaml extends sfValidatorBase
{
	
	protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('yaml_field', 'yaml');

    parent::configure($options, $messages);
  }
  
  protected function doClean($value)
  {
		$yamlparser = new ForgeYamlParser($value);	
		
		$errors = array();
		
		if (!$yamlparser->get('stable')){
			$errors[] = new sfValidatorError($this, 'No stable Git tag found');
		}
		
		if (!$yamlparser->get('stable')){
			$errors[] = new sfValidatorError($this, 'No stable Git tag found');
		}
		
		if (!$yamlparser->get('category') || TermPeer::retrieveCategoryByTitle($yamlparser->get('category'))){
			$errors[] = new sfValidatorError($this, 'No category provided, or category not found.');
		}
		
		if (!$yamlparser->get('tags')){
			$errors[] = new sfValidatorError($this, 'No tags provided.');
		}
			
		if (!empty($errors)){
			throw new sfValidatorErrorSchema($this, array($this->getOption('yaml_field') => $errors));
		}

    return $value;
  }

	
	function getMessage($code){
		return $code;
	}
	
}
