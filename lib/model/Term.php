<?php

class Term extends BaseTerm
{
	
	public function __toString(){
		return $this->getTitle();
	}
	
}

$columns_map = array('from'   => TermPeer::TITLE,
                     'to'     => TermPeer::SLUG);

sfPropelBehavior::add('Term', array('sfPropelActAsSluggableBehavior' => array('columns' => $columns_map, 'separator' => '_', 'permanent' => true)));