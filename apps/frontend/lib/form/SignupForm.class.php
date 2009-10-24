<?php

class SignupForm extends AuthorForm 
{
	public function configure(){
		parent::configure();
		
		unset($this['id'], $this['avatar']);
		
		$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
		$this->widgetSchema['password_again'] = new sfWidgetFormInputPassword();
		
		$this->validatorSchema['password'] = new sfValidatorString(array('min_length' => 5));
		$this->validatorSchema['password_again'] = new sfValidatorString();			
		$this->validatorSchema->setPostValidator(
			new sfValidatorAnd(array(
        new sfValidatorPropelUnique(array('model' => 'Author', 'column' => array('email')), array('invalid' => 'The email supplied is already in our database.')),
        new sfValidatorPropelUnique(array('model' => 'Author', 'column' => array('username')), array('invalid' => 'The username supplied is already taken.')),
				new sfValidatorSchemaCompare('password', '==', 'password_again', array(), array('invalid' => 'Passwords do not match'))
      ))
		);

    $this->widgetSchema->setNameFormat('signup[%s]');
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