<?php

class TwitterSignupForm extends AuthorForm 
{
	public function configure(){
		parent::configure();
		
		unset($this['password'], $this['id'], $this->widgetSchema['twitter_id'], $this->widgetSchema['avatar'], $this['admin']);

		$user = $this->getUser();
		$this->setDefaults(array(
			'username' => $user->getAttribute('screen_name', '', 'twitter'),
			'fullname' => $user->getAttribute('name', '', 'twitter'),
			'location' => $user->getAttribute('location', '', 'twitter'),
			'homepageurl' => $user->getAttribute('url', '', 'twitter'),
			'about' => $user->getAttribute('bio', '', 'twitter')
		));

    $this->widgetSchema->setNameFormat('twitterDetails[%s]');

		$this->validatorSchema['username']->setOption('required', true);
	}
	
	public function getUser(){
		return sfContext::getInstance()->getUser();
	}
	
	public function updateObject($values = null){
		$object = parent::updateObject($values);
		$object->setConfirmedEmail(true);
		return $object;
	}
	
	public function save($con = null)
  {    
		$this->getUser()->setAttribute('incomplete', false, 'twitter');
		return parent::save($con);
  }
	
	public function bind(array $taintedValues = null, array $taintedFiles = null){
		if ($this->getUser()->getAttribute('profile_image_url', null, 'twitter'))
			$taintedValues['avatar'] = $this->getUser()->getAttribute('profile_image_url', null, 'twitter');
		
		$taintedValues['twitter_id'] = $this->getUser()->getAttribute('screen_name', null, 'twitter');
		parent::bind($taintedValues);
	}
	
}