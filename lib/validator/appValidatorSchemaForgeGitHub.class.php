<?php

class appValidatorSchemaForgeGitHub extends sfValidatorSchema
{
	
	protected function configure($options = array(), $messages = array())
  {
	  $this->addOption('url_field', 'url');

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

		# Access repository
		$forge = ForgeGitHubFactory::fetch($values['url']);			
		
		try {			
			$forge->initialize($values['url']);
			
			if ($forge->getReadme()){
				$mdparser = new ForgeMDParser($forge->getReadme());	
			}
			
			if ($forge->getYaml()){
				$yaml = new ForgeYamlParser($forge->getYaml());	
			}
			
			$values = $values + array(
				'title' => $yaml->get('name'),
				'username' => $yaml->get('author'),
				'docsurl' => $yaml->get('docs'),
				'demourl' => $yaml->get('demo'),
				'githubuser' => $forge->getUser(),
				'githubrepo' => $forge->getProject(),
				'howtouse' => $mdparser->getSection('how-to-use'),
				'screenshot' => $mdparser->getScreenshot(),
				'description' => $mdparser->getDescription(),
				'tags' => $yaml->get('tags'),
				'category' => $yaml->get('category'),
				'stabletag' => $yaml->get('current'),
				'gitTags' => $forge->getTags(),
				'arbitrarySections' => $mdparser->getArbitrarySections(),
				'screenshots' => $mdparser->getScreenshots()
			);
			
		} catch (ForgeException $e) {				
			$errors[] = new sfValidatorError($this, $e->getMessage());
		}
		
		if (!empty($errors))
			throw new sfValidatorErrorSchema($this, $errors);	

    return $values;
  }

	
	function getMessage($code){
		return $code;
	}
	
}
