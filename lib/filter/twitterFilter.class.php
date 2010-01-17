<?php 

class twitterFilter extends sfFilter {
	
	public function execute($filterChain)
	{
		$forward = false;
		
		if ($this->isFirstCall())
		{
			$user = $this->getContext()->getUser();
			$request = $this->getContext()->getRequest();
			$controller = $this->getContext()->getController();

			# If user needs to complete registration, we forward to the required action
			if ($user->getAttribute('incomplete', false, 'twitter') && !in_array($this->getContext()->getActionName(), array('twitterSignup', 'logout')))
			{
				$controller->forward('user', 'twitterSignup');
				$forward = true;
			} else if($user->getAttribute('screen_name', false, 'twitter') && $user->twitterLogin($user->getAttribute('screen_name', null, 'twitter'))) {

			} else {	
				if (!$user->isAuthenticated())
				{
					if ($request->getParameter('oauth_token'))
					{
						$twitter = new EpiTwitter(sfConfig::get('app_twitter_consumer_key'), sfConfig::get('app_twitter_consumer_secret'));
						$twitter->setToken($request->getParameter('oauth_token'));
						$token = $twitter->getAccessToken();
						$twitter->setToken($token->oauth_token, $token->oauth_token_secret);
						$twitterInfo= $twitter->get_accountVerify_credentials();
						$twitterInfo->response;

						if ($twitterInfo->id)
						{
							// attempt to authenticate, if user is in db
							if ($user->twitterLogin($twitterInfo->screen_name)){
							  
							  // clear index cache
            		$cacheManager = sfContext::getInstance()->getViewCacheManager();
            		$cacheManager->remove('default/index');
							  
								$user->setFlash('notice', sprintf('Welcome back %s!', $user->getFirstName()));
								$controller->redirect('@homepage');
							} else {
								$user->setAttribute('incomplete', true, 'twitter');
								$user->setAttribute('screen_name', $twitterInfo->screen_name, 'twitter');							
								$user->setAttribute('location', $twitterInfo->location, 'twitter');							
								$user->setAttribute('name', $twitterInfo->name, 'twitter');																					
								$user->setAttribute('profile_image_url', $twitterInfo->profile_image_url, 'twitter');								
								$user->setAttribute('url', $twitterInfo->url, 'twitter');
								$user->setAttribute('bio', $twitterInfo->description, 'twitter');
								
								$controller->forward('user', 'twitterSignup');			
							}
							$forward = true;							
						} else {
							// authentication failed, reset token
							$user->setAttribute('authorize_url', false, 'twitter');
						}

					}
				}	
			}
		}
		
		if (!$forward)
			$filterChain->execute();
	}
	
}