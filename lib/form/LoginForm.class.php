<?php

class LoginForm extends sfForm
{
    
  public function configure()
  {
    $this->setWidgets(array(
      'email'      => new sfWidgetFormInput(),
      'password'   => new sfWidgetFormInputPassword()
    ));

    $this->setValidators(array(
      'email'      => new sfValidatorString(array('required' => true), array('required' => 'Login is required')),
      'password'   => new sfValidatorString(array('required' => true), array('required' => 'Password is required')),
    ));
    
    $this->validatorSchema->setPostValidator(new appValidatorSchemaLogin('email', 'password', array(), array('invalid' => 'Bad login')));

    $this->widgetSchema->setNameFormat('login[%s]');    
  }
  
  public function save()
  {    
    sfContext::getInstance()->getUser()->emailLogin($this->getValue('email'));
  }
  
  public function bindAndSave($taintedValues)
  {
    $this->bind($taintedValues);
    if ($this->isValid())
    {
      $this->save();
      return true;
    }
    return false;
  }
  
}