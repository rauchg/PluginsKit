<?php

class TermRelationship extends BaseTermRelationship
{
	
	public function save(PropelPDO $con = null){
		$isNew = $this->isNew();
		$ret = parent::save($con);
		if ($isNew && $this->getTerm())
		{
			$term = $this->getTerm();
			$term->setCount(intval($term->getCount()) + 1);
			$term->save();
		}
		return $ret;
	}
	
	public function delete(PropelPDO $con = null){
		if (!$this->isDeleted() && $this->getTerm())
		{
			$term = $this->getTerm();
			$term->setCount(intval($term->getCount()) - 1);
			$term->save();
		}
		return parent::delete($con);
	}
	
}
