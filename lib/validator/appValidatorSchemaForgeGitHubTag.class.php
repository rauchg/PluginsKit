<?php

class appValidatorSchemaForgeGitHubTag extends sfValidatorSchema
{
	
	protected function configure($options = array(), $messages = array())
  {
		$this->addOption('tag_field', 'stabletag');
	  $this->addOption('url_field', 'url');

		$this->addMessage('not_found', 'Git Tag "%value%" not found in repository tags.');
	
    parent::configure($options, $messages);
  }
  
  protected function doClean($values)
  {
		$forge = ForgeGitHubFactory::fetch($values[$this->getOption('url_field')]);

		if ($values[$this->getOption('tag_field')]){
			if (!in_array($values[$this->getOption('tag_field')], $forge->getTags()))
			{
				throw new sfValidatorError($this, 'not_found', array('value' => $values[$this->getOption('tag_field')]));
			}	
		} else {
			$tags = $forge->getTags();
			if ($tags){
				$values[$this->getOption('tag_field')] = end($tags);
			} else {
				$values[$this->getOption('tag_field')] = null;
			}
		}
		
    return $values;
  }
	
}
