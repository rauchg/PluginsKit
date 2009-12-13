<?php

class BrowseDevelopersForm extends sfForm
{
	protected static $active_choices = array('', '5 days', '10 days', '15 days', 'last month', 'last 2 months');
	
	public function __construct($defaults = array(), $options = array())		
	{
		// disable CSRF
    return parent::__construct($defaults, $options, false);
	}
	
	public function configure(){
				
		$this->setWidgets(array(
			'search' => new sfWidgetFormInput(array()),
			'with_plugins' => new sfWidgetFormInputCheckbox
		));
		
		$this->setValidators(array(
			'search' => new sfValidatorString(array('required' => false, 'max_length' => 50, 'min_length' => 2)),
			'with_plugins' => new sfValidatorPass
		));
		
	}
	
}