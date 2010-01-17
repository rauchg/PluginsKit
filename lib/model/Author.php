<?php

class Author extends BaseAuthor
{
  
  public function __toString(){
    return $this->getUsername();
  }
	
	public function save(PropelPDO $con = null){
		// unconfirm email upon change
		if (!$this->isNew() && in_array(AuthorPeer::EMAIL, $this->modifiedColumns)){
			$this->setConfirmedEmail(false);
		}
		
		parent::save($con);		
	}
	
	public function hasConfirmedEmail(){
		return $this->getConfirmedEmail();
	}
	
	public function isAdmin(){
		return $this->getAdmin();
	}
	
	public function setPasswordPlain($text){
		$this->setPassword(sha1($text));
	}
	
	public function getFirstName(){
		$parts = explode(' ', $this->getFullName());
		return array_shift($parts);
	}
	
	public function ownsPlugin($plugin){
		return ($plugin->getAuthorId() == $this->getId());
	}
	
}
