<?php

class PluginAddForm extends PluginForm
{
	
	public function __construct(BaseObject $object = null, $options = array(), $CSRFSecret = null)
	{
		parent::__construct($object, $options, false);
	}
	
	public function configure(){
		parent::configure();
		
    unset($this['downloads_count'], $this['comments_count'], $this['slug'], $this['official'], $this['created_at'], $this['retrieved_at'], $this['updated_at'], $this['description_clean']);		
		unset($this->widgetSchema);
		
		$this->setWidgets(array('url' => new sfWidgetFormInput));		
		
		unset($this->validatorSchema['description_clean']);
		
		$this->validatorSchema['category'] = new sfValidatorPass;
		$this->validatorSchema['dependencies'] = new sfValidatorPass;
		$this->validatorSchema['screenshot'] = new sfValidatorPass;
		$this->validatorSchema['screenshots'] = new sfValidatorPass;
		$this->validatorSchema['tags'] = new sfValidatorPass;
		$this->validatorSchema['stabletag'] = new sfValidatorPass;
		$this->validatorSchema['gitTags'] = new sfValidatorPass;
		$this->validatorSchema['arbitrarySections'] = new sfValidatorPass;
	}
	
  public function setValidators(array $validators)
  {
    $this->setValidatorSchema(new ForgeValidatorSchema($validators));
  }
	
	public function doSave($con = null){
		parent::doSave($con);
		
		$this->object->setCategory($this->getValue('category'));
		$this->object->save();
		
		$this->object->setDependencies($this->getValue('dependencies'));
		$this->object->setScreenshot($this->getValue('screenshot'));		
		$this->object->setScreenshots($this->getValue('screenshots'));
		$this->object->setArbitrarySections($this->getValue('arbitrarySections'));
		$this->object->setTags($this->getValue('tags'));		
		$this->object->setGitTags($this->getValue('gitTags'), $this->getValue('stabletag'));		
	}
	
	public function bind(array $taintedValues = null, array $taintedFiles = null){
		return parent::bind(array_merge($taintedValues, array('author_id' => sfContext::getInstance()->getUser()->getId())));
	}
	
}