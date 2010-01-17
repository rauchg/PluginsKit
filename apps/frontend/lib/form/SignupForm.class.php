<?php

class SignupForm extends AuthorForm 
{
	public function configure(){
		parent::configure();
		
		unset($this['id'], $this['avatar']);

		$this->widgetSchema['phone'] = new sfWidgetFormInput();		
		$this->validatorSchema['phone'] = new sfValidatorCallback(array(
      'callback' => array($this, 'doValidate'),
      'required' => true
    ));
		
		$this->widgetSchema['password'] = new sfWidgetFormInputPassword();		
		$this->validatorSchema['password'] = new sfValidatorString(array('min_length' => 5));
		
    $this->widgetSchema->setNameFormat('signup[%s]');
	}
	
	public function doValidate($validator, $value){
	  if (trim(str_replace(' ', '', strtolower($value))) !== 'mootools'){
	    throw new sfValidatorError($validator, 'Please review the security question');
	  }
	}
	
	public function updateObject($values = null){
		$object = parent::updateObject($values);
		$object->setPasswordPlain($this->getValue('password'));
		$object->setCheckHash(md5(uniqid(time(), true)));
		return $object;
	}
	
	public function save($con = null)
  {    
		$object = parent::save($con);
		if ($object)
		{
			sfContext::getInstance()->getUser()->login($object);
		}
		return $object;
  }
	
}