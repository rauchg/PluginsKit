<?php

class appValidatorSchemaUsernameCheck extends sfValidatorSchema
{
	
	protected function configure($options = array(), $messages = array())
  {
		$this->addOption('username_field', 'username');
	  $this->addOption('url_field', 'url');

		$this->addMessage('not_found', 'You must provide your username in the "author" field of your package.yml');
		$this->addMessage('no_match', 'The "author:" field in package.yml does not match your username "%actual%"');
	
    parent::configure($options, $messages);
  }
  
  protected function doClean($values)
  {
		if (sfConfig::get('app_plugin_dev_loose_mode') && sfConfig::get('sf_environment') == 'dev'){
			return $values;
		}
	
		$forge = ForgeGitHubFactory::fetch($values[$this->getOption('url_field')]);

		if (isset($values[$this->getOption('username_field')])){
			if ($values[$this->getOption('username_field')]){
				if (sfContext::getInstance()->getUser()->getUsername() != $values[$this->getOption('username_field')])
				{
					throw new sfValidatorError($this, 'no_match', array('actual' => sfContext::getInstance()->getUser()->getUsername()));
				}	
			} else {
				throw new sfValidatorError($this, 'not_found');
			}	
		}
		
    return $values;
  }
	
}
