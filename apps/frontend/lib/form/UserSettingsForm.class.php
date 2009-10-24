<?php

class UserSettingsForm extends AuthorForm
{
	
	public function configure(){
		parent::configure();
		
		unset($this->widgetSchema['id'], $this['admin'], $this['username']);
		
		$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
		$this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
		
		$this->validatorSchema['password'] = new sfValidatorString(array('min_length' => 5, 'required' => false));
		$this->validatorSchema['password_again'] = new sfValidatorString(array('required' => false));			
		$this->validatorSchema->setPostValidator(new sfValidatorSchemaCompare('password', '==', 'password_again'));		
		
		# not allow twitter_id / password changing for twitter users
		if (!sfContext::getInstance()->getUser()->getPassword()) unset($this['twitter_id'], $this['password']);
		
		$this->widgetSchema->setNameFormat('settings[%s]');
	}
	
	public function updateObject($values = null){
		$object = parent::updateObject($values);
		if ($this->getValue('password'))
			$object->setPasswordPlain($this->getValue('password'));
 		return $object;		
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null){
		parent::bind(array_merge($taintedValues, array('id' => sfContext::getInstance()->getUser()->getId())));
	}
	
}