<?php

class TermPeer extends BaseTermPeer
{
	
	public static function retrieveBySlug($slug, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::SLUG, $slug);
		return self::doSelectOne($c);
	}
	
	public static function retrieveCategories($criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::CATEGORY, true);
		return self::doSelect($c);
	}
	
	public static function retrieveByTitle($title, $criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::TITLE, $title);
		return self::doSelectOne($c);
	}
	
	public static function retrieveTags($criteria = null){
		$c = is_null($criteria) ? new Criteria() : clone $criteria;
		$c->add(self::CATEGORY, false);
		return self::doSelect($c);
	}
	
}
