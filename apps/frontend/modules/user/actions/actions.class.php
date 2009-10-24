<?php

/**
 * user actions.
 *
 * @package    mooforge
 * @subpackage user
 * @author     Guillermo Rauch
 * @version    SVN: $Id: actions.class.php 22 2009-10-12 08:38:33Z rauchg@gmail.com $
 */
class userActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
		$this->redirect('@homepage');
  }

 /**
  * Executes signup action
  *
  * @param sfRequest $request A request object
  */
  public function executeSignup(sfWebRequest $request)
  {
		$this->form = new SignupForm();
		if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter('signup')))
		{
			$this->getUser()->setFlash('notice', 'Signed up successfully! You\'ve been logged in as well');
			$user = $this->form->getObject();
			
			$mailBody = $this->getPartial('signupEmail', array('name' => $user->getFirstName(), 'email' => $user->getEmail(), 'password' => $this->form->getValue('password'), 'hash' => $user->getCheckHash()));			
			
			require_once('lib/vendor/swift/swift_init.php'); # needed due to symfony autoloader
			$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
			$message = Swift_Message::newInstance('Welcome to MooTools Plugins')
									->setFrom(array(sfConfig::get('app_webmaster_email') => 'MooTools Plugins'))
									->setTo(array($user->getEmail() => $user->getFullname()))
									->setBody($mailBody, 'text/html');  
			$mailer->send($message);			
			
			$this->redirect('@homepage');
		}
  }

 /**
  * Executes twitterSignup action
  *
  * @param sfRequest $request A request object
  */
  public function executeTwitterSignup(sfWebRequest $request)
  {
		$this->form = new TwitterSignupForm();
		if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter('twitterDetails')))
		{
			$user = $this->form->getObject();
			$mailBody = $this->getPartial('signupTwitterEmail', array('name' => $user->getFirstName()));						
			
			require_once('lib/vendor/swift/swift_init.php'); # needed due to symfony autoloader
			$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
			$message = Swift_Message::newInstance('Welcome to MooTools Plugins')
									->setFrom(array(sfConfig::get('app_webmaster_email') => 'MooTools Plugins'))
									->setTo(array($user->getEmail() => $user->getFullname()))
									->setBody($mailBody, 'text/html');  
			$mailer->send($message);
			
			$this->getUser()->setFlash('notice', 'Twitter signup successful! Next time you use Signin with Twitter you\'ll be smoothly logged in');
			$this->redirect('@homepage');
		}
  }

 /**
  * Executes login action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogin(sfWebRequest $request)
  {
		if ($this->getUser()->isAuthenticated())
		{
			$this->redirect('@homepage');
		} else {
			$this->form = new LoginForm();
			if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter('login')))
			{
				$this->getUser()->setFlash('notice', sprintf('Welcome back %s!', $this->getUser()->getFirstName()));
				$this->redirect('@homepage');
			}			
		}
  }

	public function executeLoginForgot(sfWebRequest $request){
		$user = AuthorPeer::retrieveByCheckHash($request->getParameter('hash'));
		$this->forward404Unless($user);
		
		$user->setCheckHash('');
		$user->save();
		
		$this->getUser()->login($user);
		$this->getUser()->setFlash('Logged in. Please change your password');
		$this->redirect('@settings');
	}
	
	public function executeConfirmEmail(sfWebRequest $request){
		$user = AuthorPeer::retrieveByCheckHash($request->getParameter('hash'));
		$this->forward404Unless($user);
		
		$user->setConfirmedEmail(true);
		$user->setCheckHash('');
		$user->save();
		
		$this->getUser()->login($user);
		$this->getUser()->setFlash('notice', 'Your email address has been confirmed');
		$this->redirect('@homepage');
	}
	
	public function executeRequestConfirm(sfWebRequest $request){
		$this->forward404If($this->getUser()->hasConfirmedEmail());

		$user = $this->getUser()->getObject();
		$user->setCheckHash(md5(uniqid(time(), true)));
		$user->save();
		
		$mailBody = $this->getPartial('confirmEmail', array('hash' => $user->getCheckHash(), 'name' => $user->getFirstName()));					
		
		require_once('lib/vendor/swift/swift_init.php'); # needed due to symfony autoloader
		$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
		$message = Swift_Message::newInstance('Confirm your email')
								->setFrom(array(sfConfig::get('app_webmaster_email') => 'MooTools Plugins'))
								->setTo(array($user->getEmail() => $user->getFullname()))
								->setBody($mailBody, 'text/html');  
		$mailer->send($message);
		
		$this->getUser()->setFlash('notice', 'Your confirmation email has been sent to ' . $user->getEmail());
		$this->redirect('@settings');
	}

	public function executeLoginTwitter(){
		$twitter = new EpiTwitter(sfConfig::get('app_twitter_consumer_key'), sfConfig::get('app_twitter_consumer_secret'));
		header('Location:' . $twitter->getAuthorizationUrl());
		exit;
	}

 /**
  * Executes logout action
  *
  * @param sfRequest $request A request object
  */
  public function executeLogout(sfWebRequest $request)
  {
		$this->getUser()->logout();
		$this->redirect('@homepage');
  }

 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit(sfWebRequest $request)
  {
		$this->form = new UserSettingsForm($this->getUser()->getObject());				
		if ($request->isMethod('post') && $this->form->bindAndSave($request->getParameter('settings')))
		{
			$this->getUser()->setFlash('notice', 'Settings saved');
		}
  }

	public function executeView(sfWebRequest $request){
		$this->author = AuthorPeer::retrieveByUsername($request->getParameter('username'));
		$this->forward404Unless($this->author);		
		
		if ($this->author->getPluginsCount())
		{
			$c = new Criteria();
			$c->addDescendingOrderByColumn(PluginPeer::DOWNLOADS_COUNT);
			$c->add(PluginPeer::AUTHOR_ID, $this->author->getId());		
			$this->plugins = PluginPeer::doSelect($c);			
		}
	}
	
	public function executeForgot(sfWebRequest $request){
		$this->forward404If($this->getUser()->isAuthenticated());
		
		$this->form = new PasswordEmailForm();
		if ($request->isMethod('post') && $this->form->bindAndCheck($request->getParameter('forgot'))){
			$user = AuthorPeer::retrieveByEmail($this->form->getValue('email'));
			
			if (!$user->getPassword())
			{
				$this->getUser()->setFlash('notice', 'The provided email is not a normal account with a password to recover. You probably used Sign in with Twitter.');
				$this->redirect('@homepage');
			}
			
			$hash = md5(uniqid(time(), true));
			$user->setCheckHash($hash);
			$user->save();
			$mailBody = $this->getPartial('forgotEmail', array('hash' => $hash, 'name' => $user->getFirstName()));			
			
			require_once('lib/vendor/swift/swift_init.php'); # needed due to symfony autoloader
			$mailer = Swift_Mailer::newInstance(Swift_MailTransport::newInstance());
			$message = Swift_Message::newInstance('Retrieve your password')
									->setFrom(array(sfConfig::get('app_webmaster_email') => 'MooTools Plugins'))
									->setTo(array($user->getEmail() => $user->getFullname()))
									->setBody($mailBody, 'text/html');  
			$mailer->send($message);
			
			$this->getUser()->setFlash('notice', 'An email with a confirmation link has been sent');
		}
	}

}
