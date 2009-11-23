<?php

class PluginTagPeer extends BasePluginTagPeer
{
	
	public function retrieveByName($name, $criteria = null){
		$criteria = is_null($criteria) ? new Criteria : clone $criteria;
		$criteria->add(self::NAME, $name);
		return self::doSelectOne($criteria);
	}
	
}
