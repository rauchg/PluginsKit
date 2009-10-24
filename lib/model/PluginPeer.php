<?php

class PluginPeer extends BasePluginPeer
{
	
	public static function retrieveBySlug($slug, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::SLUG, $slug);
		return self::doSelectOne($c);
	}
	
	public static function retrieveByGit($user, $repo, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::GITHUBUSER, $user)->add(self::GITHUBREPO, $repo);
		return self::doSelect($c);
	}
	
}
