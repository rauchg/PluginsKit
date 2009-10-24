<?php

/**
* 
*/
class PasswordEmailForm extends sfForm
{
	
	public function configure(){
		$this->setWidgets(array(
			'email' => new sfWidgetFormInput
		));
		
		$this->setValidators(array(
			'email' => new sfValidatorPropelChoice(array('model' => 'Author', 'column' => 'email', 'required' => true), array('invalid' => 'Email not found', 'required' => 'If you want to retrieve your password, we need your email.'))
		));
		
		$this->widgetSchema->setNameFormat('forgot[%s]');
	}

	public function bindAndCheck(array $taintedValues = null, array $taintedFiles = null){
		$this->bind($taintedValues);
		return $this->isValid();
	}
	
}
