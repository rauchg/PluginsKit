<?php

class myUser extends sfBasicSecurityUser
{
	
	protected $data = null;
	
	public function logout(){
		// clear session
		$this->getAttributeHolder()->clear();
		$this->clearCredentials();
		$this->setAuthenticated(false);
	}
	
	public function login(Author $author){
		if (!$this->data)
		{
			$this->data = $author;
			
			sfConfig::set('sf_cache', false);
			
			if (!$this->isAuthenticated())
			{
				$this->setAttribute('id', $author->getId());
				$this->setAuthenticated(true);
				$this->addCredential('contributor');
				if ($author->isAdmin()) $this->addCredential('admin');	
				$author->setLoggedAt(time());
				$author->save();
			}			
		}
	}
	
	# Login via twitter
	public function twitterLogin($id = null){
		if ($id)
		{
			$author = AuthorPeer::retrieveByTwitterId($id);
			if ($author)
			{
				$this->login($author);
				return true;
			} 			
		}
		return false;
	}
	
	# Login via session id
	public function idLogin($id){
		if (is_numeric($id))
		{
			$author = AuthorPeer::retrieveByPk($id);
			if ($author)
			{
				$this->login($author);
				return true;
			} 			
		}
		return false;
	}
	
	public function emailLogin($email){
		if ($email)
		{
			$author = AuthorPeer::retrieveByEmail($email);
			if ($author)
			{
				$this->login($author);
				return true;
			} 			
		}
		return false;
	}
	
	public function getObject(){
		return $this->data;
	}
	
	public function __call($m, $a)
  {
    if(! $this->data) return false;
    return call_user_func_array(array($this->data, $m), $a);    
  }
	
}
