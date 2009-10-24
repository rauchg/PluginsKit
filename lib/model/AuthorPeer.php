<?php

class AuthorPeer extends BaseAuthorPeer
{
	
	public static function retrieveByUsername($username, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::USERNAME, $username);
		return self::doSelectOne($c);
	}
	
	public static function retrieveByTwitterId($id, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::TWITTER_ID, $id);
    $c->add(self::PASSWORD, null, Criteria::ISNULL); # not registered normally
		return self::doSelectOne($c);
	}
	
	public static function retrieveByEmailAndPassword($login, $password, $criteria = null)
  {
		if (empty($password)) return null;
    $c = is_null($criteria) ? new Criteria() : clone $criteria;
    $c->add(self::EMAIL, $login);
    $c->add(self::PASSWORD, $password);
    
    return self::doSelectOne($c);
  }

	public static function retrieveByEmail($login, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
    $c->add(self::EMAIL, $login);
    return self::doSelectOne($c);
	}
	
	public static function retrieveByCheckHash($hash, $criteria = null){
		if (!strlen($hash)) return null;
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
    $c->add(self::CHECKHASH, $hash);
    return self::doSelectOne($c);
	}
	
}
