<?php

class UserSettingsForm extends AuthorForm
{
	
	public function configure(){
		parent::configure();
		
		unset($this->widgetSchema['id'], $this['admin'], $this['username'], $this['password']);
		
		// only allow password change for non-twitter users (ie: those that already have a password)
		
		if (sfContext::getInstance()->getUser()->getPassword()){
		  $this->widgetSchema['password_change'] = new sfWidgetFormInputPassword();
  		$this->validatorSchema['password_change'] = new sfValidatorString(array('min_length' => 5, 'required' => false));
		} else {
		  // dont allow twitter_id changing for twitter users
		  unset($this['twitter_id']);
		}		
		
		$this->widgetSchema->setNameFormat('settings[%s]');
	}
	
	public function updateObject($values = null){
		$object = parent::updateObject($values);
		if ($this->getValue('password_change'))
		  $object->setPasswordPlain($this->getValue('password_change'));
 		return $object;		
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null){
		parent::bind(array_merge($taintedValues, array('id' => sfContext::getInstance()->getUser()->getId())));
	}
	
}