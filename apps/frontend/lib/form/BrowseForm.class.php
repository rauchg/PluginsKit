<?php

class BrowseForm extends sfForm
{
	protected static $active_choices = array('', '5 days', '10 days', '15 days', 'last month', 'last 2 months');
	
	public function __construct($defaults = array(), $options = array())		
	{
		// disable CSRF
    return parent::__construct($defaults, $options, false);
	}
	
	public function configure(){
				
		$this->setWidgets(array(
			'tag' => new sfWidgetFormInputHidden(),
			'search' => new sfWidgetFormInput(),
			'active' => new sfWidgetFormChoice(array(
			  'choices' => array_combine(self::$active_choices, self::$active_choices)
			)),
			'official' => new sfWidgetFormInputCheckbox(),
			'category' => new sfWidgetFormPropelChoice(array(
			  'model'     => 'Term',
			  'add_empty' => true,
				'peer_method' => 'retrieveCategories',
				'key_method' => 'getSlug'
			))
		));
		
		$this->setValidators(array(
			'tag' => new sfValidatorPass(),
			'search' => new sfValidatorString(array('required' => false, 'max_length' => 50, 'min_length' => 2)),
			'active' => new sfValidatorChoice(array('required' => false, 'choices' => self::$active_choices)),
			'official' => new sfValidatorPass(),
			'category' => new sfValidatorPass()
		));
		
	}
	
}