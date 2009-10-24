<?php

/**
 * Author form.
 *
 * @package    mooforge
 * @subpackage form
 * @author     Guillermo Rauch
 * @version    SVN: $Id: AuthorForm.class.php 1 2009-09-06 18:35:08Z rauchg@gmail.com $
 */
class AuthorForm extends BaseAuthorForm
{
	
  public function configure()
  {
		unset($this['created_at'], $this['plugins_count'], $this['logged_at'], $this['confirmed_email'], $this['checkhash'], $this['admin']);
	
		$this->widgetSchema['about'] = new sfWidgetFormTextarea();
		$this->validatorSchema['homepageurl'] = new sfValidatorUrl(array('required' => false));
	
	  $this->validatorSchema['email'] = new sfValidatorEmail(array('trim' => true));
    $validators = $this->validatorSchema->getPostValidator()->setMessage('invalid', 'This email is already in use.');
  }
}
