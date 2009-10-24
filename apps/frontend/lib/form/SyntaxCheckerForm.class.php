<?php

class SyntaxCheckerForm extends sfForm
{
	
	public function configure(){
		$this->setWidgets(array(
			'readme' => new sfWidgetFormTextarea,
			'yaml' => new sfWidgetFormTextarea
		));		
		$this->setValidators(array(
			'readme' => new sfValidatorString(),
			'yaml' => new sfValidatorString(),
		));
		
		$this->validatorSchema->setPostValidator(new sfValidatorAnd(array(
		  new appValidatorForgeMD('readme'),
		  new appValidatorForgeYaml('yaml'),
		)));
		
		$this->widgetSchema->setNameFormat('syntax[%s]');
	}
	
	public function save()
  {    
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