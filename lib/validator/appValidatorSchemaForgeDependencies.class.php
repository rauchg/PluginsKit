<?php

class appValidatorSchemaForgeDependencies extends sfValidatorSchema
{
	
	protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('dependencies_field', 'requires');	

		$this->addMessage('no_package', 'A dependency is missing a "package:" attribute.');
		$this->addMessage('missing_tag', 'Please provide the version parameter for the forge plugin "%value%" with the release tag your plugin is known to work with.');
		$this->addMessage('plugin_not_found', 'The package http://mootools.net/plugins/p/<strong>"%value%"</strong> does not exist.');
		$this->addMessage('tag_not_found', 'The plugin "%plugin%" doesn\'t have a release called "%value%"');
		
    parent::configure($options, $messages);
  }
  
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
			
		$errors = array();
		$deps = isset($values[$this->getOption('dependencies_field')]) ? $values[$this->getOption('dependencies_field')] : array();
		
		if (!empty($deps)){
			foreach ($deps as $i => $dep){
				if (!isset($dep['package']) || !$dep['package'])
				{
					$errors[] = new sfValidatorError($this, 'no_package');
				} else {
					if (!ForgeToolkit::isUrl($dep['package']))
					{
						if (!isset($dep['version']) || !$dep['version'])
						{
							$errors[] = new sfValidatorError($this, 'missing_tag', array('value' => $results[3]));
						}
					
						$plugin = PluginPeer::retrieveBySlug($results[3]);
						if ($plugin)
						{
							if ($tag = $plugin->getGitTagByName($dep['tag']))
							{
								$values[$i]['plugin_tag_id'] = $tag->getId();
								$errors[] = new sfValidatorError($this, 'tag_not_found', array('plugin' => $results[3], 'value' => $dep['tag']));
							}
						} else {
							$errors[] = new sfValidatorError($this, 'plugin_not_found', array('value' => $dep['package']));
						}
					} else {
						$values[$i]['external'] = true;
						$values[$i]['url'] = $dep['package'];
						$values[$i]['title'] = ForgeToolkit::retrieveTitleFromURL($dep['package']);
					}	
								
				}						
			}
		}
			
		if (!empty($errors))
			throw new sfValidatorErrorSchema($this, $errors);	

    return $values;
  }
	
}
