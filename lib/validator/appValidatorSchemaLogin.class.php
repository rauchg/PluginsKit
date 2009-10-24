<?php

class appValidatorSchemaLogin extends sfValidatorSchema
{
  
  public function __construct($loginField, $passwordField, $options = array(), $messages = array())
  {
    $this->addOption('login_field', $loginField);
    $this->addOption('password_field', $passwordField);

    $this->addOption('throw_global_error', true);

    parent::__construct(null, $options, $messages);
  }
  
  /**
   * @see sfValidatorBase
   */
  protected function doClean($values)
  {
    if (is_null($values))
    {
      $values = array();
    }

    if (!is_array($values))
    {
      throw new InvalidArgumentException('You must pass an array parameter to the clean() method');
    }

    $loginValue  = isset($values[$this->getOption('login_field')]) ? $values[$this->getOption('login_field')] : null;
    $passwordValue = isset($values[$this->getOption('password_field')]) ? $values[$this->getOption('password_field')] : null;
    
    if(! $loginValue) return false;
    
    $valid = AuthorPeer::retrieveByEmailAndPassword($loginValue, sha1($passwordValue));

    if (!$valid)
    {
      $error = new sfValidatorError($this, 'invalid', array(
        'login_field'  => $loginValue,
        'password_field' => $passwordValue
      ));
      if ($this->getOption('throw_global_error'))
      {
        throw $error;
      }

      throw new sfValidatorErrorSchema($this, array($this->getOption('login_field') => $error));
    }

    return $values;
  }
  
}