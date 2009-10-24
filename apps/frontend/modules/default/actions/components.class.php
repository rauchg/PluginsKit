<?php

class defaultComponents extends sfComponents
{
	
	function executeSidebar()
	{
		$c = new Criteria();
		$c->addAscendingOrderByColumn(TermPeer::TITLE);
		$this->categories = TermPeer::retrieveCategories($c);
		
		if (!$this->getUser()->isAuthenticated())
		{	
			# Standard
			$this->login = new LoginForm();
		}
		
		// Search
		$this->search = new SearchForm();
	}
	
}
